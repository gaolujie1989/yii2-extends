<?php

namespace lujie\charging;

use lujie\charging\forms\ShippingTableForm;
use lujie\charging\searches\ShippingTableSearch;
use lujie\extend\helpers\MockHelper;
use PHPUnit\Framework\Assert;
use tests\Yii2Cest;

/**
 * Class ShippingTableCest
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableCest extends Yii2Cest
{
    /**
     * @var string
     */
    public $url = '/shipping-tables';

    /**
     * @var string
     */
    public $formClass = ShippingTableForm::class;

    /**
     * @var string
     */
    public $searchClass = ShippingTableSearch::class;

    /**
     * @var int[]
     */
    public $mockConfig = ['decimalLength' => 1];

    /**
     * @throws \Codeception\Exception\ExternalUrlException
     * @throws \Codeception\Exception\ModuleException
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function tryToTestSearch(): void
    {
        /** @var ShippingTableForm $form */
        $form = new $this->formClass();
        $mockData = MockHelper::mockData($form->rules(), true);
        $form->setAttributes($mockData);
        Assert::assertTrue($form->save(false));

        $notEmptyQueries = [
            ['activeAt' => $form->started_at + 1],
            ['activeAt' => $form->ended_at - 1],
        ];
        $emptyQueries = [
            ['activeAt' => $form->started_at - 1],
            ['activeAt' => $form->ended_at + 1],
        ];
        $this->yii2Helper->testGetList($this->url, $notEmptyQueries, $emptyQueries);
    }
}
