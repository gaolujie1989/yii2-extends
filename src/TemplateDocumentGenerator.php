<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document;

use lujie\data\loader\DataLoaderInterface;
use lujie\template\document\engines\TemplateEngineInterface;
use lujie\template\document\generators\DocumentGeneratorInterface;
use lujie\template\document\generators\PdfGenerator;
use lujie\template\document\models\DocumentTemplate;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii2tech\html2pdf\Manager;

/**
 * Class TemplateDocumentGenerator
 * @package lujie\template\document
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TemplateDocumentGenerator extends BaseObject
{
    /**
     * @var TemplateEngineInterface
     */
    public $templateEngine;

    /**
     * @var DocumentGeneratorInterface
     */
    public $documentGenerator = PdfGenerator::class;

    /**
     * @var DataLoaderInterface[]
     */
    public $templateDataLoaders = [];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->templateEngine = Instance::ensure($this->templateEngine, TemplateEngineInterface::class);
        $this->documentGenerator = Instance::ensure($this->documentGenerator, DocumentGeneratorInterface::class);
    }

    /**
     * @param string $documentType
     * @param int $documentReferenceId
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function render(string $documentType, int $documentReferenceId = 0): string
    {
        $templates = $this->getTemplates($documentType, $documentReferenceId);
        $referenceData = $this->getReferenceData($documentType, $documentReferenceId);
        return $this->renderDocumentContent($templates, $referenceData);
    }

    /**
     * @param string $documentType
     * @param int $documentReferenceId
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function generate(string $documentType, int $documentReferenceId = 0): string
    {
        return $this->documentGenerator->generate($this->render($documentType, $documentReferenceId));
    }

    /**
     * @param string $documentType
     * @param int $documentReferenceId
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    protected function getTemplates(string $documentType, int $documentReferenceId): array
    {
        $templateQuery = DocumentTemplate::find()->type($documentType)->referenceId($documentReferenceId);
        if ($documentReferenceId && !$templateQuery->exists()) {
            $defaultTemplates = DocumentTemplate::find()
                ->type($documentType)
                ->referenceId(0)
                ->asArray()
                ->all();
            if (empty($defaultTemplates)) {
                throw new InvalidArgumentException("Unknown Document Type: {$documentType}");
            }
            $defaultTemplates = array_map(static function ($template) use ($documentReferenceId) {
                unset($template['document_template_id']);
                $template['document_reference_id'] = $documentReferenceId;
                return $template;
            }, $defaultTemplates);
            DocumentTemplate::getDb()
                ->createCommand()
                ->batchInsert(DocumentTemplate::tableName(), array_keys($defaultTemplates[0]), $defaultTemplates)
                ->execute();
        }
        return $templateQuery->active()->orderByPosition()->asArray()->all();
    }

    /**
     * @param string $documentType
     * @param int $documentReferenceId
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getReferenceData(string $documentType, int $documentReferenceId): array
    {
        $templateDataLoader = $this->getTemplateDataLoader($documentType);
        return $templateDataLoader->get($documentReferenceId);
    }

    /**
     * @param string $documentType
     * @return DataLoaderInterface
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getTemplateDataLoader(string $documentType): DataLoaderInterface
    {
        if (empty($this->templateDataLoaders[$documentType])) {
            throw new InvalidArgumentException("Unknown Document Type: {$documentType}");
        }
        if (!($this->templateDataLoaders[$documentType] instanceof DataLoaderInterface)) {
            $this->templateDataLoaders[$documentType] = Instance::ensure($this->templateDataLoaders[$documentType], DataLoaderInterface::class);
        }
        return $this->templateDataLoaders[$documentType];
    }

    /**
     * @param array $templates
     * @param array $templateData
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @inheritdoc
     */
    protected function renderDocumentContent(array $templates, array $templateData): string
    {
        $this->templateEngine->setTemplates($templates);
        $renderContents = [];
        foreach ($templates as $template) {
            unset($template['content']);
            //set template self data
            $templateData['template'] = $template;
            $name = $template['document_template_id'];
            $renderContents[] = $this->templateEngine->render($name, $templateData);
        }
        return implode('', $renderContents);
    }
}
