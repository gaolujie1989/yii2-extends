<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\file;

use lujie\extend\file\FileArchiver;

/**
 * Class FileLogArchiverTest
 * @package lujie\extend\tests\unit\log
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileArchiverTest extends \Codeception\Test\Unit
{
    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        $archiver = new FileArchiver();
        $archiver->archive();
    }
}
