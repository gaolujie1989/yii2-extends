<?php

namespace lujie\common\address\models;

use lujie\alias\behaviors\AliasPropertyBehavior;
use lujie\extend\helpers\ValueHelper;
use lujie\extend\models\AddressInterface;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%address}}".
 *
 * @property int $address_id
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
 * @property string $options
 * @property string $signature
 *
 * @property string $province
 * @property string $town
 * @property string $zip_code
 * @property string $company_name
 * @property string $first_name
 * @property string $last_name
 * @property string $street
 * @property string $street_no
 * @property string $house_no
 * @property string $additional
 */
class Address extends \lujie\extend\db\ActiveRecord implements AddressInterface
{
    /**
     * if identify by signature,
     * no update for exists address, create address instead
     * if signature is same, not create address, return exist address instead
     * @var bool
     */
    public $identifyBySignature = false;

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
            [['options'], 'safe'],
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), $this->aliasBehaviors());
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function aliasBehaviors(): array
    {
        return array_merge(parent::aliasBehaviors(), [
            'alias' => [
                'class' => AliasPropertyBehavior::class,
                'aliasProperties' => [
                    'province' => 'state',
                    'town' => 'city',
                    'zip_code' => 'postal_code',
                    'company_name' => 'name1',
                    'first_name' => 'name2',
                    'last_name' => 'name3',
                    'street' => 'address1',
                    'street_no' => 'address2',
                    'house_no' => 'address2',
                    'additional' => 'address3',
                ]
            ]
        ]);
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
            } elseif (trim($this->name1)) {
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
            'options' => Yii::t('lujie/common', 'Options'),
            'signature' => Yii::t('lujie/common', 'Signature'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AddressQuery the active query used by this AR class.
     */
    public static function find(): AddressQuery
    {
        return new AddressQuery(static::class);
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function generateSignature(): string
    {
        $except = ['address_id', 'signature', 'created_at', 'created_by', 'updated_at', 'updated_by'];
        $addressData = $this->getAttributes(null, $except);
        $addressData = array_filter($addressData, [ValueHelper::class, 'notEmpty']);
        return md5(Json::encode($addressData));
    }

    /**
     * @param bool $runValidation
     * @param array $attributeNames
     * @return bool
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null): bool
    {
        if ($this->identifyBySignature) {
            $signature = $this->generateSignature();
            $address = static::findBySignature($signature);
            if ($address === null) {
                $this->setIsNewRecord(true);
                $this->address_id = null;
                return parent::save($runValidation, $attributeNames);
            }
            $this->refreshInternal($address);
            return true;
        }
        return parent::save($runValidation, $attributeNames);
    }

    /**
     * @return bool|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function delete(): bool|int
    {
        if ($this->identifyBySignature) {
            return true;
        }
        return parent::delete();
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

    #region AddressInterface implementation

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPostalCode(): string
    {
        return $this->postal_code;
    }

    public function getCompanyName(): string
    {
        return $this->name1;
    }

    public function getFirstName(): string
    {
        return $this->name2;
    }

    public function getLastName(): string
    {
        return $this->name3;
    }

    public function getStreet(): string
    {
        return $this->address1;
    }

    public function getStreetNo(): string
    {
        return $this->address2;
    }

    public function getAdditional(): string
    {
        return $this->address3;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    #endregion
}
