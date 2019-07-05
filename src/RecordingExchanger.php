<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording;

use lujie\data\exchange\Exchanger;
use lujie\data\loader\DataLoaderInterface;

/**
 * Class DataRecording
 * @package lujie\data\recording
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RecordingExchanger extends Exchanger
{
    /**
     * @var DataLoaderInterface|RecordingExchangeLoader
     */
    public $exchangeLoader = RecordingExchangeLoader::class;
}
