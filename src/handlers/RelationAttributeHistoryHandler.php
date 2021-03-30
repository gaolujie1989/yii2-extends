<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\ar\history\handlers;

use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class AddressDiffHandler
 * @package lujie\ar\history\handlers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RelationAttributeHistoryHandler extends BaseAttributeHistoryHandler
{
    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @var bool
     */
    public $multi = false;

    /**
     * @var string
     */
    public $indexAttribute;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (empty($this->attributes)) {
            throw new InvalidConfigException('The property `attributes` must be set.');
        }
        if ($this->multi && empty($this->indexAttribute)) {
            throw new InvalidConfigException('The property `indexAttribute` must be set when multi is true');
        }
    }

    /**
     * @param BaseActiveRecord|BaseActiveRecord[]|array $value
     * @return array|void
     * @inheritdoc
     */
    public function extract($value)
    {
        if ($this->multi) {
            return array_map([$this, 'extractValue'], $value);
        } else {
            return $this->extractValue($value);
        }
    }

    /**
     * @param BaseActiveRecord|null $value
     * @return array
     * @inheritdoc
     */
    public function extractValue(?BaseActiveRecord $value): array
    {
        return $value ? $value->getAttributes($this->attributes) : [];
    }

    /**
     * @param mixed|BaseActiveRecord $oldValue
     * @param mixed|BaseActiveRecord $newValue
     * @return array|null
     * @inheritdoc
     */
    public function diff($oldValue, $newValue): ?array
    {
        if ($this->multi) {
            $oldValue = ArrayHelper::index($oldValue, $this->indexAttribute);
            $newValue = ArrayHelper::index($newValue, $this->indexAttribute);
            foreach ($oldValue as $key => $value) {
                $oldValue[$key] = $this->extractValue($value);
            }
            foreach ($newValue as $key => $value) {
                $newValue[$key] = $this->extractValue($value);
            }
            $modified = [];
            foreach ($newValue as $key => $i) {
                if (isset($oldValue[$key])) {
                    $modified[$key] = $this->diffRelationValue($oldValue[$key], $newValue[$key]);
                    unset($newValue[$key], $oldValue[$key]);
                }
            }
            $modified = array_filter($modified);
            return array_filter([
                'added' => $newValue,
                'deleted' => $oldValue,
                'modified' => $modified,
            ]) ?: null;
        } else {
            $oldValue = $this->extractValue($oldValue);
            $newValue = $this->extractValue($newValue);
            $modified = $this->diffRelationValue($oldValue, $newValue);
            return $modified ? ['modified' => $modified] : null;
        }
    }

    /**
     * @param array $oldValue
     * @param array $newValue
     * @return array
     */
    public function diffRelationValue(array $oldValue, array $newValue): array
    {
        $diffValue = [];
        $default = array_fill_keys($this->attributes, '');
        $newValue = array_merge($default, $newValue);
        $oldValue = array_merge($default, $oldValue);
        foreach ($this->attributes as $attribute) {
            if ($newValue[$attribute] !== $oldValue[$attribute]) {
                $diffValue[$attribute]  = $this->diffValue($oldValue[$attribute], $newValue[$attribute]);
            }
        }
        return $diffValue;
    }
}
