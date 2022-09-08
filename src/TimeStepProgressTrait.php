<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use lujie\extend\helpers\ValueHelper;

/**
 * Trait TimeStepTrait
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait TimeStepProgressTrait
{
    use ProgressTrait;

    public $timeFrom;

    public $timeTo;

    public $timeStep = 86400;

    public function executeStep(): void
    {
        $timeAt = ValueHelper::formatDateTime($this->timeFrom);
        $timeAt = ValueHelper::formatDateTime($this->timeFrom);
    }
}