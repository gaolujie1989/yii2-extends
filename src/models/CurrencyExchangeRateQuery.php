<?php

namespace lujie\currency\exchanging\models;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[CurrencyExchangeRate]].
 *
 * @method CurrencyExchangeRateQuery fromTo($from, $to)
 * @method CurrencyExchangeRateQuery date($date)
 * @method CurrencyExchangeRateQuery beforeDate($date)
 *
 * @method CurrencyExchangeRateQuery orderByDate($sort = SORT_ASC)
 *
 * @method float getRate()
 *
 * @method CurrencyExchangeRate[]|array all($db = null)
 * @method CurrencyExchangeRate|array|null one($db = null)
 *
 * @see CurrencyExchangeRate
 */
class CurrencyExchangeRateQuery extends \yii\db\ActiveQuery
{
    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
                    'fromTo' => ['from', 'to'],
                    'date' => ['date'],
                    'beforeDate' => ['date' => '<='],
                ],
                'querySorts' => [
                    'orderByDate' => ['date'],
                ],
                'queryReturns' => [
                    'getRate' => ['rate', FieldQueryBehavior::RETURN_SCALAR]
                ],
            ]
        ]);
    }
}
