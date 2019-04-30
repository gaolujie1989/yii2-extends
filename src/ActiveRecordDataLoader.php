<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


use yii\base\BaseObject;
use yii\db\BaseActiveRecord;

/**
 * Class ActiveRecordLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordDataLoader extends BaseObject implements DataLoaderInterface
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
     * @var array
     */
    public $condition = [];

    /**
     * @var bool
     */
    public $returnAsArray = false;

    /**
     * @param int|string $key
     * @return array|BaseActiveRecord|null
     * @inheritdoc
     */
    public function get($key)
    {
        return $this->modelClass::find()
            ->andFilterWhere($this->condition)
            ->andWhere([$this->uniqueKey => $key])
            ->asArray($this->returnAsArray)
            ->one();
    }

    /**
     * @return array|BaseActiveRecord[]
     * @inheritdoc
     */
    public function all()
    {
        return $this->modelClass::find()
            ->andFilterWhere($this->condition)
            ->asArray($this->returnAsArray)
            ->all();
    }
}
