<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\center;

use lujie\data\exchange\sources\RestSource;
use lujie\data\loader\ObjectDataLoader;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class ThirdPartSourceLoader
 * @package lujie\data\center
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ThirdPartSourceLoader extends ObjectDataLoader
{
    /**
     * @var ThirdPartClientLoader
     */
    public $clientLoader = [
        'class' => ThirdPartClientLoader::class
    ];

    /**
     * [
     *      'xxxType' => ['class' => 'xxxClass', 'xxx' => 'xxx']
     * ]
     * @var array
     */
    public $sources = [];

    /**
     * @var string
     */
    public $clientKey = 'client';

    /**
     * @var string
     */
    public $sourceKey = 'source';

    /**
     * @var string
     */
    public $objectClass = RestSource::class;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        $this->clientLoader = Instance::ensure($this->clientLoader, ThirdPartClientLoader::class);
        parent::init();
    }

    /**
     * @param $data
     * @return object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function createObject(array $data): object
    {
        if (empty($data[$this->clientKey])) {
            throw new InvalidConfigException('Invalid source config');
        }
        $data['client'] = $this->clientLoader->get($data[$this->clientKey]);
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
        if (empty($data[$this->sourceKey]) || empty($this->sources[$data[$this->sourceKey]])) {
            throw new InvalidConfigException('Invalid source config');
        }

        $source = $this->sources[$data[$this->sourceKey]];
        if (is_array($source)) {
            $this->objectClass = $source['class'];
            unset($source['class']);
            $this->dataConfig = $source;
        } else {
            $this->objectClass = $source;
        }
    }
}
