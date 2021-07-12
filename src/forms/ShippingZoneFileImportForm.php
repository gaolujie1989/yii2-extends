<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\forms;

use lujie\charging\ShippingZoneFileImporter;
use lujie\data\exchange\forms\FileImportForm;

/**
 * Class ShippingZoneFileImportForm
 * @package lujie\charging\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingZoneFileImportForm extends FileImportForm
{
    /**
     * @var string[]
     */
    public $dataAttributes = ['owner_id', 'started_time', 'ended_time'];

    /**
     * @var ShippingZoneFileImporter
     */
    public $fileImporter = ShippingZoneFileImporter::class;
}
