<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tests\unit\importers;

use lujie\sales\channel\importers\OttoCategoryImporter;
use yii\helpers\Json;

/**
 * Class OttoCategoryImporterTest
 * @package lujie\sales\channel\tests\unit\importers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoCategoryImporterTest extends \Codeception\Test\Unit
{
    public function testMe(): void
    {
        $categories = Json::decode(file_get_contents(__DIR__ . '/../fixtures/data/otto/categories.json'));
        $categories = $categories['categoryGroups'] ?? $categories;
        $categoryImporter = new OttoCategoryImporter();
        $categoryImporter->exchange($categories);
        $affectedRowCounts = $categoryImporter->getAffectedRowCounts();
        foreach ($affectedRowCounts as $key => $counts) {
            $totalCount = array_sum($counts);
            $this->assertTrue($totalCount > 0);
        }
    }
}