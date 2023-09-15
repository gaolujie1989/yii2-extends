<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\gii\generators\migration;

use lujie\extend\db\Migration;
use lujie\extend\file\FileReaderInterface;
use lujie\extend\file\readers\CsvReader;
use lujie\extend\file\readers\JsonReader;
use lujie\extend\helpers\ValueHelper;
use Yii;
use yii\di\Instance;
use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;

/**
 * Class Generator
 * @package lujie\extend\gii\generators\openapi
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Generator extends \yii\gii\Generator
{
    public $ns = 'app\migrations';

    public $baseClass = Migration::class;

    public $dataFilePath = '';

    public $detectRows = 1000;

    public $skipEmptyContentColumns = true;

    /**
     * @var array[]
     */
    public $readers = [
        'csv' => [
            'class' => CsvReader::class,
            'delimiter' => "\t",
            'enclosure' => ''
        ],
        'json' => [
            'class' => JsonReader::class,
        ]
    ];

    /**
     * @return string
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'Migration Generator';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return 'This generator generates Migration code base on data file.';
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['ns', 'baseClass', 'dataFilePath'], 'required'],
            [['ns', 'baseClass', 'dataFilePath'], 'trim'],
            [['ns', 'baseClass', 'dataFilePath'], 'string'],
            [['skipEmptyContentColumns'], 'boolean'],
        ]);
    }

    /**
     * @return string[]
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'ns' => 'Namespace',
            'baseClass' => 'Base Class',
            'dataFilePath' => 'Data File Path',
            'skipEmptyContentColumns' => 'Skip Empty Content Columns',
        ];
    }

    /**
     * @return string[]
     * @inheritdoc
     */
    public function requiredTemplates(): array
    {
        return ['migration.php'];
    }

    /**
     * @return array|CodeFile[]
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function generate(): array
    {
        $files = [];

        $dataFilePath = Yii::getAlias($this->dataFilePath);
        $dataFiles = FileHelper::findFiles($dataFilePath, ['only' => ['*.json', '*.csv']]);
        sort($dataFiles);
        foreach ($dataFiles as $dataFile) {
            $apiClassName = $this->generateClassName($dataFile);
            $generateTableName = $this->generateTableName($dataFile);
            $columns = $this->generateColumns($dataFile);
            $indexes = $this->generateIndexes($columns);
            $params = [
                'className' => $apiClassName,
                'tableName' => $generateTableName,
                'columns' => $columns,
                'indexes' => $indexes,
            ];
            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $apiClassName . '.php',
                $this->render('migration.php', $params)
            );
        }
        return $files;
    }

    /**
     * @param string $dataFile
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function generateColumns(string $dataFile): array
    {
        $ext = strtolower(pathinfo($dataFile, PATHINFO_EXTENSION));
        /** @var FileReaderInterface $reader */
        $reader = Instance::ensure($this->readers[$ext], FileReaderInterface::class);
        $data = $reader->read($dataFile);
        if (empty($data)) {
            return [];
        }
        $firstRow = reset($data);
        $firstNRows = array_slice($data, 0, $this->detectRows);
        $columns = [];
        foreach ($firstRow as $key => $value) {
            $columnKey = Inflector::underscore(Inflector::id2camel($key));
            if (str_contains($key, 'time') || str_contains($key, 'date')) {
                $columnKey = strtr($columnKey, ['time' => 'at', 'date' => 'at']);
                $columns[$columnKey] = 'integer()->unsigned()->notNull()->defaultValue(0)';
                continue;
            }
            $values = ArrayHelper::getColumn($firstNRows, $key);
            $columns[$columnKey] = $this->getColumnDefine($values);
        }
        return array_filter($columns);
    }

    /**
     * @param array $columns
     * @return array
     * @inheritdoc
     */
    protected function generateIndexes(array $columns): array
    {
        $indexes = [];
        foreach ($columns as $key => $value) {
            if (str_contains($key, '_at') || str_contains($key, '_id')
                || str_contains($key, '_no') || str_contains($key, '_key') || str_contains($key, '_code')
                || str_contains($key, 'sku') || str_contains($key, 'asin')) {
                $indexes['idx_' . $key] = [$key];
            }
        }
        return $indexes;
    }

    /**
     * @param array $values
     * @return array
     * @inheritdoc
     */
    public function getColumnDefine(array $values): ?string
    {
        $maxValueTypeLength = max(array_map(static function ($v) {
            if (ValueHelper::isEmpty($v)) {
                return 0;
            }
            if (is_numeric($v)) {
                if (str_contains($v, '.')) {
                    return 50 + strlen(explode('.', $v)[1]);
                }
                return strlen($v);
            }
            return 100 + strlen($v);
        }, $values));
        if ($maxValueTypeLength <= 0) {
            return $this->skipEmptyContentColumns ? null : "string({255})->notNull()->defaultValue('')";
        }
        if ($maxValueTypeLength < 10) {
            return 'integer()->notNull()->defaultValue(0)';
        }
        if ($maxValueTypeLength <= 20) {
            return 'bigInteger()->notNull()->defaultValue(0)';
        }
        if ($maxValueTypeLength < 60) {
            $scale = $maxValueTypeLength - 50;
            return "decimal(10, $scale)->notNull()->defaultValue(0)";
        }
        $strLength = $maxValueTypeLength - 100;
        if ($strLength < 5) {
            return "char({$strLength})->notNull()->defaultValue('')";
        }
        $strLengthSteps = [10, 20, 50, 100, 200, 250];
        foreach ($strLengthSteps as $strLengthStep) {
            if ($strLength < $strLengthStep) {
                return "string({$strLengthStep})->notNull()->defaultValue('')";
            }
        }
        return "text()";
    }

    /**
     * @param string $dataFile
     * @return string
     * @inheritdoc
     */
    protected function generateTableName(string $dataFile): string
    {
        return Inflector::underscore(Inflector::id2camel(pathinfo($dataFile, PATHINFO_FILENAME)));
    }

    /**
     * @param string $dataFile
     * @return string
     * @inheritdoc
     */
    protected function generateClassName(string $dataFile): string
    {
        return 'm' . date('Ymd_000000') . '_' . $this->generateTableName($dataFile);
    }
}
