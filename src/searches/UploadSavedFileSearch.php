<?php

namespace lujie\upload\searches;

use lujie\upload\models\UploadSavedFile;
use lujie\upload\models\UploadSavedFileQuery;

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
