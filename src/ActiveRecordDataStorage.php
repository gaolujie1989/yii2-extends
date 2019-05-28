<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\storage;

use lujie\data\loader\ActiveRecordDataLoader;
use Yii;
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
     * @return BaseActiveRecord
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getModel($key): BaseActiveRecord
    {
        /** @var BaseActiveRecord $model */
        $model = $this->modelClass::find()
            ->andFilterWhere($this->condition)
            ->andWhere([$this->uniqueKey => $key])
            ->one() ?: Yii::createObject($this->modelClass);
        return $model;
    }

    /**
     * @param $key
     * @param $data
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function set($key, $data): bool
    {
        $model = $this->getModel($key);
        $model->setAttributes($data);
        return $model->save($this->runValidation);
    }

    /**
     * @param $key
     * @return bool|false|int
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function remove($key)
    {
        $model = $this->getModel($key);
        if (!$model->getIsNewRecord()) {
            return $model->delete();
        }
        return 0;
    }
}
