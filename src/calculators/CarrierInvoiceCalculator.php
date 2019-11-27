<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\calculators;

use lujie\charging\ChargeCalculatorInterface;
use lujie\charging\models\ChargePrice;
use lujie\data\loader\DataLoaderInterface;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\db\Connection;
use yii\db\Query;
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
     * @var string
     */
    public $carrierPackageTable = '{{%carrier_package}}';

    /**
     * @var string
     */
    public $carrierPackageDB = 'kiwiDataRepDB';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->carrierItemLoader = Instance::ensure($this->carrierItemLoader, DataLoaderInterface::class);
        $this->carrierPackageDB = Instance::ensure($this->carrierPackageDB, Connection::class);
    }

    /**
     * @param BaseActiveRecord $model
     * @param ChargePrice $chargePrice
     * @return ChargePrice
     * @inheritdoc
     */
    public function calculate(BaseActiveRecord $model, ChargePrice $chargePrice): ChargePrice
    {
        /** @var CarrierItem $carrierItem */
        $carrierItem = $this->carrierItemLoader->get($model);
        $chargePrice->custom_type = $carrierItem->carrier;
        $chargePrice->setAttributes($carrierItem->additional);

        $carrierPackages = $this->getCarrierInvoicePrices($carrierItem);
        if (empty($carrierPackages)) {
            $chargePrice->price_table_id = 0;
            $chargePrice->price_cent = 0;
            $chargePrice->currency = '';
        } else {
            $chargePrice->price_table_id = $carrierPackages[0]['carrier_package_id'] ?? $carrierPackages[0]['id'] ?? 1;
            $chargePrice->price_cent = array_sum(ArrayHelper::getColumn($carrierPackages, 'total_price_cent'));
            $chargePrice->currency = $carrierPackages[0]['currency'];
        }

        return $chargePrice;
    }

    /**
     * @param CarrierItem $carrierItem
     * @return array
     * @inheritdoc
     */
    public function getCarrierInvoicePrices(CarrierItem $carrierItem): array
    {
        return (new Query())->from($this->carrierPackageTable)
            ->andWhere(['carrier' => $carrierItem->carrier])
            ->andWhere(['tracking_no' => $carrierItem->trackingNumbers])
            ->all($this->carrierPackageDB);
    }
}
