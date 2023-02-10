<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tasks;

use lujie\data\exchange\pipelines\DbPipeline;
use lujie\data\exchange\pipelines\PipelineInterface;
use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
use lujie\extend\helpers\TemplateHelper;
use lujie\sales\channel\channels\otto\OttoSalesChannel;
use lujie\sales\channel\models\OttoCategory;
use lujie\sales\channel\models\SalesChannelAccount;
use lujie\sales\channel\SalesChannelInterface;
use lujie\sales\channel\SalesChannelManager;
use lujie\scheduling\CronTask;
use yii\base\InvalidArgumentException;
use yii\base\UserException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Class PullOttoCategoryTask
 * @package lujie\sales\channel\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullOttoCategoryTask extends CronTask implements ProgressInterface
{
    use ProgressTrait;

    /**
     * @var SalesChannelManager
     */
    public $salesChannelManager = 'salesChannelManager';

    /**
     * @var string
     */
    public $accountName;

    /**
     * @var int
     */
    public $page = 0;

    /**
     * @var DbPipeline
     */
    public $categoryPipeline = [
        'class' => DbPipeline::class,
        'modelClass' => OttoCategory::class,
        'indexKeys' => ['category_group', 'name'],
    ];

    /**
     * @var DbPipeline
     */
    public $attributePipeline = [
        'class' => DbPipeline::class,
        'modelClass' => OttoCategory::class,
        'indexKeys' => ['attribute_group', 'name'],
    ];

    /**
     * @return \Generator
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\IntegrityException
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        $salesChannel = $this->getService($this->accountName);
        if (!($salesChannel instanceof OttoSalesChannel)) {
            throw new UserException('SalesChannel is not OTTO');
        }
        $this->categoryPipeline = Instance::ensure($this->categoryPipeline, PipelineInterface::class);
        $this->attributePipeline = Instance::ensure($this->categoryPipeline, PipelineInterface::class);
        $batchCategories = $salesChannel->client->batchV3ProductCategories(['page' => $this->page]);
        $defaultAttribute = [
            'attribute_group' => '',
            'name' => '',
            'type' => '',
            'multi_value' => 0,
            'unit' => '',
            'unit_display_name' => '',
            'allowed_values' => [],
            'feature_relevance' => [],
            'related_media_assets' => [],
            'relevance' => '',
            'description' => '',
            'example_values' => [],
            'recommended_values' => [],
            'reference' => '',
        ];
        $progress = $this->getProgress(100);
        foreach ($batchCategories as $categories) {
            $transformedCategories = [];
            $transformedAttributes = [];
            foreach ($categories as $category) {
                $transformedCategories[] = [
                    'category_group' => $category['categoryGroup'],
                    'name' => $category['name'],
                    'title' => $category['title'],
                    'attributes' => ArrayHelper::getColumn($category['attributes'], 'name'),
                    'variation_themes' => $category['variationThemes'],
                    'otto_created_at' => strtotime($category['createdAt']),
                    'otto_updated_at' => strtotime($category['lastModified']),
                ];
                foreach ($category['attributes'] as $attribute) {
                    $transformedAttribute = [];
                    foreach ($attribute as $key => $value) {
                        $transformedAttribute[Inflector::underscore($key)] = $value;
                    }
                    $transformedAttributes[] = array_merge($defaultAttribute, $transformedAttribute);
                }
            }
            $this->categoryPipeline->process($transformedCategories);
            $this->attributePipeline->process($transformedAttributes);
            $progress->message = TemplateHelper::render('[Category:C:{created};U:{updated};S:{skipped}]', $this->categoryPipeline->getAffectedRowCounts())
                . TemplateHelper::render('[Attribute:C:{created};U:{updated};S:{skipped}]', $this->attributePipeline->getAffectedRowCounts());
            $progress->done += count($categories);
            yield true;
        }
    }

    /**
     * @param string $accountName
     * @return SalesChannelAccount
     * @inheritdoc
     */
    protected function getAccount(string $accountName): SalesChannelAccount
    {
        $salesChannelAccount = SalesChannelAccount::find()->name($accountName)->cache()->one();
        if ($salesChannelAccount === null) {
            throw new InvalidArgumentException("Account {$accountName} not found");
        }
        return $salesChannelAccount;
    }

    /**
     * @param string $accountName
     * @return SalesChannelInterface
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getService(string $accountName): SalesChannelInterface
    {
        $account = $this->getAccount($accountName);
        $this->salesChannelManager = Instance::ensure($this->salesChannelManager, SalesChannelManager::class);
        return $this->salesChannelManager->salesChannelLoader->get($account->account_id);
    }
}