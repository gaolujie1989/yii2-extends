<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\web;


use Workerman\Connection\ConnectionInterface;
use yii\base\InvalidConfigException;

class Request extends \yii\web\Request
{
    /**
     * @var ConnectionInterface
     */
    public $connection;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (!($this->connection instanceof ConnectionInterface)) {
            throw new InvalidConfigException('The property `connection` must be set');
        }
        $this->setRawBody($GLOBALS['HTTP_RAW_POST_DATA']);
    }
}
