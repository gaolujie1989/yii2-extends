<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\batch;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\rest\Action;

/**
 * Class BatchUpdateAction
 * @package lujie\core\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BatchMethodAction extends Action
{
    public $separator = ';';

    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @var string
     */
    public $method;

    /**
     * @param $ids
     * @return BatchMethodForm|mixed
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function run($ids)
    {
        $batchMethodForm = new BatchMethodForm([
            'condition' => $this->getCondition($ids),
            'modelClass' => $this->modelClass,
            'checkAccess' => $this->checkAccess,
            'scenario' => $this->scenario,
        ]);

        $batchMethodForm->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($batchMethodForm->hasMethod($this->method, false)) {
            if ($models = $batchMethodForm->{$this->method}) {
                return $models;
            } else {
                return $batchMethodForm;
            }
        } else {
            throw new InvalidConfigException('Method Not Exists.');
        }
    }

    /**
     * @param $ids
     * @return array|bool
     * @inheritdoc
     */
    protected function getCondition($ids)
    {
        if (is_string($ids) && $ids) {
            $ids = explode($this->separator, $ids);
        }

        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $pkColumns = $modelClass::primaryKey();
        if (count($pkColumns) > 1) {
            $condition = [];
            foreach ($ids as $values) {
                $values = explode(',', $values);
                if (count($pkColumns) === count($values)) {
                    $condition[] = array_combine($pkColumns, $values);
                }
            }
            if ($condition) {
                array_unshift($condition, 'OR');
                return $condition;
            }
        } elseif ($ids !== null) {
            return [$pkColumns[0] => $ids];
        }

        return false;
    }
}
