<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\monitor\searches;


use lujie\extend\base\SearchTrait;
use lujie\extend\helpers\ModelHelper;
use lujie\scheduling\monitor\models\ScheduleTask;
use yii\db\ActiveQueryInterface;

/**
 * Class ScheduleTaskSearch
 * @package lujie\scheduling\monitor\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ScheduleTaskSearch extends ScheduleTask
{
    use SearchTrait;

    /**
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public function query(): ActiveQueryInterface
    {
        $query = ModelHelper::query($this);
        return $query->addOrderBy(['position' => SORT_ASC]);
    }
}
