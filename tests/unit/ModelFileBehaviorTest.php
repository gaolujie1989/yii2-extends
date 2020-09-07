<?php

namespace lujie\upload\tests\unit;

use lujie\extend\tests\unit\fixtures\models\Migration;
use lujie\upload\behaviors\ModelFileBehavior;

class ModelFileBehaviorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testMe(): void
    {
        $migration = new Migration();
        $migration->attachBehavior('modelFiles', [
            'class' => ModelFileBehavior::class,
            'modelFileTypes' => [
                'xxxImages' => 'MODEL_XXX_IMAGE',
                'yyyFiles' => 'MODEL_YYY_FILE'
            ]
        ]);

    }
}
