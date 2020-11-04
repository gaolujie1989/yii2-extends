<?php

namespace lujie\common\address\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "address_postal_code".
 *
 * @property int $address_postal_code_id
 * @property string $country
 * @property string $postal_code
 * @property string $type
 * @property int $started_at
 * @property int $ended_at
 * @property string $note
 */
class AddressPostalCode extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%address_postal_code}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['country', 'postal_code', 'type', 'note'], 'default', 'value' => ''],
            [['started_at', 'ended_at'], 'default', 'value' => 0],
            [['started_at', 'ended_at'], 'integer'],
            [['country'], 'string', 'max' => 2],
            [['postal_code'], 'string', 'max' => 20],
            [['type'], 'string', 'max' => 50],
            [['note'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'address_postal_code_id' => Yii::t('lujie/common', 'Address Postal Code ID'),
            'country' => Yii::t('lujie/common', 'Country'),
            'postal_code' => Yii::t('lujie/common', 'Postal Code'),
            'type' => Yii::t('lujie/common', 'Type'),
            'started_at' => Yii::t('lujie/common', 'Started At'),
            'ended_at' => Yii::t('lujie/common', 'Ended At'),
            'note' => Yii::t('lujie/common', 'Note'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AddressPostalCodeQuery the active query used by this AR class.
     */
    public static function find(): AddressPostalCodeQuery
    {
        return new AddressPostalCodeQuery(static::class);
    }
}
