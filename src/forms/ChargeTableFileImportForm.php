<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\forms;

use lujie\charging\ChargeTableFileImporter;
use lujie\data\exchange\forms\FileImportForm;

/**
 * Class ChargeTableImportForm
 * @package lujie\charging\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableFileImportForm extends FileImportForm
{
    /**
     * @var string[]
     */
    public $dataAttributes = ['owner_id', 'started_time', 'ended_time'];

    /**
     * @var ChargeTableFileImporter
     */
    public $fileImporter = ChargeTableFileImporter::class;
}
