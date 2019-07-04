<?php

namespace lujie\data\recording;

use lujie\data\recording\models\DataSource;
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
     * @param int|string $key
     * @return array|\yii\db\BaseActiveRecord|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function get($key)
    {
        /** @var DataSource $model */
        $model = $this->getModel($key);
        return $model->additional_info[$this->conditionKey] ?? null;
    }

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
        $model->additional_info = array_merge($model->additional_info ?: [], [$this->conditionKey => $data]);
        return $model->save($this->runValidation, ['additional_info']);
    }

    /**
     * @param $key
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function remove($key): bool
    {
        return $this->set($key, null);
    }
}
