<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\configuration\dataLoaders;


use yii\base\BaseObject;
use yii\db\BaseActiveRecord;

/**
 * Class ActiveRecordLoader
 * @package lujie\configuration\dataLoaders
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
     * @param int|string $key
     * @return array|BaseActiveRecord|null
     * @inheritdoc
     */
    public function loadByKey($key)
    {
        return $this->modelClass::find()->andFilterWhere($this->condition)
            ->andWhere([$this->uniqueKey => $key])
            ->one();
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function loadAll()
    {
        return $this->modelClass::find()->andFilterWhere($this->condition)
            ->all();
    }
}
