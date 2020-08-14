<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\forms;


use lujie\charging\ChargeTableFileImporter;
use lujie\charging\models\ShippingTable;
use lujie\charging\ShippingTableFileImporter;
use lujie\data\exchange\forms\FileImportForm;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\FillDefaultValueTransformer;

/**
 * Class ShippingTableImportForm
 * @package lujie\charging\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableFileImportForm extends FileImportForm
{
    public $ownerId;

    public $startedTime;

    public $endedTime;

    public $departure = 'DE';

    /**
     * @var ChargeTableFileImporter
     */
    public $fileImporter = ShippingTableFileImporter::class;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['ownerId', 'startedTime', 'endedTime'], 'required'],
            [['startedTime', 'endedTime'], 'string'],
        ]);
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function import(): bool
    {
        $fillOwnerIdTransformer = new FillDefaultValueTransformer(['defaultValues' => [
            'owner_id' => $this->ownerId,
            'started_time' => $this->startedTime,
            'ended_time' => $this->endedTime,
            'departure' => $this->departure,
        ]]);
        /** @var ChainedTransformer $transformer */
        $transformer = $this->fileImporter->transformer;
        array_unshift($transformer->transformers, $fillOwnerIdTransformer);
        return parent::import();
    }
}