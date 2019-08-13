<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use yii\db\ActiveRecord;

/**
 * Class ActiveDataHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveDataHelper
{
    /**
     * @param string|ActiveRecord $modelClass
     * @param array $data
     * @return array
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public static function typecast(string $modelClass, array $data): array
    {
        $columns = $modelClass::getTableSchema()->columns;
        return array_map(static function ($row) use ($columns) {
            foreach ($row as $name => $value) {
                if (isset($columns[$name])) {
                    $row[$name] = $columns[$name]->phpTypecast($value);
                }
            }
        }, $data);
    }
}
