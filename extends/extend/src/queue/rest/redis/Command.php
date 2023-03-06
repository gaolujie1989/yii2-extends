<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\queue\rest\redis;

/**
 * Class Command
 * @package lujie\extend\queue\rest\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Command extends \lujie\extend\queue\rest\Command
{
    /**
     * @var Queue
     */
    public $queue;
    /**
     * @var string
     */
    public $defaultAction = 'info';

    /**
     * @return array
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'info' => InfoAction::class,
        ]);
    }
}