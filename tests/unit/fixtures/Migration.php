<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace tests\unit\fixtures;

use yii\db\ActiveRecord;

/**
 * Class Migration
 * @package tests\unit\fixtures
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Migration extends ActiveRecord
{
    /**
     * @return string
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%migration}}';
    }
}
