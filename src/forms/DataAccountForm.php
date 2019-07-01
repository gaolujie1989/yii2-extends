<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\staging\forms;

use lujie\data\staging\DataSourceCreator;
use lujie\data\staging\models\DataAccount;
use yii\base\InvalidConfigException;
use yii\di\Instance;

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
            [['url', 'username', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        $this->generateName();
        return parent::beforeSave($insert);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        $this->createDataSources();
    }

    /**
     * @inheritdoc
     */
    public function generateName(): void
    {
        $this->name = $this->type . $this->username;
    }

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function createDataSources(): void
    {
        if (empty($this->dataSourceCreators[$this->type])) {
            throw new InvalidConfigException('Source creator must be set');
        }
        /** @var DataSourceCreator $creator */
        $creator = Instance::ensure($this->dataSourceCreators[$this->type], DataSourceCreator::class);
        $creator->createSources($this);
    }
}
