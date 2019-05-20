<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\storage;


interface FileExporterInterface
{
    public function exportToFile(string $file, array $data): void;
}
