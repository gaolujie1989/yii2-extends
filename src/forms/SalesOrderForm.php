<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\order\center\forms;


use lujie\ar\relation\behaviors\RelationSavableBehavior;
use lujie\common\address\forms\AddressForm;
use lujie\data\loader\DataLoaderInterface;
use lujie\sales\order\center\models\Customer;
use lujie\sales\order\center\models\SalesOrder;
use lujie\sales\order\center\Module;
use yii\db\ActiveQuery;
use yii\di\Instance;

/**
 * Class SalesOrderSearch
 *
 * @method setRelation(string $name, $data): void
 *
 * @package lujie\sales\order\center\searches
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SalesOrderForm extends SalesOrder
{
    /**
     * @var DataLoaderInterface
     */
    public $currencyExchangeRateLoader;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->currencyExchangeRateLoader = Instance::ensure($this->currencyExchangeRateLoader, DataLoaderInterface::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        return array_merge($rules, [
            [['customer', 'shippingAddress', 'billingAddress', 'orderAmount', 'orderItems'], 'safe'],
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'relationSave' => [
                'class' => RelationSavableBehavior::class,
                'relations' => [
                    'customer', 'shippingAddress', 'billingAddress',
                    'orderItems', 'orderAmount', 'systemCurrencyOrderAmount'],
                'relationIndex' => [
                    'orderItems' => 'item_id'
                ]
            ]
        ];
    }

    /**
     * @param array $data
     * @inheritdoc
     */
    public function setOrderAmount(array $data): void
    {
        $this->setRelation('orderAmount', $data);

        $systemCurrency = Module::getSystemCurrency();
        if ($systemCurrency === $this->currency) {
            return;
        }
        $rateKey = $this->currency . '2' . $systemCurrency . '@' . $this->ordered_at;
        $rate = $this->currencyExchangeRateLoader->get($rateKey);
        $systemCurrencyOrderAmountData = array_merge($data, [
            'currency' => $systemCurrency,
            'exchange_rate' => $rate,
        ]);
        $systemCurrencyOrderAmountData['exchange_rate'] = $rate;
        $amountAttributes = [
            'item_total_cent', 'discount_total_cent', 'subtotal_cent',
            'shipping_total_cent', 'tax_total_cent', 'grand_total_cent',
        ];
        foreach ($amountAttributes as $amountAttribute) {
            if (isset($data[$amountAttribute])) {
                $systemCurrencyOrderAmountData[$amountAttribute] = round($data[$amountAttribute] * $rate);
            }
        }
        $this->setRelation('systemCurrencyOrderAmount', $systemCurrencyOrderAmountData);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getCustomer(): ActiveQuery
    {
        if ($this->customer_email && $this->customer_id === null) {
            return $this->hasOne(Customer::class, ['email' => 'customer_email']);
        }
        return parent::getCustomer();
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getShippingAddress(): ActiveQuery
    {
        return $this->hasOne(AddressForm::class, ['address_id' => 'shipping_address_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getBillingAddress(): ActiveQuery
    {
        return $this->hasOne(AddressForm::class, ['address_id' => 'billing_address_id']);
    }
}
