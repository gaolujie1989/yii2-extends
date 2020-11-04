<?php

namespace lujie\common\address\forms;

use lujie\common\address\AddressPostalCodeImporter;
use lujie\data\exchange\forms\FileImportForm;

/**
 * Class AddressPostalCodeSearch
 * @package lujie\common\address\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AddressPostalCodeImportForm extends FileImportForm
{
    /**
     * @var AddressPostalCodeImporter
     */
    public $fileImporter = AddressPostalCodeImporter::class;
}
