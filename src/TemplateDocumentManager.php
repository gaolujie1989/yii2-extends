<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document;

use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class TemplateDocumentGenerator
 * @package lujie\template\document
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TemplateDocumentManager extends BaseObject
{
    /**
     * @var TemplateDocumentGenerator[]
     */
    public $generators = [];

    /**
     * @param string $documentType
     * @return TemplateDocumentGenerator
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function getGenerator(string $documentType): TemplateDocumentGenerator
    {
        if (!($this->generators[$documentType] instanceof TemplateDocumentGenerator)) {
            $this->generators[$documentType] = Instance::ensure($this->generators[$documentType], TemplateDocumentGenerator::class);
        }
        return $this->generators[$documentType];
    }

    /**
     * @param string $documentType
     * @param $documentReferenceKey
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function render(string $documentType, $documentReferenceKey): string
    {
        return $this->getGenerator($documentType)->render($documentReferenceKey);
    }

    /**
     * @param string $documentType
     * @param $documentReferenceKey
     * @param string $filePath
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function generate(string $documentType, $documentReferenceKey, string $filePath): void
    {
        $this->getGenerator($documentType)->generate($filePath, $documentReferenceKey);
    }
}
