<?php

namespace lujie\sales\order\center\models;

use lujie\extend\db\TraceableBehaviorTrait;
use Yii;

/**
 * This is the model class for table "{{%address}}".
 *
 * @property string $address_id
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $name1 company name
 * @property string $name2 first name
 * @property string $name3 last name
 * @property string $address1 street|pack station|post filiale
 * @property string $address2 house no|pack station id
 * @property string $address3 additional
 * @property string $zip_code
 * @property string $email
 * @property string $phone
 * @property string $signature
 */
class Address extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%address}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['country'], 'required'],
            [['country'], 'string', 'max' => 2],
            [['state', 'city'], 'string', 'max' => 200],
            [['name1', 'name2', 'name3', 'address1', 'address2', 'address3'], 'string', 'max' => 255],
            [['zip_code'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'address_id' => Yii::t('sales/order', 'Address ID'),
            'country' => Yii::t('sales/order', 'Country'),
            'state' => Yii::t('sales/order', 'State'),
            'city' => Yii::t('sales/order', 'City'),
            'name1' => Yii::t('sales/order', 'Name1'),
            'name2' => Yii::t('sales/order', 'Name2'),
            'name3' => Yii::t('sales/order', 'Name3'),
            'address1' => Yii::t('sales/order', 'Address1'),
            'address2' => Yii::t('sales/order', 'Address2'),
            'address3' => Yii::t('sales/order', 'Address3'),
            'zip_code' => Yii::t('sales/order', 'Zip Code'),
            'email' => Yii::t('sales/order', 'Email'),
            'phone' => Yii::t('sales/order', 'Phone'),
            'signature' => Yii::t('sales/order', 'Signature'),
        ];
    }

    /**
     * @return string
     * @inheritdoc
     */
    protected function generateSignature(): string
    {
        $addressData = $this->getAttributes(null, ['address_id', 'created_at', 'created_by', 'updated_at', 'updated_by']);
        return md5(json_encode($addressData));
    }

    /**
     * @param bool $insert
     * @return bool
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        $this->signature = $this->generateSignature();
        return parent::beforeSave($insert);
    }

    /**
     * @param $signature
     * @return Address|null
     * @inheritdoc
     */
    public static function findBySignature(string $signature): ?self
    {
        static::findOne(['signature' => $signature]);
    }
}
