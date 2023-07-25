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

    public $factoryClass = '';

    public $factoryNs = '';

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

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['ns', 'baseClass', 'openapiJsonPath'], 'required'],
            [['ns', 'baseClass', 'openapiJsonPath', 'factoryClass', 'factoryNs'], 'trim'],
            [['ns', 'baseClass', 'openapiJsonPath', 'factoryClass', 'factoryNs'], 'string'],
        ]);
    }

    /**
     * @return string[]
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'ns' => 'Namespace',
            'baseClass' => 'Base Class',
            'openapiJsonPath' => 'OpenAPI Json Path',
            'factoryClass' => 'Factory Class',
            'factoryNs' => 'Factory Namespace',
        ];
    }

    /**
     * @return string[]
     * @inheritdoc
     */
    public function requiredTemplates(): array
    {
        return $this->factoryClass ? ['openapi.php', 'factory.php'] : ['openapi.php'];
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
        if ($this->factoryClass) {
            $this->factoryNs = $this->factoryNs ?: $this->ns;
            $params = [
                'apiClassNames' => array_map(function ($openApiJsonFile) {
                    return $this->generateClassName($openApiJsonFile);
                }, $openApiJsonFiles),
            ];
            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->factoryNs)) . '/' . $this->factoryClass . '.php',
                $this->render('factory.php', $params)
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
