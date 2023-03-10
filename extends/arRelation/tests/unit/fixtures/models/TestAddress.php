<?php

namespace lujie\ar\relation\behaviors\tests\unit\fixtures\models;

/**
 * This is the model class for table "test_address".
 *
 * @property string $test_address_id
 * @property string $street
 */
class TestAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['street'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'test_address_id' => 'Test Address ID',
            'street' => 'Street',
        ];
    }
}
