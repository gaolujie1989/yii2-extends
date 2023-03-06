<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\BaseActiveRecord;

/**
 * Class ActiveRecordLoader
 *
 * @property-write bool $returnAsArray;
 * @property ActiveQuery $query
 *
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordDataLoader extends QueryDataLoader
{
    /**
     * @var BaseActiveRecord
     */
    public $modelClass;

    /**
     * @var array|string
     */
    public $with;

    /**
     * @var bool
     */
    protected $returnAsArray = false;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        $primaryKey = $this->modelClass::primaryKey();
        if (empty($this->key) && count($primaryKey) === 1) {
            $this->key = reset($primaryKey);
        }
        if (empty($this->query)) {
            $this->query = $this->modelClass::find()->asArray($this->returnAsArray);
        }
        if (empty($this->indexBy) && count($primaryKey) === 1) {
            $this->indexBy = reset($primaryKey);
        }
        if ($this->with && $this->query instanceof ActiveQueryInterface) {
            $this->query->with($this->with);
        }
        $this->db = $this->modelClass::getDb();
        parent::init();
    }

    /**
     * @param bool $returnAsArray
     * @inheritdoc
     */
    public function setReturnAsArray(bool $returnAsArray): void
    {
        $this->returnAsArray = $returnAsArray;
        if ($this->query instanceof ActiveQueryInterface) {
            $this->query->asArray($this->returnAsArray);
        }
    }
}
