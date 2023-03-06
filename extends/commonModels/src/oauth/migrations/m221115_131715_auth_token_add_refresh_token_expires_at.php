<?php

use lujie\extend\db\DropTableTrait;
use lujie\extend\db\TraceableColumnTrait;
use yii\db\Migration;

class m221115_131715_auth_token_add_refresh_token_expires_at extends Migration
{
    public $tableName = '{{%auth_token}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'refresh_token_expires_at', $this->integer()->unsigned()->notNull()->defaultValue(0)->after('expires_at'));
    }
}
