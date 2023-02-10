<?php

namespace lujie\sales\channel\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%otto_category}}".
 *
 * @property int $otto_category_id
 * @property string $category_group
 * @property string $name
 *
 * @property OttoCategoryGroup $group
 */
class OttoCategory extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%otto_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['category_group', 'name'], 'default', 'value' => ''],
            [['created_at'], 'default', 'value' => 0],
            [['created_at'], 'integer'],
            [['category_group', 'name'], 'string', 'max' => 1000],
            [['category_group', 'name'], 'unique', 'targetAttribute' => ['category_group', 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'otto_category_id' => Yii::t('lujie/salesChannel', 'Otto Category ID'),
            'category_group' => Yii::t('lujie/salesChannel', 'Category Group'),
            'name' => Yii::t('lujie/salesChannel', 'Name'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return OttoCategoryQuery the active query used by this AR class.
     */
    public static function find(): OttoCategoryQuery
    {
        return new OttoCategoryQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'group' => 'group'
        ]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getGroup(): ActiveQuery
    {
        return $this->hasOne(OttoCategoryGroup::class, ['category_group' => 'category_group']);
    }
}
