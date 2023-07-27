<?php /** @noinspection ALL */
/**
 * This is the template for generating the openapi class.
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $generator \lujie\extend\gii\generators\openapi\Generator */
/* @var $openapi array openapi */
/* @var $className string Class Name */

$serverUrls = ArrayHelper::map($openapi['servers'], 'description', static function (array $server) {
    $variables = [];
    foreach ($server['variables'] as $key => $variable) {
        $variables['{' . $key . '}'] = $variable['default'];
    }
    return strtr($server['url'], $variables);
});

$apiBaseUrl = $serverUrls['Production'] ?? '';

$className = $className ?: Inflector::camelize($openapi['info']['title']);

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
use Iterator;

/**
* This class is autogenerated by the OpenAPI gii generator
* @description <?= $openapi['info']['description'] . "\n" ?>
*/
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{

    public $apiBaseUrl = '<?= $apiBaseUrl ?>';

<?php foreach ($openapi['paths'] as $path => $pathMethods): ?>
    <?php foreach ($pathMethods as $httpMethod => $method): ?>
    <?php
        $apiMethod = $method['operationId'] ?? null;
        if (empty($apiMethod)) {
            continue;
        }
        $httpMethod = strtoupper($httpMethod);

        $params = ArrayHelper::map($method['parameters'] ?? [], 'name', static function (array $parameter) {
            return [
                'var' => '$' . lcfirst(Inflector::camelize($parameter['name'])),
                'name' => $parameter['name'],
                'required' => $parameter['required'],
                'type' => $parameter['schema']['type'],
                'description' => $parameter['description'],
            ];
        }, 'in');

        $pathReplaces = [];
        $functionParams = [];
        $docParams = [];

        $pathParams = $params['path'] ?? [];
        foreach ($pathParams as $name => $pathParam) {
            $pathReplaces['{' . $name . '}'] = '{' . $pathParam['var'] . '}';

            $functionParams[] = $pathParam['required']
                ? $pathParam['type'] . ' ' . $pathParam['var']
                : '?' . $pathParam['type'] . ' ' . $pathParam['var'] . ' = null';

            $docParams[] = '     * @param ' . $pathParam['type'] . ' ' . $pathParam['var'] . ' ' . $pathParam['description'];
        }
        $apiUrl = strtr($path, $pathReplaces);

        $queryParams = $params['query'] ?? [];
        if ($queryParams) {
            $functionParams[] = 'array $query';
            $docParams[] = '     * @param array $query';
            foreach ($queryParams as $name => $param) {
                $required = $param['required'] ? 'required' : 'optional';
                $docParams[] = "     *      - *{$name}* - {$param['type']} - {$required}";
                $docParams[] = "     *          - {$param['description']}";
            }
        }

        $requestBody = $method['requestBody'] ?? null;
        if ($requestBody) {
            $functionParams[] = 'array $data';
            $docParams[] = '     * @param array $data' . ' ' . ($requestBody['description'] ?? '');
            $ref = $requestBody['content']['application/json']['schema']['$ref'] ?? null;
            if ($ref) {
                $refPath = strtr(substr($ref, 2), ['/' => '.']);
                $component = ArrayHelper::getValue($openapi, $refPath);
                $properties = $component['properties'] ?? [];
                foreach ($properties as $name => $property) {
                    $type = $property['type'] ?? '';
                    $docParams[] = "     *      - *{$name}* - {$type}";
                    $description = $property['description'] ?? '';
                    if ($description) {
                        $docParams[] = "     *          - {$description}";
                    }
                }
            }
        }

        $isJsonData = true;
        $hasEbayCustomHeaders = false;
        $headerParams = $params['header'] ?? [];
        if ($headerParams) {
            $docParams[] = '     * @param array $headers';
            foreach ($headerParams as $name => $param) {
                if ($name === 'Content-Type' && !str_contains($param['description'], 'application/json')) {
                    $isJsonData = false;
                }
                if (str_starts_with($name, 'X-EBAY-')) {
                    $hasEbayCustomHeaders = true;
                }
                $required = $param['required'] ? 'required' : 'optional';
                $docParams[] = "     *      - *{$name}* - {$param['type']} - {$required}";
                $docParams[] = "     *          - {$param['description']}";
            }
            $functionParams[] = $hasEbayCustomHeaders ? 'array $headers' : 'array $headers = []';
        }

        $returnType = ': void';
        $successResponse = $method['responses']['200'] ?? null;
        $eachMethod = false;
        $batchMethod = false;
        if ($successResponse) {
            $ref = $successResponse['content']['application/json']['schema']['$ref'] ?? null;
            if ($ref) {
                $returnType = ': array';
                $docParams[] = '     * @return array';
                $refPath = strtr(substr($ref, 2), ['/' => '.']);
                $component = ArrayHelper::getValue($openapi, $refPath);
                $properties = $component['properties'] ?? [];
                foreach ($properties as $name => $property) {
                    $type = $property['type'] ?? '';
                    $docParams[] = "     *      - *{$name}* - {$type}";
                    $description = $property['description'] ?? '';
                    if ($description) {
                        $docParams[] = "     *          - {$description}";
                    }
                }
                if ($httpMethod === 'GET' && isset($properties['limit'])) {
                    $eachMethod = 'each' . substr($method['operationId'], 3);
                    $batchMethod = 'batch' . substr($method['operationId'], 3);
                }
            }
        }

        $functionParams = implode(', ', $functionParams);
        $docParams = implode("\n", $docParams);
        if (!$isJsonData) {
            $docParams = strtr($docParams, ['array $data' => 'string $data']);
            $functionParams = strtr($functionParams, ['array $data' => 'string $data']);
        }

        $apiParams = [];
        if ($queryParams) {
            $apiParams[] = 'array_merge(["' . $apiUrl . '"], $query)';
        } else {
            $apiParams[] = '"' . $apiUrl . '"';
        }
        if ($httpMethod !== 'GET' || $requestBody || $headerParams) {
            $apiParams[] = "'{$httpMethod}'";
        }
        if ($requestBody) {
            $apiParams[] = '$data';
        }
        if ($headerParams) {
            if (!$requestBody) {
                $apiParams[] = '[]';
            }
            $apiParams[] = '$headers';
        }
        $apiParams = implode(', ', $apiParams);
    ?>
    <?php if ($eachMethod): ?>

    /**
     * @description <?= $method['description'] . "\n" ?>
     * @tag <?= implode(',', $method['tags']) . "\n" ?>
<?= strtr($docParams, ['@return array' => '@return Iterator']) . "\n" ?>
     */
    public function <?= $eachMethod ?>(<?= $functionParams ?>): Iterator
    {
        return $this->eachInternal('<?= $apiMethod ?>', func_get_args());
    }
    <?php endif; ?>
    <?php if ($batchMethod): ?>

    /**
     * @description <?= $method['description'] . "\n" ?>
     * @tag <?= implode(',', $method['tags']) . "\n" ?>
<?= strtr($docParams, ['@return array' => '@return Iterator']) . "\n" ?>
     */
    public function <?= $batchMethod ?>(<?= $functionParams ?>): Iterator
    {
        return $this->batchInternal('<?= $apiMethod ?>', func_get_args());
    }
    <?php endif; ?>

    /**
     * @description <?= $method['description'] . "\n" ?>
     * @tag <?= implode(',', $method['tags']) . "\n" ?>
<?= $docParams . "\n" ?>
     */
    public function <?= $apiMethod ?>(<?= $functionParams ?>)<?= $returnType . "\n" ?>
    {
        <?= $returnType === ': void' ? '' : 'return ' ?>$this->api(<?= $apiParams ?>);
    }
    <?php endforeach; ?>
<?php endforeach; ?>

}
