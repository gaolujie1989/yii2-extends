<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\controllers\rest;

use lujie\extend\helpers\TemplateHelper;
use lujie\template\document\actions\DocumentAction;
use lujie\template\document\TemplateDocumentManager;
use Yii;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Class TemplateDocumentController
 * @package lujie\template\document\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TemplateDocumentController extends Controller
{
    /**
     * @var TemplateDocumentManager
     */
    public $documentManager = 'documentManager';

    /**
     * @var string
     */
    public $documentType;

    /**
     * @return array
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'download' => [
                'class' => DocumentAction::class,
                'documentManager' => $this->documentManager,
                'documentType' => $this->documentType,
            ],
            'preview' => [
                'class' => DocumentAction::class,
                'documentManager' => $this->documentManager,
                'documentType' => $this->documentType,
                'preview' => true,
            ]
        ]);
    }
}
