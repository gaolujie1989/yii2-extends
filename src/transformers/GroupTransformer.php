<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\transformers;

use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class IndexTransformer
 * @package lujie\data\exchange\transformers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class GroupTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @var array for group like relation models
     */
    public $groupConfig = [
        'valueKeys' => [],
        'multi' => true,
        'indexKey' => null,
        'subGroups' => [],
    ];

    /**
     * @param array $data
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        return $this->groupData($data, $this->groupConfig);
    }

    /**
     * @param $data
     * @param array $group
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function groupData($data, $group = []): array
    {
        $indexKey = $group['indexKey'] ?? null;
        $valueKeys = $group['valueKeys'] ?? [];
        $flipValueKeys = array_flip($valueKeys);

        $groupData = $indexKey ? ArrayHelper::index($data, $indexKey) : $data;
        $groupData = $flipValueKeys ? array_map(static function($values) use ($flipValueKeys) {
            return array_intersect_key($values, $flipValueKeys);
        }, $groupData) : $groupData;

        if (isset($group['subGroups'])) {
            if (empty($indexKey)) {
                throw new InvalidConfigException('IndexKey must be set if exist sub groups.');
            }
            foreach ($group['subGroups'] as $groupName => $subGroup) {
                $subGroupData = ArrayHelper::index($data, null, $indexKey);
                foreach ($subGroupData as $subGroupKeyValue => $subGroupValues) {
                    $groupData[$subGroupKeyValue][$groupName] = $this->groupData($subGroupValues, $subGroup);
                }
            }
        }

        if (empty($group['multi']) && count($groupData)) {
            $groupData = reset($groupData);
        }
        return $groupData;
    }
}
