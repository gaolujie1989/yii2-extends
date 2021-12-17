<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document;

use lujie\data\loader\BaseDataLoader;
use lujie\template\document\forms\DocumentTemplateForm;
use lujie\template\document\models\DocumentTemplate;
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
     * @var bool
     */
    public $autoCreate = true;

    /**
     * @var bool
     */
    public $useDefault = true;

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
            ->referenceId($key)
            ->orderByPosition()
            ->asArray();
        if (!$query->exists()) {
            if ($this->autoCreate) {
                DocumentTemplateForm::createTemplates($this->documentType, $key);
            } else if ($this->useDefault) {
                $query = DocumentTemplate::find()
                    ->documentType($this->documentType)
                    ->referenceId(0)
                    ->orderByPosition()
                    ->asArray();
            }
        }
        $templates = $query->active()->all();
        return implode('', ArrayHelper::getColumn($templates, 'content'));
    }
}
