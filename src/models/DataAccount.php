<?php

namespace lujie\data\center\models;

use Yii;

/**
 * This is the model class for table "{{%data_account}}".
 *
 * @property int $data_account_id
 * @property string $name
 * @property string $type
 * @property string $url
 * @property string $username
 * @property string $password
 * @property array $options
 * @property int $status
 * @property int $owner_id
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 */
class DataAccount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%data_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['options'], 'safe'],
            [['status', 'owner_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 50],
            [['url', 'username', 'password'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'data_account_id' => 'Data Account ID',
            'name' => 'Name',
            'type' => 'Type',
            'url' => 'Url',
            'username' => 'Username',
            'password' => 'Password',
            'options' => 'Options',
            'status' => 'Status',
            'owner_id' => 'Owner ID',
        ];
    }
}
