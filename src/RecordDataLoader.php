<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording;

use lujie\data\loader\BaseDataLoader;
use lujie\data\recording\models\DataRecord;
use lujie\data\recording\models\DataRecordData;
use yii\helpers\Json;

/**
 * Class RecordDataLoader
 * @package lujie\data\recording
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RecordDataLoader extends BaseDataLoader
{
    /**
     * @var string
     */
    public $modelClass = DataRecord::class;

    /**
     * @var string
     */
    public $key = 'data_id';

    /**
     * @var string
     */
    public $indexBy = 'data_id';

    /**
     * @var string
     */
    public $value = 'data_record_id';

    /**
     * @param int|mixed|string $key
     * @return mixed|null
     * @inheritdoc
     */
    public function get($key)
    {
        $orders = $this->multiGet([$key]);
        return $orders[$key] ?? null;
    }

    /**
     * @param array $keys
     * @return array
     * @inheritdoc
     */
    public function multiGet(array $keys): array
    {
        $dataRecordIds = parent::multiGet($keys);
        if (empty($dataRecordIds)) {
            return [];
        }

        $dataList = [];
        $dataIds = array_flip($dataRecordIds);
        $dataTexts = DataRecordData::getDataTextsByRecordIds($dataRecordIds);
        foreach ($dataTexts as $recordId => $dataText) {
            $data = Json::decode($dataText);
            $dataList[$dataIds[$recordId]] = $data;
        }
        return $dataList;
    }
}
