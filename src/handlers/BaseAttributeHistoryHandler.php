<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ar\history\handlers;

use yii\base\BaseObject;

/**
 * Class AddressDiffHandler
 * @package lujie\ar\history\handlers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BaseAttributeHistoryHandler extends BaseObject implements AttributeHistoryHandlerInterface
{
    public $maxValueLength = 33;

    /**
     * @var bool
     */
    public $strict = false;

    /**
     * @param mixed $value
     * @return mixed
     * @inheritdoc
     */
    public function extract($value)
    {
        return $value;
    }

    /**
     * @param mixed|int|string $oldValue
     * @param mixed|int|string $newValue
     * @return string[]|null
     * @inheritdoc
     */
    public function diff($oldValue, $newValue): ?array
    {
        $diffValue = $this->diffValue($oldValue, $newValue);
        return $diffValue ? ['modified' => $diffValue] : null;
    }

    /**
     * @param mixed $oldValue
     * @param mixed $newValue
     * @return string|null
     * @inheritdoc
     */
    protected function diffValue($oldValue, $newValue): ?string
    {
        /** @noinspection TypeUnsafeComparisonInspection */
        $isEqual = $this->strict ? $oldValue === $newValue : $oldValue == $newValue;
        if ($isEqual) {
            return null;
        }
        if (strlen($oldValue) > $this->maxValueLength) {
            $oldValue = mb_substr($oldValue, 0, $this->maxValueLength - 3) . '...';
        }
        if (strlen($newValue) > $this->maxValueLength) {
            $newValue = mb_substr($newValue, 0, $this->maxValueLength - 3) . '...';
        }
        return strtr('"{oldValue}" -> "{newValue}"', ['{oldValue}' => $oldValue, '{newValue}' => $newValue]);
    }
}
