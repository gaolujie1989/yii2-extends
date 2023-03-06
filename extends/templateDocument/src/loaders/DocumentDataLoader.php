<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\loaders;

use lujie\data\loader\BaseDataLoader;
use lujie\template\document\models\DocumentFile;

/**
 * Class DocumentDataLoader
 * @package lujie\template\document\loaders
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DocumentDataLoader extends BaseDataLoader
{
    /**
     * @var string
     */
    public $documentType;

    /**
     * @param mixed $key
     * @return array|mixed|null
     * @inheritdoc
     */
    public function get($key)
    {
        $documentFile = DocumentFile::find()
            ->documentType($this->documentType)
            ->referenceId($key)
            ->one();

        return $documentFile->document_data ?? null;
    }
}
