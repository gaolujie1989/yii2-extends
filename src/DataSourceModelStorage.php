<?php

namespace lujie\data\center\models;

use lujie\data\storage\ActiveRecordDataStorage;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table "{{%data_source}}".
 *
 * @property int $data_source_id
 * @property int $data_account_id
 * @property string $name
 * @property array $options
 * @property array $additional_info
 * @property int $status
 */
class DataSourceModelStorage extends ActiveRecordDataStorage
{
    /**
     * @var DataSource
     */
    public $modelClass = DataSource::class;

    /**
     * @var string
     */
    public $conditionKey = 'incrementCondition';

    /**
     * @param $key
     * @param $data
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function set($key, $data): bool
    {
        /** @var DataSource $model */
        $model = $this->getModel($key);
        $model->additional_info[$this->conditionKey] = $data;
        return $model->save($this->runValidation, ['additional_info']);
    }

    /**
     * @param $key
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function delete($key): bool
    {
        /** @var DataSource $model */
        $model = $this->getModel($key);
        unset($model->additional_info[$this->conditionKey]);
        return $model->save($this->runValidation, ['additional_info']);
    }
}
