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
 * @property int $processed_status
 * @property int $processed_at
 * @property array|null $processed_result
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
            [['message_id', 'message_type', 'sender_id', 'receiver_id', 'status', 'status_msg', 'mdn_mode', 'mdn_status', 'mic'], 'default', 'value' => ''],
            [['direction', 'signed', 'encrypted', 'compressed', 'processed_status', 'processed_at'], 'default', 'value' => 0],
            [['processed_result'], 'default', 'value' => []],
            [['direction', 'signed', 'encrypted', 'compressed', 'processed_status', 'processed_at'], 'integer'],
            [['processed_result'], 'safe'],
            [['message_id', 'message_type', 'sender_id', 'receiver_id', 'mdn_mode', 'mdn_status'], 'string', 'max' => 50],
            [['status_msg'], 'string', 'max' => 1000],
            [['mic'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('lujie/as2', 'ID'),
            'message_id' => Yii::t('lujie/as2', 'Message ID'),
            'message_type' => Yii::t('lujie/as2', 'Message Type'),
            'direction' => Yii::t('lujie/as2', 'Direction'),
            'sender_id' => Yii::t('lujie/as2', 'Sender ID'),
            'receiver_id' => Yii::t('lujie/as2', 'Receiver ID'),
            'status' => Yii::t('lujie/as2', 'Status'),
            'status_msg' => Yii::t('lujie/as2', 'Status Msg'),
            'mdn_mode' => Yii::t('lujie/as2', 'Mdn Mode'),
            'mdn_status' => Yii::t('lujie/as2', 'Mdn Status'),
            'mic' => Yii::t('lujie/as2', 'Mic'),
            'signed' => Yii::t('lujie/as2', 'Signed'),
            'encrypted' => Yii::t('lujie/as2', 'Encrypted'),
            'compressed' => Yii::t('lujie/as2', 'Compressed'),
            'processed_status' => Yii::t('lujie/as2', 'Processed Status'),
            'processed_at' => Yii::t('lujie/as2', 'Processed At'),
            'processed_result' => Yii::t('lujie/as2', 'Processed Result'),
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

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
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
