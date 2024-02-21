<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

/**
 * Class m191211_101749_user_app
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @deprecated
 */
class m191211_101749_user_app extends Migration
{
    use TraceableColumnTrait, DropTableTrait;

    public $tableName = '{{%user_app}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'user_app_id' => $this->bigPrimaryKey(),
            'user_id' => $this->bigInteger()->notNull()->defaultValue(0),
            'name' => $this->string(200)->notNull()->defaultValue(''),
            'key' => $this->string(32)->notNull()->defaultValue('')->unique(),
            'secret' => $this->string(32)->notNull()->defaultValue(''),
            'status' => $this->tinyInteger()->notNull()->defaultValue(10),
        ]);

        $this->createIndex('uk_user_id_name', $this->tableName, ['user_id', 'name'], true);
    }
}
