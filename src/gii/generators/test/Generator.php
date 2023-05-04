<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\gii\generators\test;

use yii\gii\CodeFile;

/**
 * Class Generator
 * @package lujie\extend\gii\generators\test
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Generator extends \yii\gii\Generator
{
    public function getName(): string
    {
        return 'Test CRUD Generator';
    }

    public function getDescription(): string
    {
        return 'This generator generates automated CRUD unit/functional/api test for the specified table.';
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['modelClass'], 'required']
        ]);
    }


    /**
     * @return array|string[]
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'modelClass' => 'Model Class',
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function hints(): array
    {
        return array_merge(parent::hints(), [
            'modelClass' => 'This is the ActiveRecord class associated with the table that CRUD will be built upon.
                You should provide a fully qualified class name, e.g., <code>app\models\Post</code>.',
        ]);
    }

    public function generate()
    {

    }
}
