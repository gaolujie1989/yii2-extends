<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document;

use lujie\data\loader\DataLoaderInterface;
use lujie\extend\file\FileWriterInterface;
use lujie\extend\file\writers\PdfWriter;
use lujie\template\document\engines\TemplateEngineInterface;
use lujie\template\document\engines\TwigTemplateEngine;
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
     * @var TemplateEngineInterface
     */
    public $templateEngine = TwigTemplateEngine::class;

    /**
     * @var FileWriterInterface
     */
    public $fileWriter = PdfWriter::class;

    /**
     * @var DataLoaderInterface
     */
    public $templateLoader;

    /**
     * @var DataLoaderInterface
     */
    public $referenceDataLoader;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->templateEngine = Instance::ensure($this->templateEngine, TemplateEngineInterface::class);
        $this->fileWriter = Instance::ensure($this->fileWriter, FileWriterInterface::class);
        $this->templateLoader = Instance::ensure($this->templateLoader, DataLoaderInterface::class);
        $this->referenceDataLoader = Instance::ensure($this->referenceDataLoader, DataLoaderInterface::class);
    }

    /**
     * @param mixed $documentReferenceKey
     * @return string
     * @inheritdoc
     */
    public function render($documentReferenceKey): string
    {
        $template = $this->templateLoader->get($documentReferenceKey);
        $referenceData = $this->referenceDataLoader->get($documentReferenceKey);
        return $this->templateEngine->render($template, $referenceData ?: []);
    }

    /**
     * @param string $filePath
     * @param mixed $documentReferenceKey
     * @inheritdoc
     */
    public function generate(string $filePath, $documentReferenceKey): void
    {
        $this->fileWriter->write($filePath, [$this->render($documentReferenceKey)]);
    }
}
