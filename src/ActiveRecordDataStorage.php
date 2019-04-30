<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use Yii;

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
     * @return bool|false|int|mixed
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function delete($key)
    {
        $returnAsArray = $this->returnAsArray;
        $this->returnAsArray = false;

        if ($model = $this->get($key)) {
            $this->returnAsArray = $returnAsArray;
            return $model->delete();
        }
        $this->returnAsArray = $returnAsArray;
        return false;
    }


    /**
     * @param $data
     * @return bool|mixed
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function set($key, $data)
    {
        $returnAsArray = $this->returnAsArray;
        $this->returnAsArray = false;

        $model = $key ? $this->get($key) : null;
        $this->returnAsArray = $returnAsArray;
        if (empty($model)) {
            $model = Yii::createObject($this->modelClass);
        }
        $model->setAttributes($data);
        return $model->save($this->runValidation);
    }
}
