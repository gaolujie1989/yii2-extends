<?php

namespace lujie\common\address\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\helpers\Json;

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
 * @property string $address2 street no|house no|pack station id
 * @property string $address3 additional
 * @property string $postal_code
 * @property string $email
 * @property string $phone
 * @property string $signature
 */
class Address extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

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
            [['name2'], 'validateName'],
            [['country', 'city', 'postal_code', 'name2', 'address1'], 'required'],
            [['state', 'city', 'name1', 'name2', 'name3', 'address1', 'address2', 'address3',
                'postal_code', 'email', 'phone'], 'default', 'value' => ''],
            [['country'], 'string', 'max' => 2],
            [['state', 'city'], 'string', 'max' => 200],
            [['name1', 'name2', 'name3', 'address1', 'address2', 'address3'], 'string', 'max' => 255],
            [['postal_code', 'phone'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['province', 'town', 'zip_code', 'company_name', 'first_name', 'last_name',
                'street', 'street_no', 'house_no', 'additional'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function validateName(): void
    {
        if (empty(trim($this->name2))) {
            if (trim($this->name3)) {
                $this->name2 = $this->name3;
                $this->name3 = '';
            } else if (trim($this->name1)) {
                $this->name2 = $this->name1;
                $this->name1 = '';
            }
        }
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
            'name1' => Yii::t('lujie/common', 'Company Name'),
            'name2' => Yii::t('lujie/common', 'First Name'),
            'name3' => Yii::t('lujie/common', 'Last Name'),
            'address1' => Yii::t('lujie/common', 'Street'),
            'address2' => Yii::t('lujie/common', 'House No'),
            'address3' => Yii::t('lujie/common', 'Additional'),
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
        return md5(Json::encode($addressData));
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
     * @param string $signature
     * @return static|null
     * @inheritdoc
     */
    public static function findBySignature(string $signature): ?self
    {
        return static::findOne(['signature' => $signature]);
    }
}
