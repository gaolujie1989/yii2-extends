<?php

namespace lujie\sales\order\center\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%customer}}".
 *
 * @property string $customer_id
 * @property string $email
 * @property string $phone
 * @property string $ebay_name
 * @property string $first_name
 * @property string $last_name
 * @property array $additional
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 */
class Customer extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%customer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['email'], 'required'],
            [['additional'], 'safe'],
            [['email'], 'string', 'max' => 100],
            [['phone', 'ebay_name'], 'string', 'max' => 50],
            [['first_name', 'last_name'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'customer_id' => Yii::t('sales/order', 'Customer ID'),
            'email' => Yii::t('sales/order', 'Email'),
            'phone' => Yii::t('sales/order', 'Phone'),
            'ebay_name' => Yii::t('sales/order', 'Ebay Name'),
            'first_name' => Yii::t('sales/order', 'First Name'),
            'last_name' => Yii::t('sales/order', 'Last Name'),
            'additional' => Yii::t('sales/order', 'Additional'),
        ];
    }

    /**
     * @return CustomerQuery
     * @inheritdoc
     */
    public static function find(): CustomerQuery
    {
        return new CustomerQuery(static::class);
    }
}
