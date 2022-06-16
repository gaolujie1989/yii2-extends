<?php

namespace lujie\as2\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%as2_message}}".
 *
 * @property int $id
 * @property string $message_id
 * @property string $message_type
 * @property int $direction
 * @property string $sender_id
 * @property string $receiver_id
 * @property string $status
 * @property string $status_msg
 * @property string $mdn_mode
 * @property string $mdn_status
 * @property string $mic
 * @property int $signed
 * @property int $encrypted
 * @property int $compressed
 *
 * @property As2MessageContent $content
 * @property As2Partner $sender
 * @property As2Partner $receiver
 */
class As2Message extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%as2_message}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['message_id', 'message_type', 'direction', 'sender_id', 'receiver_id', 'status', 'status_msg', 'mdn_mode', 'mdn_status', 'mic', 'signed', 'encrypted', 'compressed'], 'default', 'value' => 0],
            [['direction', 'signed', 'encrypted', 'compressed'], 'integer'],
            [['message_id', 'message_type', 'sender_id', 'receiver_id', 'status_msg', 'mdn_mode', 'mdn_status', 'mic'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('lujie/edi', 'ID'),
            'message_id' => Yii::t('lujie/edi', 'Message ID'),
            'message_type' => Yii::t('lujie/edi', 'Message Type'),
            'direction' => Yii::t('lujie/edi', 'Direction'),
            'sender_id' => Yii::t('lujie/edi', 'Sender ID'),
            'receiver_id' => Yii::t('lujie/edi', 'Receiver ID'),
            'status' => Yii::t('lujie/edi', 'Status'),
            'status_msg' => Yii::t('lujie/edi', 'Status Msg'),
            'mdn_mode' => Yii::t('lujie/edi', 'Mdn Mode'),
            'mdn_status' => Yii::t('lujie/edi', 'Mdn Status'),
            'mic' => Yii::t('lujie/edi', 'Mic'),
            'signed' => Yii::t('lujie/edi', 'Signed'),
            'encrypted' => Yii::t('lujie/edi', 'Encrypted'),
            'compressed' => Yii::t('lujie/edi', 'Compressed'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return As2MessageQuery the active query used by this AR class.
     */
    public static function find(): As2MessageQuery
    {
        return new As2MessageQuery(static::class);
    }

    public function getContent(): ActiveQuery
    {
        return $this->hasOne(As2MessageContent::class, ['message_id' => 'message_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getSender(): ActiveQuery
    {
        return $this->hasOne(As2Partner::class, ['as2_id' => 'sender_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getReceiver(): ActiveQuery
    {
        return $this->hasOne(As2Partner::class, ['as2_id' => 'receiver_id']);
    }
}
