<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use yii\db\BaseActiveRecord;

/**
 * Class ActiveRecordLoader
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
     * @var bool
     */
    public $returnAsArray = false;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        if (empty($this->uniqueKey)) {
            $primaryKey = $this->modelClass::primaryKey();
            $this->uniqueKey = reset($primaryKey);
        }
        if (empty($this->query)) {
            $this->query = $this->modelClass::find()->asArray($this->returnAsArray);
        }
        $this->db = $this->modelClass::getDb();
        parent::init();
    }
}
