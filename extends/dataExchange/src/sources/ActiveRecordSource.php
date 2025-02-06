<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use yii\base\InvalidConfigException;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;

/**
 * Class ActiveRecordSource
 * @package lujie\data\exchange\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordSource extends QuerySource
{
    /**
     * @var ActiveRecord
     */
    public $modelClass;

    /**
     * @var null|bool
     */
    public $asArray = null;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        if (empty($this->query)) {
            $this->query = $this->modelClass::find();
        }
        if ($this->query instanceof ActiveQueryInterface && $this->asArray !== null) {
            $this->query->asArray($this->asArray);
        }
        parent::init();
    }
}
