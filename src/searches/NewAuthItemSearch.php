<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\searches;

use lujie\auth\models\NewAuthItem;
use lujie\extend\db\SearchTrait;
use yii\db\ActiveQueryInterface;

/**
 * @copyright Copyright (c) 2019
 */
class NewAuthItemSearch extends NewAuthItem
{
    use SearchTrait;

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $columns = array_diff($this->attributes(), ['data']);
        return $this->searchQuery()->select($columns);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        $fields = parent::fields();
        unset($fields['data']);
        return $fields;
    }
}