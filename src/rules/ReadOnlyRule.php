<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\rules;

use Yii;
use yii\rbac\Rule;

/**
 * Class ReadOnlyRule
 * @package lujie\auth\rules
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ReadOnlyRule extends Rule
{
    /**
     * @var string
     */
    public $name = 'Readonly';

    /**
     * @param int|string $user
     * @param \yii\rbac\Item $item
     * @param array $params
     * @return bool
     * @inheritdoc
     */
    public function execute($user, $item, $params): bool
    {
        $request = Yii::$app->getRequest();
        return $request->getIsGet() || $request->getIsOptions() || $request->getIsHead();
    }
}
