<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\actions;

use lujie\extend\helpers\TemplateHelper;
use lujie\template\document\TemplateDocumentManager;
use Yii;
use yii\base\Action;
use yii\di\Instance;
use yii\web\Response;

/**
 * Class DocumentAction
 * @package lujie\template\document\actions
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DocumentAction extends Action
{
    /**
     * @var string
     */
    public $filePathTemplate = '@runtime/documents/{documentType}/{documentKey}.pdf';

    /**
     * @var TemplateDocumentManager
     */
    public $documentManager = 'documentManager';

    /**
     * @var string
     */
    public $documentType;

    /**
     * @var bool
     */
    public $preview = false;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->documentManager = Instance::ensure($this->documentManager, TemplateDocumentManager::class);
    }

    /**
     * @param $key
     * @param string|null $type
     * @return string|null
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function run($key, ?string $type = null): ?string
    {
        $type = $type ?: $this->documentType;
        if ($this->preview) {
            return $this->preview($type, $key);
        }
        $this->download($type, $key);
        return null;
    }

    /**
     * @param string $type
     * @param $key
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public function download(string $type, $key): void
    {
        $data = ['{documentType}' => $type, '{documentKey}' => substr($key, 0, 50)];
        $generateFile = Yii::getAlias(TemplateHelper::generate($this->filePathTemplate, $data));
        $this->documentManager->generate($type, $key, $generateFile);
        $ext = pathinfo($generateFile, PATHINFO_EXTENSION);
        $inline = in_array($ext, ['pdf', 'html'], true);
        Yii::$app->getResponse()->sendFile($generateFile, null, ['inline' => $inline]);
    }

    /**
     * @param string $type
     * @param $key
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function preview(string $type, $key): string
    {
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;
        return $this->documentManager->render($type, $key);
    }
}
