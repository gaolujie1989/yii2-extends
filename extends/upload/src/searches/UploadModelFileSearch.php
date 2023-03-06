<?php

namespace lujie\upload\searches;

use lujie\extend\db\SearchTrait;
use lujie\extend\helpers\QueryHelper;
use lujie\upload\models\UploadModelFile;
use lujie\upload\models\UploadModelFileQuery;
use yii\db\ActiveQueryInterface;

/**
 * Class UploadSavedFileSearch
 * @package lujie\upload\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadModelFileSearch extends UploadModelFile
{
    use SearchTrait;

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge($this->searchRules(), [
            [['ext'], 'safe'],
        ]);
    }

    /**
     * @return ActiveQueryInterface|UploadModelFileQuery
     */
    public function query(): ActiveQueryInterface
    {
        $query = $this->searchQuery();
        QueryHelper::filterValue($query, $this->getAttributes(['ext']));
        return $query;
    }
}
