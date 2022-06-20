<?php

namespace lujie\as2\models;

use Yii;

/**
 * This is the model class for table "{{%as2_message_content}}".
 *
 * @property int $id
 * @property string $message_id
 * @property string|null $headers
 * @property string|null $payload
 * @property string|null $mdn_payload
 */
class As2MessageContent extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%as2_message_content}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['message_id', 'headers', 'payload', 'mdn_payload'], 'default', 'value' => ''],
            [['headers', 'payload', 'mdn_payload'], 'string'],
            [['created_at'], 'integer'],
            [['message_id'], 'string', 'max' => 50],
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
            'headers' => Yii::t('lujie/edi', 'Headers'),
            'payload' => Yii::t('lujie/edi', 'Payload'),
            'mdn_payload' => Yii::t('lujie/edi', 'Mdn Payload'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return As2MessageContentQuery the active query used by this AR class.
     */
    public static function find(): As2MessageContentQuery
    {
        return new As2MessageContentQuery(static::class);
    }
}
