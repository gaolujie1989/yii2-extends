<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\currency\exchanging\swap\services;

use DateTime;
use Exchanger\Contract\ExchangeRate as ExchangeRateContract;
use Exchanger\Contract\ExchangeRateQuery;
use Exchanger\Exception\UnsupportedExchangeQueryException;
use Exchanger\HistoricalExchangeRateQuery;
use Exchanger\Service\HttpService;
use Exchanger\StringUtil;
use yii\base\UserException;

/**
 * Class Juhe
 * @package lib\exchanger\service
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Juhe extends HttpService
{
    private const URL = 'http://op.juhe.cn/onebox/exchange/currency?from={from}&to={to}&key={key}';

    /**
     * @return string
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'juhe';
    }

    /**
     * @param ExchangeRateQuery $exchangeQuery
     * @return ExchangeRateContract
     * @throws UnsupportedExchangeQueryException
     * @throws UserException
     * @inheritdoc
     */
    public function getExchangeRate(ExchangeRateQuery $exchangeQuery): ExchangeRateContract
    {
        if ($exchangeQuery instanceof HistoricalExchangeRateQuery) {
            throw new UnsupportedExchangeQueryException($exchangeQuery, $this);
        }
        $currencyPair = $exchangeQuery->getCurrencyPair();
        $url = strtr(self::URL, [
            '{from}' => $currencyPair->getBaseCurrency(),
            '{to}' => $currencyPair->getQuoteCurrency(),
            '{key}' => $this->options['access_key'],
        ]);

        $content = $this->request($url);
        $data = StringUtil::jsonToArray($content);

        if (isset($data['error_code']) && $data['error_code']) {
            throw new UserException("Error code: {$data['error_code']}, reason: {$data['reason']}");
        }

        if (empty($data['result'])) {
            throw new UserException("Empty result");
        }

        foreach ($data['result'] as $resultData) {
            if ($resultData['currencyF'] === $currencyPair->getBaseCurrency()
                && $resultData['currencyT'] === $currencyPair->getQuoteCurrency()) {
                $date = new DateTime($resultData['updateTime']);
                return $this->createRate($currencyPair, $resultData['result'], $date);
            }
        }

        throw new UserException("No matched result");
    }

    /**
     * @inheritdoc
     */
    public function supportQuery(ExchangeRateQuery $exchangeQuery): bool
    {
        return true;
    }
}
