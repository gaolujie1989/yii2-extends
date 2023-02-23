<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\models;

interface ItemInterface
{
    public function getWeightG(): int;

    public function getLengthMM(): int;

    public function getWidthMM(): int;

    public function getHeightMM(): int;
}