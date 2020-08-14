<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\forms;

use lujie\charging\ChargeTableFileImporter;
use lujie\data\exchange\forms\FileImportForm;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\FillDefaultValueTransformer;

/**
 * Class ChargeTableImportForm
 * @package lujie\charging\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableFileImportForm extends FileImportForm
{
    public $ownerId;

    public $startedTime;

    public $endedTime;

    /**
     * @var ChargeTableFileImporter
     */
    public $fileImporter = ChargeTableFileImporter::class;

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
        ]]);
        /** @var ChainedTransformer $transformer */
        $transformer = $this->fileImporter->transformer;
        array_unshift($transformer->transformers, $fillOwnerIdTransformer);
        return parent::import();
    }
}