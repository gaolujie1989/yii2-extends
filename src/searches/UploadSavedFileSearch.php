<?php

namespace lujie\upload\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use lujie\upload\behaviors\FileBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UploadSavedFileSearch
 * @package lujie\upload\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadSavedFileSearch extends UploadSavedFile
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['model_id', 'name', 'ext'], 'safe'],
        ];
    }

    /**
     * @return UploadSavedFileQuery
     * @inheritdoc
     */
    public function query(): UploadSavedFileQuery
    {
        return static::find()
            ->andFilterWhere(['LIKE', 'name', $this->name])
            ->andFilterWhere([
                'model_id' => $this->model_id,
                'ext' => $this->ext
            ]);
    }
}
