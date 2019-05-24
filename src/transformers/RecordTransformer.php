<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\center\transformers;

use lujie\data\exchange\transformers\TransformerInterface;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class RecordTransformer
 * @package lujie\data\center\transformers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RecordTransformer extends BaseObject implements TransformerInterface
{
    /**
     * @var array
     */
    public $recordConfig = [
        'data_key' => '',
        'data_parent_id' => '',
        'data_additional' => '',
    ];

    /**
     * @var array
     */
    public $defaultConfig = [
        'data_id' => 'id',
        'data_created_at' => 'createdAt',
        'data_updated_at' => 'updatedAt',
    ];

    /**
     * @var string
     */
    public $serializer = 'serialize';

    /**
     * @var string
     */
    public $compressor = 'gzdeflate';

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->serializer && !is_callable($this->serializer)) {
            throw new InvalidConfigException('The "serializer" property must be callable.');
        }
        if ($this->compressor && !is_callable($this->compressor)) {
            throw new InvalidConfigException('The "compressor" property must be callable.');
        }
    }

    /**
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        $record = [];
        $config = array_merge($this->defaultConfig, $this->recordConfig);
        foreach ($config as $attribute => $item) {
            if ($item && $value = ArrayHelper::getValue($data, $item)) {
                $record[$attribute] = $value;
            }
        }

        $text = $data;
        if ($this->serializer) {
            $text = call_user_func($this->serializer, $data);
        }
        if ($this->compressor) {
            $text = call_user_func($this->serializer, $text);
        }

        return [
            'data' => $data,
            'text' => $text,
            'record' => $record,
        ];
    }
}
