<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\staging;


use lujie\data\staging\models\DataAccount;
use lujie\data\staging\models\DataSource;
use lujie\data\staging\DataSourceModelStorage;
use lujie\data\staging\pipelines\DataRecordPipeline;
use lujie\data\staging\transformers\RecordTransformer;
use lujie\data\exchange\DataExchange;
use lujie\data\exchange\sources\ClientSource;
use lujie\data\exchange\sources\IncrementSource;
use lujie\data\exchange\sources\SourceInterface;
use lujie\data\loader\DataLoaderInterface;
use lujie\extend\helpers\ObjectHelper;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\httpclient\Client;

/**
 * Class StagingExchangeLoader
 * @package lujie\data\staging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class StagingExchangeLoader extends BaseObject implements DataLoaderInterface
{
    /**
     * @var array
     */
    public $clients = [];

    /**
     * @var array
     */
    public $sources = [];

    public $transformers = [];

    public $pipelines = [];

    /**
     * @param int|string $key
     * @return mixed|null
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function get($key): DataExchange
    {
        $dataSource = DataSource::findOne($key);
        if ($dataSource === null) {
            return null;
        }

        return new DataExchange([
            'source' => $this->createSource($dataSource),
            'transformer' => $this->createTransformer($dataSource),
            'pipeline' => $this->createPipeline($dataSource),
        ]);
    }

    /**
     * @param DataSource $dataSource
     * @return DataRecordPipeline|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function createPipeline(DataSource $dataSource): DataRecordPipeline
    {
        if (empty($this->pipelines[$dataSource->type])) {
            throw new InvalidConfigException('Pipeline not set');
        }
        $pipelineConfig = $this->pipelines[$dataSource->type];
        return ObjectHelper::create(ClientSource::class, $pipelineConfig);
    }

    /**
     * @param DataSource $dataSource
     * @return RecordTransformer|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function createTransformer(DataSource $dataSource): RecordTransformer
    {
        if (empty($this->transformers[$dataSource->type])) {
            throw new InvalidConfigException('Transformer not set');
        }
        $transformerConfig = $this->transformers[$dataSource->type];
        return ObjectHelper::create(ClientSource::class, $transformerConfig);
    }

    /**
     * @param DataSource $dataSource
     * @return SourceInterface|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function createSource(DataSource $dataSource): SourceInterface
    {
        if (empty($this->sources[$dataSource->type])) {
            throw new InvalidConfigException('Invalid source type');
        }
        $sourceConfig = $this->sources[$dataSource->type];
        $source = ObjectHelper::create(ClientSource::class, $sourceConfig, $dataSource);
        return new IncrementSource([
            'source' => $source,
            'sourceKey' => $dataSource->data_source_id,
            'dataStorage' => [
                'class' => DataSourceModelStorage::class
            ]
        ]);
    }

    /**
     * @param DataAccount $dataAccount
     * @return object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function createClient(DataAccount $dataAccount)
    {
        if (empty($this->clients[$dataAccount->type])) {
            throw new InvalidConfigException('Invalid account type');
        }
        $clientConfig = $this->clients[$dataAccount->type];
        return ObjectHelper::create(Client::class, $clientConfig, $dataAccount);
    }

    /**
     * @return array|null
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function all(): ?array
    {
        throw new NotSupportedException('not support');
    }
}
