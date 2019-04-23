<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


use yii\helpers\VarDumper;

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
