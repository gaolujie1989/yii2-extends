<?php

namespace lujie\common\address\models;

use Yii;

/**
 * This is the model class for table "address_postal_code".
 *
 * @property int $address_postal_code_id
 * @property string $type
 * @property string $country
 * @property string $postal_code
 * @property int $status
 * @property string $note
 */
class AddressPostalCode extends \lujie\extend\db\ActiveRecord
{
    public const TYPE_ISLAND = 'ISLAND';

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
            [['type', 'country', 'postal_code', 'note'], 'default', 'value' => ''],
            [['status'], 'default', 'value' => 0],
            [['status'], 'integer'],
            [['type'], 'string', 'max' => 50],
            [['country'], 'string', 'max' => 2],
            [['postal_code'], 'string', 'max' => 20],
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
            'type' => Yii::t('lujie/common', 'Type'),
            'country' => Yii::t('lujie/common', 'Country'),
            'postal_code' => Yii::t('lujie/common', 'Postal Code'),
            'status' => Yii::t('lujie/common', 'Status'),
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
