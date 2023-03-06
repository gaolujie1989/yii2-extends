<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use Yii;
use yii\web\Application;

/**
 * Class IdentityHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class IdentityHelper
{
    /**
     * @return int
     */
    public static function getId(): int
    {
        if (!(Yii::$app instanceof Application)) {
            return 0;
        }
        if (!Yii::$app->has('user')) {
            return 0;
        }
        $user = Yii::$app->getUser();
        if ($user->getIsGuest()) {
            return 0;
        }
        return $user->getId();
    }
}
