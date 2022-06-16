<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2\actions;

use lujie\as2\As2Manager;
use yii\base\Action;
use yii\di\Instance;
use yii\web\Response;

/**
 * Class As2Action
 * @package lujie\as2\actions
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class As2Action extends Action
{
    /**
     * @var As2Manager
     */
    public $as2Manager = 'as2Manager';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->as2Manager = Instance::ensure($this->as2Manager, As2Manager::class);
    }

    /**
     * @return Response
     * @inheritdoc
     */
    public function run(): Response
    {
        return $this->as2Manager->handleRequest();
    }
}