<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\rules;

use lujie\extend\helpers\ValueHelper;
use Yii;
use yii\db\BaseActiveRecord;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Class ModelRule
 * 数据权限，行数据执行过滤规则，判断模型是否匹配有权限
 * @package lujie\auth\rules
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ModelRule extends Rule
{
    /**
     * @var string
     */
    public $name = 'ModelChecker';

    /**
     * @var BaseActiveRecord
     */
    public $modelClass;

    /**
     * @var string
     */
    public $idParams = 'id';

    /**
     * @var array
     */
    public $condition = [];

    /**
     * @var bool
     */
    public $strict = false;

    /**
     * @param int|string $user
     * @param Item $item
     * @param array $params
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    public function execute($user, $item, $params): bool
    {
        Yii::configure($this, $item->data['rule'] ?? []);
        $request = Yii::$app->getRequest();
        $id = $request->get($this->idParams);
        if (empty($id)) {
            return true;
        }
        $model = $this->modelClass::findOne($id);
        if ($model === null) {
            return true;
        }

        if (ValueHelper::match($model, $this->condition, $this->strict)) {
            return true;
        }
        return false;
    }
}