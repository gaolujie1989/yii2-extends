<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\staging;


use lujie\data\exchange\DataExchange;
use lujie\data\exchange\sources\ClientSource;
use lujie\data\exchange\sources\IncrementSource;
use lujie\data\exchange\sources\SourceInterface;
use lujie\data\loader\DataLoaderInterface;
use lujie\data\staging\models\DataAccount;
use lujie\data\staging\models\DataSource;
use lujie\data\staging\pipelines\DataRecordPipeline;
use lujie\data\staging\transformers\RecordTransformer;
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
    public $clientSources = [];

    /**
     * @var array
     */
    public $incrementSources = [];

    /**
     * @var array
     */
    public $transformers = [];

    /**
     * @var array
     */
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
        $pipelineConfig = $this->pipelines[$dataSource->type] ?? [];
        $pipelineConfig['sourceId'] = $dataSource->data_source_id;
        return ObjectHelper::create($pipelineConfig, DataRecordPipeline::class);
    }

    /**
     * @param DataSource $dataSource
     * @return RecordTransformer|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function createTransformer(DataSource $dataSource): RecordTransformer
    {
        $transformerConfig = $this->transformers[$dataSource->type] ?? [];
        return ObjectHelper::create($transformerConfig, RecordTransformer::class);
    }

    /**
     * @param DataSource $dataSource
     * @return SourceInterface|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function createSource(DataSource $dataSource): SourceInterface
    {
        if (empty($this->clientSources[$dataSource->type])) {
            throw new InvalidConfigException('Invalid source type');
        }
        $clientSourceConfig = $this->clientSources[$dataSource->type];
        $clientSourceConfig['client'] = $this->createClient($dataSource->dataAccount);
        $clientSource = ObjectHelper::create($clientSourceConfig, ClientSource::class);

        if (empty($this->incrementSources[$dataSource->type])) {
            return $clientSource;
        }
        $incrementSourceConfig = array_merge($this->incrementSources[$dataSource->type], [
            'source' => $clientSource,
            'sourceKey' => $dataSource->data_source_id,
            'dataStorage' => [
                'class' => DataSourceModelStorage::class
            ]
        ]);
        return ObjectHelper::create($incrementSourceConfig, IncrementSource::class);
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
        return ObjectHelper::create($clientConfig, null, $dataAccount);
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
