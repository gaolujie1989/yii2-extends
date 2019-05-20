<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\storage;

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
    public function exportToFile(string $file, array $data): void
    {
        file_put_contents($file, "<?php\n\nreturn " . VarDumper::export($data) . ';');
    }
}
