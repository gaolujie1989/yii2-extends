<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;


use lujie\data\exchange\file\FileParserInterface;
use lujie\data\exchange\file\parsers\ExcelParser;
use lujie\data\exchange\pipelines\DbPipelineInterface;
use lujie\data\exchange\sources\FileSource;
use yii\di\Instance;

/**
 * Class FileImporter
 * @package lujie\data\exchange
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileImporter extends DataExchanger
{
    /**
     * @var FileParserInterface
     */
    public $fileParser = ExcelParser::class;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->fileParser = Instance::ensure($this->fileParser, FileParserInterface::class);
    }

    /**
     * @param string $file
     * @return bool
     * @inheritdoc
     */
    public function importFromFile(string $file): bool
    {
        $source = new FileSource([
            'fileParser' => $this->fileParser,
            'file' => $file,
        ]);
        return $this->exchange($source);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getAffectedRowCounts(): array
    {
        if ($this->pipeline instanceof DbPipelineInterface) {
            return $this->pipeline->getAffectedRowCounts();
        }
        return [];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function getErrors(): array
    {
        if ($this->pipeline instanceof DbPipelineInterface) {
            return $this->pipeline->getErrors();
        }
        return [];
    }
}
