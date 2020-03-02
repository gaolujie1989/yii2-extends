<?php

namespace lujie\ar\relation\behaviors\tests\unit\fixtures\models;

/**
 * This is the model class for table "test_customer".
 *
 * @property string $test_customer_id
 * @property string $customer_email
 */
class TestCustomer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test_customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_email', 'username'], 'string', 'max' => 255],
            [['customer_email'], 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'test_customer_id' => 'Test Customer ID',
            'customer_email' => 'Customer Email',
            'username' => 'Username',
        ];
    }
}
