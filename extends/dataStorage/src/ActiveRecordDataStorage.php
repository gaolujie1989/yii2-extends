<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\storage;

use lujie\data\loader\ActiveRecordDataLoader;
use Yii;
use yii\db\ActiveRecordInterface;
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
     * @return ActiveRecordInterface|null
     * @inheritdoc
     */
    protected function getModel($key): ?ActiveRecordInterface
    {
        return $this->modelClass::find()
            ->andFilterWhere($this->condition)
            ->andWhere([$this->key => $key])
            ->one();
    }

    /**
     * @param int|string $key
     * @param mixed $data
     * @return bool
     * @inheritdoc
     */
    public function set($key, $data): bool
    {
        $model = $this->getModel($key) ?: new $this->modelClass();
        $model->setAttributes($data);
        return $model->save($this->runValidation);
    }

    /**
     * @param int|string $key
     * @return bool|int|mixed
     * @inheritdoc
     */
    public function remove($key)
    {
        $model = $this->getModel($key);
        if ($model === null) {
            return true;
        }
        return $model->delete();
    }
}
