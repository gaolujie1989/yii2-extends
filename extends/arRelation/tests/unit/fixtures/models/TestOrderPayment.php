<?php

namespace lujie\ar\relation\behaviors\tests\unit\fixtures\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "test_order_payment".
 *
 * @property string $test_order_payment_id
 * @property string $test_order_id
 * @property string $transaction_no
 * @property int $paid_amount
 */
class TestOrderPayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test_order_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['test_order_id', 'paid_amount'], 'integer'],
            [['transaction_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'test_order_payment_id' => 'Test Order Payment ID',
            'test_order_id' => 'Test Order ID',
            'transaction_no' => 'Transaction No',
            'paid_amount' => 'Paid Amount',
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getOrder(): ActiveQuery
    {
        return $this->hasOne(TestOrder::class, ['test_order_id'=> 'test_order_id']);
    }
}
