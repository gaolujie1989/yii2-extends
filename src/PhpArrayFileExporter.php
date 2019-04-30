<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use yii\helpers\VarDumper;

/**
 * Class PhpArrayFileExporter
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PhpArrayFileExporter implements FileExporterInterface
{
    /**
     * @param string $file
     * @param array $data
     * @inheritdoc
     */
    public function exportToFile(string $file, array $data)
    {
        file_put_contents($file, "<?php\n\n" . VarDumper::export($data));
    }
}
