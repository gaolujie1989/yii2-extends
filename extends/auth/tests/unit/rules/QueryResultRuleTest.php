<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\tests\unit\rules;

use lujie\auth\rules\QueryResultRule;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\rbac\Item;

class QueryResultRuleTest extends \Codeception\Test\Unit
{
    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        $rule = new QueryResultRule();
        $rule->condition = ['version' => 'm000000_000000_base'];
        $query = (new Query())->from('migration');
        $dataProvider = new ActiveDataProvider();
        $dataProvider->query = clone $query;

        $this->assertTrue($query->count() > 1);
        $this->assertTrue($rule->execute(1, new Item(), ['result' => $query]));
        $this->assertEquals(1, $query->count());
        $this->assertTrue($rule->execute(1, new Item(), ['result' => $dataProvider]));
        $this->assertEquals(1, $dataProvider->query->count());
    }
}
