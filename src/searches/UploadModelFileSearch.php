<?php

namespace lujie\upload\searches;

use lujie\upload\models\UploadModelFile;
use lujie\upload\models\UploadModelFileQuery;

/**
 * Class UploadSavedFileSearch
 * @package lujie\upload\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadModelFileSearch extends UploadModelFile
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['model_id', 'model_parent_id', 'name', 'ext'], 'safe'],
        ];
    }

    /**
     * @return UploadModelFileQuery
     * @inheritdoc
     */
    public function query(): UploadModelFileQuery
    {
        return static::find()
            ->andFilterWhere(['LIKE', 'name', $this->name])
            ->andFilterWhere([
                'model_id' => $this->model_id,
                'model_parent_id' => $this->model_parent_id,
                'ext' => $this->ext
            ]);
    }
}
