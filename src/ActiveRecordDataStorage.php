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
class ActiveRecordDataStorage extends ActiveRecordDataLoader implements DataStorageInterface
{
    /**
     * @var bool
     */
    public $runValidation = false;

    /**
     * @param $key
     * @return mixed|void
     * @inheritdoc
     */
    public function delete($key)
    {
        // TODO: Implement delete() method.
    }


    /**
     * @param $data
     * @return bool|mixed
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function set($key, $data)
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
