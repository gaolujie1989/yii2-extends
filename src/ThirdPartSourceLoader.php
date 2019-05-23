<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\center;

use lujie\data\center\models\DataSource;
use lujie\data\exchange\sources\RestApiSource;
use lujie\data\loader\ActiveRecordDataLoader;
use lujie\data\loader\DataLoaderInterface;
use lujie\data\loader\ObjectedDataLoader;
use yii\base\InvalidArgumentException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class ThirdPartSourceLoader
 * @package lujie\data\center
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ThirdPartSourceLoader extends ObjectedDataLoader
{
    public $thirdPartClientConfig = [
        'plentymarkets' => [
            'class' => 'lujie\plentymarkets\PlentymarketsRestClient',
            'username' => 'username',
            'password' => 'password',
        ],
    ];

    public $thirdPartClientKey = 'type';

    /**
     * @var DataLoaderInterface
     */
    public $dataLoader = [
        'class' => ActiveRecordDataLoader::class,
        'modelClass' => DataSource::class,
        'returnAsArray' => false,
    ];

    public $objectClass = RestApiSource::class;

    /**
     * @param array|null $data
     * @return object|null|RestApiSource
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function createObject(?array $data): ?object
    {
        if ($data === null) {
            return null;
        }
        if (empty($this->thirdPartClientConfig[$data[$this->thirdPartClientKey]])) {
            throw new InvalidArgumentException('Invalid source');
        }
        $clientConfig = $this->thirdPartClientConfig[$data[$this->thirdPartClientKey]];
        foreach ($clientConfig as $property => $item) {
            if ($property !== 'class') {
                $clientConfig[$property] = ArrayHelper::getValue($data, $item);
            }
        }

        $sourceConfig['restClient'] = $clientConfig;
        $sourceConfig['resource'] = $data['resource'];
        return Instance::ensure($sourceConfig, RestApiSource::class);
    }
}
