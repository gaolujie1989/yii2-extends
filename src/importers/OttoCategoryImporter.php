<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\importers;

use lujie\data\exchange\DataExchanger;
use lujie\data\exchange\pipelines\CombinedPipeline;
use lujie\data\exchange\pipelines\DbPipeline;
use lujie\data\exchange\transformers\TransformerInterface;
use lujie\sales\channel\models\OttoAttribute;
use lujie\sales\channel\models\OttoCategory;
use lujie\sales\channel\models\OttoCategoryGroup;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Class OttoCategoryImporter
 * @package lujie\sales\channel\importers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoCategoryImporter extends DataExchanger implements TransformerInterface
{
    /**
     * @var array
     */
    public $pipeline = [
        'class' => CombinedPipeline::class,
        'pipelines' => [
            'categoryGroups' => [
                'class' => DbPipeline::class,
                'modelClass' => OttoCategoryGroup::class,
                'indexKeys' => ['category_group'],
            ],
            'categories' => [
                'class' => DbPipeline::class,
                'modelClass' => OttoCategory::class,
                'indexKeys' => ['category_group', 'name'],
            ],
            'attributes' => [
                'class' => DbPipeline::class,
                'modelClass' => OttoAttribute::class,
                'indexKeys' => ['attribute_group', 'name'],
            ],
        ]
    ];

    /**
     * @param array $data
     * @return array[]
     * @inheritdoc
     */
    public function transform(array $data): array
    {
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
        $transformedCategoryGroups = [];
        $transformedCategories = [];
        $transformedAttributes = [];
        foreach ($data as $categoryGroup) {
            $transformedCategoryGroups[] = [
                'category_group' => $categoryGroup['categoryGroup'],
                'categories' => $categoryGroup['categories'],
                'title' => $categoryGroup['title'],
                'attributes' => ArrayHelper::getColumn($categoryGroup['attributes'], 'name'),
                'variation_themes' => $categoryGroup['variationThemes'],
                'otto_created_at' => empty($categoryGroup['createdAt']) ? 0 : strtotime($categoryGroup['createdAt']),
                'otto_updated_at' => empty($categoryGroup['lastModified']) ? 0 : strtotime($categoryGroup['lastModified']),
            ];
            foreach ($categoryGroup['categories'] as $categoryName) {
                $transformedCategories[] = [
                    'category_group' => $categoryGroup['categoryGroup'],
                    'name' => $categoryName,
                ];
            }
            foreach ($categoryGroup['attributes'] as $attribute) {
                $transformedAttribute = [];
                foreach ($attribute as $key => $value) {
                    $transformedAttribute[Inflector::underscore($key)] = $value;
                }
                $transformedAttributes[] = array_merge($defaultAttribute, $transformedAttribute);
            }
        }
        return [
            'categoryGroups' => $transformedCategoryGroups,
            'categories' => $transformedCategories,
            'attributes' => $transformedAttributes,
        ];
    }
}