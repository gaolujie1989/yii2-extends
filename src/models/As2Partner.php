<?php

namespace lujie\as2\models;

use Yii;

/**
 * This is the model class for table "{{%as2_partner}}".
 *
 * @property int $id
 * @property string $partner_name
 * @property string $partner_type
 * @property string $as2_id
 * @property string $email
 * @property string $target_url
 * @property string $content_type
 * @property string $content_transfer_encoding
 * @property string $subject
 * @property string $auth_method
 * @property string $auth_user
 * @property string $auth_password
 * @property string $signature_algorithm
 * @property string $encryption_algorithm
 * @property string $certificate
 * @property string $private_key
 * @property string $private_key_pass_phrase
 * @property string $compression_type
 * @property string $mdn_mode
 * @property string $mdn_options
 * @property string $mdn_subject
 * @property int $status
 */
class As2Partner extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%as2_partner}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['partner_name', 'partner_type', 'as2_id', 'email', 'target_url', 'content_type', 'content_transfer_encoding', 'subject', 'auth_method', 'auth_user', 'auth_password', 'signature_algorithm', 'encryption_algorithm', 'certificate', 'private_key', 'private_key_pass_phrase', 'compression_type', 'mdn_mode', 'mdn_options', 'mdn_subject'], 'default', 'value' => ''],
            [['status'], 'default', 'value' => 0],
            [['certificate', 'private_key'], 'string'],
            [['status'], 'integer'],
            [['partner_name', 'partner_type', 'as2_id', 'email', 'content_type', 'auth_user', 'auth_password', 'private_key_pass_phrase', 'mdn_options'], 'string', 'max' => 50],
            [['target_url', 'subject', 'mdn_subject'], 'string', 'max' => 200],
            [['content_transfer_encoding', 'signature_algorithm', 'encryption_algorithm', 'compression_type', 'mdn_mode'], 'string', 'max' => 10],
            [['auth_method'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('lujie/as2', 'ID'),
            'partner_name' => Yii::t('lujie/as2', 'Partner Name'),
            'partner_type' => Yii::t('lujie/as2', 'Partner Type'),
            'as2_id' => Yii::t('lujie/as2', 'As 2 ID'),
            'email' => Yii::t('lujie/as2', 'Email'),
            'target_url' => Yii::t('lujie/as2', 'Target Url'),
            'content_type' => Yii::t('lujie/as2', 'Content Type'),
            'content_transfer_encoding' => Yii::t('lujie/as2', 'Content Transfer Encoding'),
            'subject' => Yii::t('lujie/as2', 'Subject'),
            'auth_method' => Yii::t('lujie/as2', 'Auth Method'),
            'auth_user' => Yii::t('lujie/as2', 'Auth User'),
            'auth_password' => Yii::t('lujie/as2', 'Auth Password'),
            'signature_algorithm' => Yii::t('lujie/as2', 'Signature Algorithm'),
            'encryption_algorithm' => Yii::t('lujie/as2', 'Encryption Algorithm'),
            'certificate' => Yii::t('lujie/as2', 'Certificate'),
            'private_key' => Yii::t('lujie/as2', 'Private Key'),
            'private_key_pass_phrase' => Yii::t('lujie/as2', 'Private Key Pass Phrase'),
            'compression_type' => Yii::t('lujie/as2', 'Compression Type'),
            'mdn_mode' => Yii::t('lujie/as2', 'Mdn Mode'),
            'mdn_options' => Yii::t('lujie/as2', 'Mdn Options'),
            'mdn_subject' => Yii::t('lujie/as2', 'Mdn Subject'),
            'status' => Yii::t('lujie/as2', 'Status'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return As2PartnerQuery the active query used by this AR class.
     */
    public static function find(): As2PartnerQuery
    {
        return new As2PartnerQuery(static::class);
    }
}
