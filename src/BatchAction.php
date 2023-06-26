<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\batch;

use lujie\extend\helpers\ModelHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class BatchUpdateAction
 * @package lujie\core\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BatchAction extends Action
{
    /**
     * @var string|BatchForm
     */
    public $batchFormClass;

    /**
     * @var string
     */
    public $separator = ';';

    /**
     * @var string
     */
    public $method = 'batchUpdate';

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->batchFormClass === null) {
            throw new InvalidConfigException(get_class($this) . '::$batchFormClass must be set.');
        }
    }

    /**
     * @param array|string|int $ids
     * @return BatchForm
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     * @inheritdoc
     */
    public function run($ids): BatchForm
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var BatchForm $batchForm */
        $batchForm = new $this->batchFormClass([
            'modelClass' => $this->modelClass,
            'batchCondition' => ModelHelper::getCondition($ids, $this->modelClass, $this->separator),
        ]);

        $batchForm->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($batchForm->hasMethod($this->method, false)) {
            if ($batchForm->{$this->method}() === false && !$batchForm->hasErrors()) {
                throw new ServerErrorHttpException('Failed to update object for unknown reason.');
            }
            return $batchForm;
        }
        throw new InvalidConfigException('Method Not Exists.');
    }
}
