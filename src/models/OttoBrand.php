<?php

namespace lujie\sales\channel\models;

use Yii;

/**
 * This is the model class for table "{{%otto_brand}}".
 *
 * @property int $otto_brand_id
 * @property string $key
 * @property string $name
 * @property string $logo
 * @property int $usable
 */
class OttoBrand extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%otto_brand}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['key', 'name', 'logo'], 'default', 'value' => ''],
            [['usable'], 'default', 'value' => 0],
            [['usable'], 'integer'],
            [['key'], 'string', 'max' => 20],
            [['name', 'logo'], 'string', 'max' => 200],
            [['key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'otto_brand_id' => Yii::t('lujie/salesChannel', 'Otto Brand ID'),
            'key' => Yii::t('lujie/salesChannel', 'Key'),
            'name' => Yii::t('lujie/salesChannel', 'Name'),
            'logo' => Yii::t('lujie/salesChannel', 'Logo'),
            'usable' => Yii::t('lujie/salesChannel', 'Usable'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return OttoBrandQuery the active query used by this AR class.
     */
    public static function find(): OttoBrandQuery
    {
        return new OttoBrandQuery(static::class);
    }
}
