<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

use lujie\charging\ChargeCalculatorInterface;
use lujie\charging\models\ChargePrice;
use lujie\data\loader\DataLoaderInterface;
use lujie\data\loader\DbDataLoader;
use lujie\data\loader\QueryDataLoader;
use yii\base\BaseObject;
use yii\db\BaseActiveRecord;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class CarrierInvoiceCalculator
 * @package ccship\charging\calculators
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CarrierInvoiceCalculator extends BaseObject implements ChargeCalculatorInterface
{
    /**
     * @var DataLoaderInterface
     */
    public $carrierItemLoader;

    /**
     * @var DataLoaderInterface|QueryDataLoader
     */
    public $carrierInvoiceLoader = [
        'class' => DbDataLoader::class,
        'table' => '{{%carrier_package}}',
        'db' => 'kiwiDataRepDB',
        'key' => 'tracking_no',
        'indexBy' => false,
    ];

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->carrierItemLoader = Instance::ensure($this->carrierItemLoader, DataLoaderInterface::class);
        $this->carrierInvoiceLoader = Instance::ensure($this->carrierInvoiceLoader, DataLoaderInterface::class);
    }

    /**
     * @param BaseActiveRecord $model
     * @param ChargePrice $chargePrice
     * @return ChargePrice
     * @inheritdoc
     */
    public function calculate(BaseActiveRecord $model, ChargePrice $chargePrice): ChargePrice
    {
        $chargePrice->price_table_id = 0;
        $chargePrice->price_cent = 0;
        $chargePrice->currency = '';
        $chargePrice->error = '';

        /** @var ?CarrierItem $carrierItem */
        $carrierItem = $this->carrierItemLoader->get($model);
        if ($carrierItem === null) {
            $chargePrice->error = 'Null CarrierItem';
            return $chargePrice;
        }

        $chargePrice->custom_type = $carrierItem->carrier;
        $chargePrice->setAttributes($carrierItem->additional);

        //because one tracking number maybe exist multi invoice item lines
        $carrierPackages = $this->getCarrierInvoicePrices($carrierItem);
        if (empty($carrierPackages)) {
            $chargePrice->error = 'CarrierInvoice not found';
            return $chargePrice;
        }

        $carrierPackage = reset($carrierPackages);
        $chargePrice->price_table_id = (int)($carrierPackage['carrier_package_id'] ?? $carrierPackage['id']);
        $chargePrice->price_cent = array_sum(ArrayHelper::getColumn($carrierPackages, 'package_price_cent'));
        $chargePrice->surcharge_cent = array_sum(ArrayHelper::getColumn($carrierPackages, 'additional_price_cent'));
        $chargePrice->currency = $carrierPackage['currency'];
        $chargePrice->note = implode(';', array_filter(ArrayHelper::getColumn($carrierPackages, 'note')));
        $chargePrice->additional = [
            'additionalServices' => ArrayHelper::getColumn($carrierPackages, 'additional_services'),
        ];
        return $chargePrice;
    }

    /**
     * @param CarrierItem $carrierItem
     * @return array
     * @inheritdoc
     */
    protected function getCarrierInvoicePrices(CarrierItem $carrierItem): array
    {
        if (empty($carrierItem->trackingNumbers)) {
            return [];
        }
        $carrierItem->trackingNumbers = array_map([$this, 'formatInvoiceTrackingNo'], $carrierItem->trackingNumbers);
        //for shipping_date may not be correctly
        if ($this->carrierInvoiceLoader instanceof DbDataLoader) {
            $this->carrierInvoiceLoader->condition = ['BETWEEN', 'shipping_date',
                date('Y-m-d', $carrierItem->shippedAt - 86400 * 30),
                date('Y-m-d', $carrierItem->shippedAt + 86400 * 60),
            ];
        }
        return $this->carrierInvoiceLoader->multiGet($carrierItem->trackingNumbers);
    }

    /**
     * @param string $trackingNo
     * @return string
     * @inheritdoc
     */
    public function formatInvoiceTrackingNo(string $trackingNo): string
    {
        $trackingNo = trim($trackingNo);
        if (strlen($trackingNo) === 12 && strpos($trackingNo, '50') === 0) {
            return substr($trackingNo, 0, 11);
        }
        return $trackingNo;
    }
}
