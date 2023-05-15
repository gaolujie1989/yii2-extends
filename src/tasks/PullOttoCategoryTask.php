<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tasks;

use lujie\data\exchange\DataExchanger;
use lujie\sales\channel\importers\OttoCategoryImporter;
use yii\base\UserException;

/**
 * Class PullOttoCategoryTask
 * @package lujie\sales\channel\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PullOttoCategoryTask extends BasePullOttoCommonItemsTask
{
    /**
     * @var DataExchanger
     */
    public $importer = OttoCategoryImporter::class;

    /**
     * @return \Generator
     * @throws UserException
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function batchOttoItems(): \Generator
    {
        $salesChannel = $this->getOttoSalesChannel();
        return $salesChannel->client->batchV3ProductCategories(['page' => $this->page]);
    }
}
