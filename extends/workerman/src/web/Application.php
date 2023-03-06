<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\web;

use yii\base\ExitException;

/**
 * Class Application
 * @package lujie\workerman\web
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Application extends \yii\web\Application
{
    /**
     * @param int $status
     * @param null $response
     * @throws ExitException
     * @inheritdoc
     */
    public function end($status = 0, $response = null): void
    {
        if ($this->state === self::STATE_BEFORE_REQUEST || $this->state === self::STATE_HANDLING_REQUEST) {
            $this->state = self::STATE_AFTER_REQUEST;
            $this->trigger(self::EVENT_AFTER_REQUEST);
        }

        if ($this->state !== self::STATE_SENDING_RESPONSE && $this->state !== self::STATE_END) {
            $this->state = self::STATE_END;
            $response = $response ?: $this->getResponse();
            $response->send();
        }

        if (YII_ENV_TEST) {
            throw new ExitException($status);
        }
    }
}