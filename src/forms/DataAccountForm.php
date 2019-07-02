<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\staging\forms;

use lujie\ar\relation\behaviors\RelationDeletableBehavior;
use lujie\data\staging\DataSourceCreator;
use lujie\data\staging\models\DataAccount;

/**
 * Class DataAccountForm
 * @package lujie\data\staging\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DataAccountForm extends DataAccount
{
    /**
     * @var DataSourceCreator[]
     */
    public $dataSourceCreators;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['options'], 'safe'],
            [['status'], 'integer'],
            [['type'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['url', 'username', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'relationDelete' => [
                'class' => RelationDeletableBehavior::class,
                'relations' => 'dataSources'
            ]
        ]);
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function beforeValidate(): bool
    {
        $this->generateName();
        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    protected function generateName(): void
    {
        $this->name = $this->type . '_' . $this->username;
    }
}
