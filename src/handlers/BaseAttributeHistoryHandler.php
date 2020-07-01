<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ar\history\handlers;

use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class AddressDiffHandler
 * @package lujie\ar\history\handlers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BaseAttributeHistoryHandler extends BaseObject implements AttributeHistoryHandlerInterface
{
    public $maxValueLength = 33;

    /**
     * @param $value
     * @return mixed
     * @inheritdoc
     */
    public function extract($value)
    {
        return $value;
    }

    /**
     * @param string|int $oldValue
     * @param string|int $newValue
     * @return array|null
     * @inheritdoc
     */
    public function diff($oldValue, $newValue): ?array
    {
        $diffValue = $this->diffValue($oldValue, $newValue);
        return $diffValue ? ['modified' => $diffValue] : null;
    }

    /**
     * @param $oldValue
     * @param $newValue
     * @return string|null
     * @inheritdoc
     */
    protected function diffValue($oldValue, $newValue): ?string
    {
        if ($oldValue === $newValue) {
            return null;
        }
        if (strlen($oldValue) > $this->maxValueLength) {
            $oldValue = mb_substr($oldValue, 0, $this->maxValueLength - 3) . '...';
        }
        if (strlen($newValue) > $this->maxValueLength) {
            $newValue = mb_substr($newValue, 0, $this->maxValueLength - 3) . '...';
        }
        return "'{$oldValue}' -> '{$newValue}'";
    }
}