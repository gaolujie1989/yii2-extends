<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document;

use lujie\data\loader\BaseDataLoader;
use lujie\template\document\forms\DocumentTemplateForm;
use lujie\template\document\models\DocumentTemplate;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

/**
 * Class DocumentTemplateLoader
 * @package lujie\template\document
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DocumentTemplateLoader extends BaseDataLoader
{
    /**
     * @var string
     */
    public $documentType;

    /**
     * @param int|mixed|string $key
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function get($key): string
    {
        $query = DocumentTemplate::find()
            ->documentType($this->documentType)
            ->referenceId($key)->orderByPosition()
            ->asArray();
        if (!$query->exists()) {
            DocumentTemplateForm::createTemplates($this->documentType, $key);
        }
        $templates = $query->active()->all();
        return implode('', ArrayHelper::getColumn($templates, 'content'));
    }
}
