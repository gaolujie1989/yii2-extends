<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\common\option\providers;

use yii\db\BaseActiveRecord;

/**
 * Class ModelOptionProvider
 * @package lujie\common\option\providers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelOptionProvider extends QueryOptionProvider
{
    /**
     * @var string|BaseActiveRecord
     */
    public $modelClass;

    /**
     * @var array
     */
    public $typeConditions = [];

    /**
     * @var array
     */
    public $typeFilterKeys = [];

    /**
     * @param string $type
     * @return bool
     * @inheritdoc
     */
    public function hasType(string $type): bool
    {
        return isset($this->typeConditions[$type]);
    }

    /**
     * @param string $type
     * @param string $key
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    public function getOptions(string $type, string $key = ''): array
    {
        $this->query = $this->modelClass::find();
        $this->condition = $this->typeConditions[$type];
        $this->filterKeys = $this->typeFilterKeys[$type];
        return parent::getOptions($type, $key);
    }
}