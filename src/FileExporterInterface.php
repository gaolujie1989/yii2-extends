<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;


interface FileExporterInterface
{
    public function exportToFile(string $file, array $data);
}
