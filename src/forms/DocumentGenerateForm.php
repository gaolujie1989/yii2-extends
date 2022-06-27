<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\forms;

use creocoder\flysystem\Filesystem;
use lujie\extend\helpers\TemplateHelper;
use lujie\template\document\models\DocumentFile;
use lujie\template\document\TemplateDocumentManager;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class DocumentGenerateForm
 * @package lujie\template\document\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DocumentGenerateForm extends DocumentFile
{
    /**
     * @var TemplateDocumentManager
     */
    public $documentManager;

    /**
     * @var Filesystem
     */
    public $fs = 'filesystem';

    /**
     * @var string
     */
    public $filePathTemplate = 'documents/{document_type}/{document_no}.pdf';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->documentManager = Instance::ensure($this->documentManager, TemplateDocumentManager::class);
        $this->fs = Instance::ensure($this->fs, Filesystem::class);
    }

    /**
     * @return array[]
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['document_type', 'document_no'], 'require'],
            [['reference_id'], 'integer'],
            [['reference_no', 'document_no'], 'string', 'max' => 50],
            [['document_data'], 'safe'],
        ];
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public function generate(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $query = static::find()
            ->documentType($this->document_type)
            ->documentNo($this->document_no);
        if ($query->exists()) {
            $this->setIsNewRecord(false);
            $model = $query->one();
            $this->setOldAttributes($model->getOldAttributes());
        }
        $this->save(false);

        $filePath = TemplateHelper::generate($this->filePathTemplate, $this->attributes);

        $localFilePath = Yii::getAlias('@runtime/' . $filePath);
        $this->documentManager->generate($this->document_type, $this->document_file_id, $localFilePath);

        if ($this->fs->has($filePath)) {
            $this->fs->delete($filePath);
        }
        $this->fs->write($filePath, file_get_contents($localFilePath));
        unlink($localFilePath);

        $this->document_file = $filePath;
        return $this->save(false);
    }
}