<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class ActiveDataHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveDataHelper
{
    /**
     * @param string $modelClass
     * @param array $data
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public static function typecast(string $modelClass, array $data): array
    {
        if (is_subclass_of($modelClass, ActiveRecord::class)) {
            $columns = $modelClass::getTableSchema()->columns;
            $closure = static function ($row) use ($columns) {
                foreach ($row as $name => $value) {
                    if (isset($columns[$name])) {
                        $row[$name] = $columns[$name]->phpTypecast($value);
                    }
                }
                return $row;
            };
            if (ArrayHelper::isAssociative($data, false)) {
                return $closure($data);
            }
            return array_map($closure, $data);
        }
        return $data;
    }
}
