<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\providers;

use lujie\common\option\models\Option;
use lujie\common\option\searches\OptionSearch;
use lujie\extend\helpers\QueryHelper;
use yii\base\BaseObject;

/**
 * Class OptionProvider
 * @package lujie\common\option
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OptionProvider extends BaseObject implements OptionProviderInterface
{
    /**
     * @param string $type
     * @return bool
     * @inheritdoc
     */
    public function hasType(string $type): bool
    {
        return Option::find()->type($type)->exists();
    }

    /**
     * @param string $type
     * @param string $key
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    public function getOptions(string $type, string $key = ''): array
    {
        $query = Option::find()->type($type)->orderBy(['position' => SORT_ASC]);
        if ($key) {
            QueryHelper::filterKey($query, ['value', 'name', 'labels'], $key);
        }
        return OptionSearch::prepareRows($query->all());
    }
}