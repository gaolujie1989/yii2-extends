<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\ar;

use lujie\extend\helpers\ClassHelper;
use Yii;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\helpers\StringHelper;
use yii\web\Application;

/**
 * Class ActiveRecordSnapshotManager
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseActiveRecordManager extends BaseObject implements BootstrapInterface
{
    /**
     * @var array
     */
    public $onlyClasses = [];

    /**
     * @var array
     */
    public $exceptClasses = [];

    /**
     * @var array
     */
    public $only = [];

    /**
     * @var array
     */
    public $except = [];

    /**
     * @var bool
     */
    public $onlyOnUserChanged = true;

    /**
     * @param BaseActiveRecord $model
     * @return bool
     * @inheritdoc
     */
    protected function isActive(BaseActiveRecord $model): bool
    {
        if ($this->onlyOnUserChanged) {
            if (!(Yii::$app instanceof Application) || Yii::$app->getUser()->getIsGuest()) {
                return false;
            }
        }
        $modelClass = ClassHelper::getClassShortName($model);
        if ($this->exceptClasses) {
            foreach ($this->exceptClasses as $exceptClass) {
                if ($model instanceof $exceptClass) {
                    return false;
                }
            }
        }
        if ($this->except) {
            foreach ($this->except as $pattern) {
                if (StringHelper::matchWildcard($pattern, $modelClass)) {
                    return false;
                }
            }
        }
        if ($this->onlyClasses) {
            foreach ($this->onlyClasses as $onlyClass) {
                if ($model instanceof $onlyClass) {
                    return true;
                }
            }
            return false;
        }
        if ($this->only) {
            foreach ($this->only as $pattern) {
                if (StringHelper::matchWildcard($pattern, $modelClass)) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }
}
