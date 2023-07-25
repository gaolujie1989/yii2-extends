<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\gii\generators\openapi;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;

/**
 * Class Generator
 * @package lujie\extend\gii\generators\openapi
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Generator extends \yii\gii\Generator
{
    public $ns = 'app\api';

    public $baseClass = '';

    public $openapiJsonPath = '';

    /**
     * @return string
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'OpenAPI Generator';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return 'This generator generates OpenAPI Client.';
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['ns', 'baseClass', 'openapiJsonPath'], 'trim'],
            [['ns', 'baseClass', 'openapiJsonPath'], 'required'],
            [['ns', 'baseClass', 'openapiJsonPath'], 'string'],
        ]);
    }

    public function attributeLabels(): array
    {
        return [
            'ns' => 'Namespace',
            'baseClass' => 'Base Class',
            'openapiJsonPath' => 'OpenAPI Json Path',
        ];
    }

    /**
     * @return string[]
     * @inheritdoc
     */
    public function requiredTemplates(): array
    {
        return ['openapi.php'];
    }

    /**
     * @return array|CodeFile[]
     * @inheritdoc
     */
    public function generate(): array
    {
        $files = [];

        $openApiPath = Yii::getAlias($this->openapiJsonPath);
        $openApiJsonFiles = FileHelper::findFiles($openApiPath, ['only' => ['*.json']]);
        sort($openApiJsonFiles);
        foreach ($openApiJsonFiles as $openApiJsonFile) {
            $apiClassName = $this->generateClassName($openApiJsonFile);
            $params = [
                'className' => $apiClassName,
                'openapi' => Json::decode(file_get_contents($openApiJsonFile)),
            ];
            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $apiClassName . '.php',
                $this->render('openapi.php', $params)
            );
        }
        return $files;
    }

    /**
     * @param string $openApiJsonFile
     * @return string
     * @inheritdoc
     */
    protected function generateClassName(string $openApiJsonFile): string
    {
        $className = basename($openApiJsonFile, '.json');
        $className = strtr($className, ['_oas3' => '']);
        return Inflector::camelize($className);
    }
}
