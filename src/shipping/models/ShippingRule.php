<?php

namespace lujie\common\shipping\models;

use lujie\extend\db\AliasFieldTrait;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%shipping_rule}}".
 *
 * @property int $shipping_rule_id
 * @property string $country
 * @property int $item_id
 * @property int $warehouse_id
 * @property string $carrier
 * @property int $priority
 * @property int $status
 * @property int $owner_id
 */
class ShippingRule extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, AliasFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shipping_rule}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['country', 'carrier'], 'default', 'value' => ''],
            [['item_id', 'warehouse_id', 'priority', 'status', 'owner_id'], 'default', 'value' => 0],
            [['item_id', 'warehouse_id', 'priority', 'status', 'owner_id'], 'integer'],
            [['country'], 'string', 'max' => 2],
            [['carrier'], 'string', 'max' => 10],
            [['owner_id', 'country', 'item_id', 'warehouse_id'], 'unique', 'targetAttribute' => ['owner_id', 'country', 'item_id', 'warehouse_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'shipping_rule_id' => Yii::t('lujie/common', 'Shipping Rule ID'),
            'country' => Yii::t('lujie/common', 'Country'),
            'item_id' => Yii::t('lujie/common', 'Item ID'),
            'warehouse_id' => Yii::t('lujie/common', 'Warehouse ID'),
            'carrier' => Yii::t('lujie/common', 'Carrier'),
            'priority' => Yii::t('lujie/common', 'Priority'),
            'status' => Yii::t('lujie/common', 'Status'),
            'owner_id' => Yii::t('lujie/common', 'Owner ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ShippingRuleQuery the active query used by this AR class.
     */
    public static function find(): ShippingRuleQuery
    {
        return new ShippingRuleQuery(static::class);
    }
}
