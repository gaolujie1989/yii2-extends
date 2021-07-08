<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\forms;

use lujie\charging\ChargeTableFileImporter;
use lujie\charging\ShippingTableFileImporter;
use lujie\data\exchange\forms\FileImportForm;
use lujie\data\exchange\transformers\ChainedTransformer;
use lujie\data\exchange\transformers\FillDefaultValueTransformer;
use lujie\extend\helpers\ModelHelper;

/**
 * Class ShippingTableImportForm
 * @package lujie\charging\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableFileImportForm extends FileImportForm
{
    /**
     * @var string[]
     */
    public $dataAttributes = ['owner_id', 'started_time', 'ended_time', 'departure'];

    /**
     * @var ShippingTableFileImporter
     */
    public $fileImporter = ShippingTableFileImporter::class;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        ModelHelper::removeAttributesRules($rules, 'departure');
        $rules[] = [['departure'], 'default', 'value' => 'DE'];
        return $rules;
    }
}
