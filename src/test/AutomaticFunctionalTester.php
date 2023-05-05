<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\test;

use Codeception\Module\Yii2;
use PHPUnit\Framework\Assert;
use yii\helpers\Json;

/**
 * Class ModelAutomaticUnitTester
 * @package lujie\extend\test
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class AutomaticFunctionalTester extends BaseAutomaticTester
{
    /**
     * @var Yii2
     */
    public $yii2;

    /**
     * @var string
     */
    public $url;

    /**
     * @param string $method
     * @param string $uri
     * @param array $parameters
     * @param array $files
     * @param array $server
     * @param $content
     * @return mixed|null
     * @throws \Codeception\Exception\ExternalUrlException
     * @throws \Codeception\Exception\ModuleException
     * @inheritdoc
     */
    protected function api(string $method, string $uri, array $parameters = [], array $files = [], array $server = [], $content = null)
    {
        $response = $this->yii2->_request($method, $uri, $parameters, $files, $server, $content);
        return Json::decode($response);
    }

    /**
     * @return array
     * @throws \Codeception\Exception\ExternalUrlException
     * @throws \Codeception\Exception\ModuleException
     * @inheritdoc
     */
    protected function testWithEmptyValuesAndReturnErrors(): array
    {
        $response = $this->api('POST', $this->url, []);
        Assert::assertEquals(422, $response['status']);
        return $response['data']['errors'] ?? [];
    }

    /**
     * @param array $invalidValues
     * @return array
     * @throws \Codeception\Exception\ExternalUrlException
     * @throws \Codeception\Exception\ModuleException
     * @inheritdoc
     */
    protected function testWithInvalidValuesAndReturnErrors(array $invalidValues): array
    {
        $response = $this->api('POST', $this->url, $invalidValues);
        Assert::assertEquals(422, $response['status']);
        return $response['data']['errors'] ?? [];
    }

    /**
     * @param array $validValues
     * @return array
     * @throws \Codeception\Exception\ExternalUrlException
     * @throws \Codeception\Exception\ModuleException
     * @inheritdoc
     */
    protected function testWithValidValuesAndReturnSavedValues(array $validValues): array
    {
        $response = $this->api('POST', $this->url, $validValues);
        Assert::assertEquals(200, $response['status']);
        return $response['data'] ?? [];
    }
}
