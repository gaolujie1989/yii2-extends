<?php

namespace lujie\currency\exchanging\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%currency_exchange_rate}}".
 *
 * @property int $id
 * @property string $from
 * @property string $to
 * @property float $rate
 * @property string $date
 */
class CurrencyExchangeRate extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%currency_exchange_rate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['rate'], 'default', 'value' => 1],
            [['rate'], 'number'],
            [['from', 'to', 'date'], 'required'],
            [['date'], 'safe'],
            [['from', 'to'], 'string', 'max' => 3],
            [['from', 'to', 'rate'], 'unique', 'targetAttribute' => ['from', 'to', 'rate']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('lujie/currency', 'ID'),
            'from' => Yii::t('lujie/currency', 'From'),
            'to' => Yii::t('lujie/currency', 'To'),
            'rate' => Yii::t('lujie/currency', 'Rate'),
            'date' => Yii::t('lujie/currency', 'Date'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CurrencyExchangeRateQuery the active query used by this AR class.
     */
    public static function find(): CurrencyExchangeRateQuery
    {
        return new CurrencyExchangeRateQuery(static::class);
    }
}
