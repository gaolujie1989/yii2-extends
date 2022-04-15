<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\auth\rules;

use yii\helpers\ArrayHelper;
use yii\rbac\Rule;
use Yii;

/**
 * Class AuthorRule
 * @package lujie\auth\rules
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AuthorRule extends Rule
{
    /**
     * @var string
     */
    public $name = 'Author';

    /**
     * @var string
     */
    public $creatorKey = 'created_by';

    /**
     * @param int|string $user
     * @param \yii\rbac\Item $item
     * @param array $params
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    public function execute($user, $item, $params): bool
    {
        Yii::configure($this, $item->data['rule'] ?? []);
        $createdBy = ArrayHelper::getValue($params, $this->creatorKey);
        return $createdBy && $createdBy === $user;
    }
}
