<?php

namespace lujie\auth\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%auth_item}}".
 *
 * @property string $name
 * @property int $type
 * @property string|null $description
 * @property string|null $rule_name
 * @property resource|null $data
 */
class AuthItem extends \lujie\extend\db\ActiveRecord
{
    public const TYPE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        $components = Yii::$app->getComponents();
        return $components['authManager']['itemTable'] ?? '{{%auth_item}}';
    }

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->type = static::TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['description'], 'default', 'value' => ''],
            [['name'], 'required'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true,
                'targetClass' => AuthRule::class,
                'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('lujie/auth', 'Name'),
            'type' => Yii::t('lujie/auth', 'Type'),
            'description' => Yii::t('lujie/auth', 'Description'),
            'rule_name' => Yii::t('lujie/auth', 'Rule Name'),
            'data' => Yii::t('lujie/auth', 'Data'),
        ];
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public static function find(): ActiveQuery
    {
        return parent::find()->andWhere(['type' => static::TYPE]);
    }
}
