<?php

namespace lujie\common\address\models;

use function GuzzleHttp\Psr7\str;
use lujie\alias\behaviors\AliasPropertyBehavior;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
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
 * @property string $postal_code
 * @property string $email
 * @property string $phone
 * @property string $signature
 *
 * @property string $zip_code
 * @property string $company_name
 * @property string $first_name
 * @property string $last_name
 * @property string $street
 * @property string $house_no
 * @property string $additional
 *
 */
class Address extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait;

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
            [['country', 'city', 'name2', 'address1'], 'required'],
            [['state', 'city', 'name1', 'name2', 'name3', 'address1', 'address2', 'address3',
                'postal_code', 'email', 'phone'], 'default', 'value' => ''],
            [['country'], 'string', 'max' => 2],
            [['state', 'city'], 'string', 'max' => 200],
            [['address1', 'address2', 'address3', 'postal_code', 'phone'], 'strVal'],
            [['name1', 'name2', 'name3', 'address1', 'address2', 'address3'], 'string', 'max' => 255],
            [['postal_code'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 50],
            [['email'], 'email'],
            [['company_name', 'first_name', 'last_name', 'street', 'house_no', 'additional'], 'safe'],
        ];
    }

    /**
     * @param string $attribute
     * @inheritdoc
     */
    public function strVal(string $attribute): void
    {
        $this->{$attribute} = (string)$this->{$attribute};
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'alias' => [
                'class' => AliasPropertyBehavior::class,
                'aliasProperties' => [
                    'province' => 'state',
                    'zip_code' => 'postal_code',
                    'company_name' => 'name1',
                    'first_name' => 'name2',
                    'last_name' => 'name3',
                    'street' => 'address1',
                    'house_no' => 'address2',
                    'additional' => 'address3',
                ]
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'address_id' => Yii::t('lujie/common', 'Address ID'),
            'country' => Yii::t('lujie/common', 'Country'),
            'state' => Yii::t('lujie/common', 'State'),
            'city' => Yii::t('lujie/common', 'City'),
            'name1' => Yii::t('lujie/common', 'Name1'),
            'name2' => Yii::t('lujie/common', 'Name2'),
            'name3' => Yii::t('lujie/common', 'Name3'),
            'address1' => Yii::t('lujie/common', 'Address1'),
            'address2' => Yii::t('lujie/common', 'Address2'),
            'address3' => Yii::t('lujie/common', 'Address3'),
            'postal_code' => Yii::t('lujie/common', 'Postal Code'),
            'email' => Yii::t('lujie/common', 'Email'),
            'phone' => Yii::t('lujie/common', 'Phone'),
            'signature' => Yii::t('lujie/common', 'Signature'),
        ];
    }

    /**
     * @return string
     * @inheritdoc
     */
    protected function generateSignature(): string
    {
        $addressData = $this->getAttributes(null, ['address_id', 'signature', 'created_at', 'created_by', 'updated_at', 'updated_by']);
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
        return static::findOne(['signature' => $signature]);
    }
}
