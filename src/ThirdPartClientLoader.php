<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\center;

use lujie\data\loader\ObjectDataLoader;
use yii\base\InvalidConfigException;

/**
 * Class ThirdPartClientLoader
 * @package lujie\data\center
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ThirdPartClientLoader extends ObjectDataLoader
{
    /**
     * [
     *      'xxxType' => ['class' => 'xxxClass', 'xxx' => 'xxx']
     * ]
     * @var array
     */
    public $clients = [];

    /**
     * @var string
     */
    public $clientKey = 'client';

    /**
     * @var string
     */
    public $objectClass = 'xxx';

    /**
     * @param $data
     * @return object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function createObject(array $data): object
    {
        $this->initConfig($data);
        return parent::createObject($data);
    }

    /**
     * @param array $data
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function initConfig(array $data): void
    {
        if (empty($data[$this->clientKey]) || empty($this->clients[$data[$this->clientKey]])) {
            throw new InvalidConfigException('Invalid client config');
        }

        $client = $this->clients[$data[$this->clientKey]];
        if (is_array($client)) {
            $this->objectClass = $client['class'];
            unset($client['class']);
            $this->dataConfig = $client;
        } else {
            $this->objectClass = $client;
        }
    }
}
