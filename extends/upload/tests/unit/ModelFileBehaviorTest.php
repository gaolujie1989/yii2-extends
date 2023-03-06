<?php

namespace lujie\upload\tests\unit;

use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\upload\behaviors\ModelFileBehavior;
use lujie\upload\models\UploadModelFileQuery;
use lujie\upload\tests\unit\fixtures\UploadModelFileFixture;

class ModelFileBehaviorTest extends \Codeception\Test\Unit
{
    protected function _after()
    {
    }

    protected function _before()
    {
    }

    public function _fixtures(): array
    {
        return [
            'file' => UploadModelFileFixture::class
        ];
    }

    // tests
    public function testMe(): void
    {
        $testOrder = new TestOrder(['test_order_id' => 1]);
        $testOrder->attachBehavior('modelFiles', [
            'class' => ModelFileBehavior::class,
            'modelFileTypes' => [
                'xxxImages' => 'MODEL_XXX_IMAGE',
                'yyyFiles' => 'MODEL_YYY_FILE'
            ]
        ]);

        $xxxImageQuery = $testOrder->getXxxImages();
        $this->assertInstanceOf(UploadModelFileQuery::class, $xxxImageQuery);

        $yyyImageQuery = $testOrder->getYyyFiles();
        $this->assertInstanceOf(UploadModelFileQuery::class, $yyyImageQuery);

        $this->assertCount(1, $testOrder->xxxImages);
        $this->assertEquals(1, $testOrder->xxxImages[0]->upload_model_file_id);

        $this->assertCount(1, $testOrder->yyyFiles);
        $this->assertEquals(2, $testOrder->yyyFiles[0]->upload_model_file_id);

        $this->assertCount(2, $testOrder->files);
        $this->assertCount(1, $testOrder->files['xxxImages']);
        $this->assertEquals(1, $testOrder->files['xxxImages'][0]->upload_model_file_id);

        $this->assertCount(1, $testOrder->files['yyyFiles']);
        $this->assertEquals(2, $testOrder->files['yyyFiles'][0]->upload_model_file_id);
    }
}
