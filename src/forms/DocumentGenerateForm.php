<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\forms;

use creocoder\flysystem\Filesystem;
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
    public $documentType;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->documentManager = Instance::ensure($this->documentManager, TemplateDocumentManager::class);
        $this->fs = Instance::ensure($this->fs, Filesystem::class);
        if (empty($this->documentType)) {
            throw new InvalidConfigException('DocumentType must be set');
        }
    }

    /**
     * @return array[]
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['reference_id', 'document_no', 'document_data'], 'require'],
            [['reference_id'], 'integer'],
            [['reference_no', 'document_no'], 'string', 'max' => 50],
            [['document_data'], 'safe'],
        ];
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function generate(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $query = static::find()
            ->documentType($this->documentType)
            ->referenceId($this->reference_id);
        if ($query->exists()) {
            $this->setIsNewRecord(false);
            $model = $query->one();
            $this->setOldAttributes($model->getOldAttributes());
        }
        $this->save(false);

        $filePath = Yii::getAlias("@runtime/{$this->document_no}.pdf");

        $this->documentManager->generate($this->documentType, $this->reference_id, $filePath);

        if ($this->fs->has($filePath)) {
            $this->fs->delete($filePath);
        }
        $this->fs->write($filePath, file_get_contents($filePath));
        unlink($filePath);

        $this->document_file = $filePath;
        return $this->save(false);
    }
}