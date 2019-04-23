<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use Yii;
use yii\base\BaseObject;
use yii\db\BaseActiveRecord;

/**
 * Class ActiveRecordDataStorage
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordDataStorage extends BaseObject implements DataStorageInterface
{
    /**
     * @var BaseActiveRecord
     */
    public $modelClass;

    /**
     * @var string|int
     */
    public $uniqueKey;

    /**
     * @var bool
     */
    public $runValidation = false;

    /**
     * @var array
     */
    public $condition = [];

    /**
     * @param $data
     * @return bool|mixed
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function save($data)
    {
        if ($this->uniqueKey && isset($data[$this->uniqueKey])) {
            /** @var BaseActiveRecord $model */
            $model = $this->modelClass::find()
                ->andFilterWhere($this->condition)
                ->andWhere([$this->uniqueKey => $data[$this->uniqueKey]])
                ->one();
        } else {
            $primaryKey = reset($this->modelClass::primaryKey());
            if (isset($data[$primaryKey])) {
                /** @var BaseActiveRecord $model */
                $model = $this->modelClass::find()
                    ->andFilterWhere($this->condition)
                    ->andWhere([$primaryKey => $data[$primaryKey]])
                    ->one();
            }
        }
        if (empty($model)) {
            $model = Yii::createObject($this->modelClass);
        }
        $model->setAttributes($data);
        return $model->save($this->runValidation);
    }
}
