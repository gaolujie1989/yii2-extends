<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\amazon\sp\controllers\console;

use Symfony\Component\DomCrawler\Crawler;
use Yii;
use yii\console\Controller;
use yii\helpers\Json;

/**
 * Class SwaggerController
 * @package lujie\amazon\sp\controllers\console
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SwaggerController extends Controller
{
    /**
     * @param string $html
     * @return array
     * @inheritdoc
     */
    public function actionGenerate(): void
    {
        $folder = Yii::getAlias('@lujie/amazon/sp/swagger/html');
        $files = glob($folder . '/*.html');
        foreach ($files as $file) {
            $swaggerModel = $this->actionGenerateFromHtml(file_get_contents($file));
            $jsonFile = strtr($file, ['html' => 'json']);
            file_put_contents($jsonFile, Json::encode($swaggerModel));
        }
    }

    /**
     * @return bool
     * @inheritdoc
     */
    public function actionGenerateFromHtml(string $html): array
    {
        $crawler = new Crawler($html);
        $title = $crawler->filterXPath('//*[@id="project"]')->text();
        $version = $crawler->filterXPath('//*[@id="api-_"]/div[1]')->text();
        $description = $crawler->filterXPath('//*[@id="api-_"]/div[2]')->text();
        $info = [
            'title' => $title,
            'version' => $version,
            'description' => $description,
        ];

        $definitions = [];
        preg_match_all('/defs\["(\w+)"].?= ({[^;]+});/', $html, $matches);
        foreach ($matches[1] as $index => $definitionName) {
            $definition = Json::decode($matches[2][$index]);
            $definitions[$definitionName] = $definition;
        }

        $paths = [];
        $selections = $crawler->filterXPath('//*[@id="sections"]//section//article');
        foreach ($selections as $selection) {
            $selection = new Crawler($selection);
            $operationId = $selection->filterXPath('//h1')->text();
            $description = $selection->filterXPath('//p[@class="marked"]')->text();
            $httpMethod = $selection->filterXPath('//pre')->attr('data-type');
            $httpPath = $selection->filterXPath('//pre/code')->text();

            $parameters = [];
            $parameterNames = $selection->filterXPath('//*[@class="methodsubtabletitle"]');
            $parameterTables = $selection->filterXPath('//table[@id="methodsubtable"]');
            $parameterIns = [];
            foreach ($parameterNames as $index => $parameterName) {
                $parameterName = new Crawler($parameterName);
                $parameterIns[$index] = strtolower(trim(strtr($parameterName->text(), ['parameters' => ''])));
            }
            foreach ($parameterTables as $index => $parameterTable) {
                $parameterIn = $parameterIns[$index];
                $parameterTable = new Crawler($parameterTable);
                $parameterRows = $parameterTable->filterXPath('//tr');

                foreach ($parameterRows as $parameterRow) {
                    $parameterRow = new Crawler($parameterRow);
                    if (!$parameterRow->filterXPath('//td')->count()) {
                        continue;
                    }
                    $parameterName = $parameterRow->filterXPath('//td[1]')->text();
                    $parameterName = trim($parameterName, "* \n\r\t\v\0");
                    if ($parameterName === 'body') {
                        preg_match('/var schemaWrapper = ({[^;]+});/', $parameterRow->html(), $matches);
                        $parameter = Json::decode($matches[1]);
                    } else {
                        $parameterTypeNode = $parameterRow->filterXPath('//*[@class="json-schema-view"]//*[@class="type"]');
                        $parameterDescNode = $parameterRow->filterXPath('//*[@class="json-schema-view"]//*[@class="inner description"]');
                        $parameterRequiredNode = $parameterRow->filterXPath('//*[@class="json-schema-view"]//*[@class="inner required"]');
                        $parameterType = $parameterTypeNode->count() ? $parameterTypeNode->text() : null;
                        $parameterDescription = $parameterDescNode->count() ? $parameterTypeNode->text() : null;
                        $parameterRequired = $parameterRequiredNode->count() > 0;
                        $parameter = [
                            'name' => $parameterName,
                            'in' => $parameterIn,
                            'description' => $parameterDescription,
                            'required' => $parameterRequired,
                            'type' => strtolower($parameterType),
                        ];
                    }
                    $parameters[] = $parameter;
                }
            }

            $responses = [];
            $schemaLinks = $selection->filterXPath('//ul[@class="nav nav-tabs nav-tabs-examples"]/li[@class="active"]/a');
            foreach ($schemaLinks as $schemaLink) {
                $schemaLink = new Crawler($schemaLink);
                $id = $schemaLink->attr('href');
                if (!str_starts_with($id, '#responses-')) {
                    continue;
                }
                $id = substr($id, 1);
                preg_match('/\d{3}/', $id, $matches);
                $responseCode = $matches[0];
                $responseCrawler = $selection->filterXPath(strtr('//*[@id="{id}"]', ['{id}' => $id]));
                preg_match('/var schemaWrapper = ({[^;]+});/', $responseCrawler->html(), $matches);
                $response = Json::decode($matches[1]);
                $responses[$responseCode] = $response;
            }

            $paths[$httpPath][$httpMethod] = [
                'operationId' => $operationId,
                'description' => $description,
                'parameters' => $parameters,
                'responses' => $responses,
            ];
        }
        return [
            'swagger' => '2.0',
            'info' => $info,
            'host' => 'sellingpartnerapi-na.amazon.com',
            'schemes' => [
                'https'
            ],
            'consumes' => [
                "application/json"
            ],
            'produces' => [
                "application/json"
            ],
            'paths' => $paths,
            'definitions' => $definitions,
        ];
    }
}
