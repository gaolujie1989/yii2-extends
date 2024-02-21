<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\option\providers;

use lujie\common\option\providers\DbOptionProvider;
use lujie\sales\channel\models\OttoCategory;
use yii\db\QueryInterface;

/**
 * Class OttoCategoryProvider
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoCategoryProvider extends DbOptionProvider
{
    public $modelClass = OttoCategory::class;

    public $filterKeys = ['category_group', 'name'];

    public $separator = '>>';

    /**
     * @param string $type
     * @param string|null $key
     * @param array|null $values
     * @param array|null $params
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getOptions(string $type, ?string $key = null, ?array $values = null, ?array $params = null): array
    {
        $ottoCategories = parent::getOptions($type, $key, $values, $params);
        return array_map(function(array $category) {
            $categoryPath = $category['category_group'] . $this->separator . $category['name'];
            return [
                'value' => $categoryPath,
                'label' => $categoryPath,
            ];
        }, $ottoCategories);
    }

    /**
     * @param string $type
     * @param string|null $key
     * @return QueryInterface
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getQuery(string $type, ?string $key = null, ?array $values = null, ?array $params = null): QueryInterface
    {
        $query = parent::getQuery($type, $key, $values, $params);
        $query->addOrderBy(['category_group' => SORT_ASC, 'name' => SORT_ASC]);
        return $query;
    }
}
