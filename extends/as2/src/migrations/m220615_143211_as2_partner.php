<?php

use lujie\extend\db\Migration;

class m220615_143211_as2_partner extends Migration
{
    public $tableName = '{{%as2_partner}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->bigPrimaryKey(),
            'partner_name' => $this->string(50)->notNull()->defaultValue(''),
            'partner_type' => $this->string(50)->notNull()->defaultValue(''),

            'as2_id' => $this->string(50)->notNull()->defaultValue(''),
            'email' => $this->string(50)->notNull()->defaultValue(''),
            'target_url' => $this->string(200)->notNull()->defaultValue(''),

            'content_type' => $this->string(50)->notNull()->defaultValue(''),
            'content_transfer_encoding' => $this->string(10)->notNull()->defaultValue(''),
            'subject' => $this->string(200)->notNull()->defaultValue(''),

            'auth_method' => $this->string(20)->notNull()->defaultValue(''),
            'auth_user' => $this->string(50)->notNull()->defaultValue(''),
            'auth_password' => $this->string(50)->notNull()->defaultValue(''),

            'signature_algorithm' => $this->string(10)->notNull()->defaultValue(''),
            'encryption_algorithm' => $this->string(11)->notNull()->defaultValue(''),

            'certificate' => $this->text(),
            'private_key' => $this->text(),
            'private_key_pass_phrase' => $this->string(50)->notNull()->defaultValue(''),
            'compression_type' => $this->string(10)->notNull()->defaultValue(''),

            'mdn_mode' => $this->string(10)->notNull()->defaultValue(''),
            'mdn_options' => $this->string(50)->notNull()->defaultValue(''),
            'mdn_subject' => $this->string(200)->notNull()->defaultValue(''),

            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('uk_as2_id', $this->tableName, ['as2_id']);
        $this->createIndex('uk_email', $this->tableName, ['email']);
    }
}
