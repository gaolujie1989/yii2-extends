<?php

namespace lujie\as2\models;

use Yii;

/**
 * This is the model class for table "{{%as2_partner}}".
 *
 * @property int $id
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
            [['as2_id', 'email', 'target_url', 'content_type', 'content_transfer_encoding', 'subject', 'auth_method', 'auth_user', 'auth_password', 'signature_algorithm', 'encryption_algorithm', 'certificate', 'private_key', 'private_key_pass_phrase', 'compression_type', 'mdn_mode', 'mdn_options', 'mdn_subject'], 'default', 'value' => ''],
            [['status'], 'default', 'value' => 0],
            [['status'], 'integer'],
            [['as2_id', 'email', 'content_type', 'auth_user', 'auth_password', 'certificate', 'private_key', 'private_key_pass_phrase', 'mdn_options'], 'string', 'max' => 50],
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
            'id' => Yii::t('lujie/edi', 'ID'),
            'as2_id' => Yii::t('lujie/edi', 'As 2 ID'),
            'email' => Yii::t('lujie/edi', 'Email'),
            'target_url' => Yii::t('lujie/edi', 'Target Url'),
            'content_type' => Yii::t('lujie/edi', 'Content Type'),
            'content_transfer_encoding' => Yii::t('lujie/edi', 'Content Transfer Encoding'),
            'subject' => Yii::t('lujie/edi', 'Subject'),
            'auth_method' => Yii::t('lujie/edi', 'Auth Method'),
            'auth_user' => Yii::t('lujie/edi', 'Auth User'),
            'auth_password' => Yii::t('lujie/edi', 'Auth Password'),
            'signature_algorithm' => Yii::t('lujie/edi', 'Signature Algorithm'),
            'encryption_algorithm' => Yii::t('lujie/edi', 'Encryption Algorithm'),
            'certificate' => Yii::t('lujie/edi', 'Certificate'),
            'private_key' => Yii::t('lujie/edi', 'Private Key'),
            'private_key_pass_phrase' => Yii::t('lujie/edi', 'Private Key Pass Phrase'),
            'compression_type' => Yii::t('lujie/edi', 'Compression Type'),
            'mdn_mode' => Yii::t('lujie/edi', 'Mdn Mode'),
            'mdn_options' => Yii::t('lujie/edi', 'Mdn Options'),
            'mdn_subject' => Yii::t('lujie/edi', 'Mdn Subject'),
            'status' => Yii::t('lujie/edi', 'Status'),
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
