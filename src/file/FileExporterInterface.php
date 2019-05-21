<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file;

interface FileExporterInterface
{
    public function exportToFile(string $file, array $data): void;
}
