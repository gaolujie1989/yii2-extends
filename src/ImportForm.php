<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\import;


use lujie\data\exchange\pipeline\PipelineInterface;
use lujie\data\exchange\parsers\CsvParser;
use lujie\data\exchange\parsers\ExcelParser;
use lujie\data\exchange\parsers\ParserInterface;
use lujie\data\exchange\transformers\TransformerInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\di\Instance;

/**
 * Class OwnerProductImportForm
 * @package ccship\common\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ImportForm extends Model
{
    /**
     * @var string
     */
    public $fileAttribute = 'file';

    /**
     * @var string
     */
    public $fileAttributeLabel = 'File';

    /**
     * @var array
     */
    public $files;

    /**
     * @var string
     */
    public $path = '/tmp';

    /**
     * @var ParserInterface[]}callable[] the custom file data parser
     */
    public $parsers = ExcelParser::class;

    /**
     * @var TransformerInterface|callable the custom data transformer
     */
    public $transformer;

    /**
     * @var PipelineInterface
     */
    public $importer;

    /**
     * @var array import results
     */
    public $affectedRowCounts;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->path = Yii::getAlias($this->path);
        $this->path = rtrim($this->path, '/') . '/';
    }

    #region overwrite model

    /**
     * @param string $name
     * @return array|mixed
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($name === $this->fileAttribute) {
            return $this->files;
        }
        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if ($name === $this->fileAttribute) {
            $this->files = is_array($value) ? $value : [$value];
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return [$this->fileAttribute];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            $this->fileAttribute => $this->fileAttributeLabel
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return [$this->fileAttribute, 'affectedRowCounts'];
    }

    #endregion

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [[$this->fileAttribute], 'required'],
            [[$this->fileAttribute], 'validateFilesExist'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function validateFilesExist(): void
    {
        foreach ($this->files as $file) {
            if (!file_exists($this->getFilePath($file))) {
                $this->addError($this->fileAttribute, Yii::t('lujie/import', 'Import file not exists.'));
            }
        }
    }

    /**
     * @return string
     * @inheritdoc
     */
    protected function getFilePath($file): string
    {
        return $this->path . $file;
    }

    /**
     * @return bool|mixed
     * @throws NotSupportedException
     * @throws \Throwable
     * @inheritdoc
     */
    public function import(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        foreach ($this->files as $file) {
            $filePath = $this->getFilePath($file);
            $data = $this->parseFileData($filePath);
            $data = $this->transformFileData($data);
            if ($this->importFileData($data)) {
                $this->affectedRowCounts[$file] = $this->dataImporter->getAffectedRowCounts();
            } else {
                $this->addError($this->fileAttribute, [$file => $this->dataImporter->getErrors()]);
            }
        }
        return !$this->hasErrors();
    }

    /**
     * @param $file
     * @return array
     * @throws InvalidConfigException
     * @throws NotSupportedException
     * @inheritdoc
     */
    protected function parseFileData($file): array
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        if (isset($this->dataParsers[$extension])) {
            /** @var ParserInterface $parser */
            $parser = Instance::ensure($this->dataParsers[$extension], ParserInterface::class);
            return $parser->parse($file);
        }
        throw new NotSupportedException('Import file not supported.');
    }

    /**
     * @param $data
     * @return array|mixed
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function transformFileData($data): ?array
    {
        if ($this->dataTransformer) {
            if (is_callable($this->dataTransformer)) {
                return call_user_func($this->dataTransformer, $data);
            } else {
                $this->dataTransformer = Instance::ensure($this->dataTransformer, TransformerInterface::class);
                return $this->dataTransformer->transform($data);
            }
        }
    }

    /**
     * @param $data
     * @return bool|mixed
     * @throws \Throwable
     * @inheritdoc
     */
    protected function importFileData($data): void
    {
        $this->dataImporter = Instance::ensure($this->dataImporter, PipelineInterface::class);
        $this->dataImporter->import($data);
    }
}
