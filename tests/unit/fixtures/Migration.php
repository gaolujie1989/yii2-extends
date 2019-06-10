<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\db\fieldQuery\behaviors\tests\unit\fixtures;

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

    /**
     * @return MigrationQuery|\yii\db\ActiveQuery
     * @inheritdoc
     */
    public static function find()
    {
        return new MigrationQuery(static::class);
    }
}
