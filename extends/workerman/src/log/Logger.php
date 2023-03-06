<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\log;

/**
 * Class Logger
 * @package lujie\workerman\log
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Logger extends \yii\log\Logger
{
    /**
     * @inheritdoc
     */
    public function init(): void
    {
        //not register shutdown function
    }
}
