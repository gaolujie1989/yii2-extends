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

<?php foreach ($openapi['paths'] as $path => $pathMethods): ?>
    <?php foreach ($pathMethods as $httpMethod => $method): ?>
    <?php
        $apiMethod = $method['operationId'] ?? null;
        if (empty($apiMethod)) {
            continue;
        }
        $httpMethod = strtoupper($httpMethod);
        $method['tags'] = $method['tags'] ?? [];

        $params = ArrayHelper::map($method['parameters'] ?? [], 'name', static function (array $parameter) use ($openapi) {
            $ref = $parameter['$ref'] ?? null;
            if ($ref) {
                $refPath = strtr(substr($ref, 2), ['/' => '.']);
                $component = ArrayHelper::getValue($openapi, $refPath);
                $parameter = array_merge($component, $parameter);
            }
            return [
                'var' => '$' . lcfirst(Inflector::camelize($parameter['name'])),
                'name' => $parameter['name'],
                'required' => $parameter['required'] ?? false,
                'type' => $parameter['type'] ?? '',
                'description' => $parameter['description'] ?? '',
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

        $hasRequiredQuery = false;
        $queryParams = $params['query'] ?? [];
        if ($queryParams) {
            $docParams[] = '     * @param array $query';
            foreach ($queryParams as $name => $param) {
                if ($param['required']) {
                    $hasRequiredQuery = true;
                }
                $required = $param['required'] ? 'required' : 'optional';
                $docParams[] = "     *      - *{$name}* - {$param['type']} - {$required}";
                $docParams[] = "     *          - {$param['description']}";
            }
            $functionParams[] = $hasRequiredQuery ? 'array $query' : 'array $query = []';
        }

        $requestBody = $params['body'] ?? null;
        if ($requestBody) {
            $functionParams[] = 'array $data';
            $docParams[] = '     * @param array $data' . ' ' . ($requestBody['description'] ?? '');
            $ref = $requestBody['schema']['$ref'] ?? null;
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

        $returnType = ': void';
        $successResponse = null;
        foreach ($method['responses'] as $statusCode => $response) {
            if (str_starts_with($statusCode, '2')) {
                $successResponse = $response;
            }
        }
        $eachMethod = false;
        $batchMethod = false;
        if ($successResponse) {
            $ref = $successResponse['schema']['$ref'] ?? null;
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
                if ($httpMethod === 'GET' && isset($queryParams['nextToken'])) {
                    $eachMethod = 'each' . substr($method['operationId'], 3);
                    $batchMethod = 'batch' . substr($method['operationId'], 3);
                }
            }
        }

        $functionParams = implode(', ', $functionParams);
        $docParams = implode("\n", $docParams);

        $apiParams = [];
        if ($queryParams) {
            $apiParams[] = 'array_merge(["' . $apiUrl . '"], $query)';
        } else {
            $apiParams[] = '"' . $apiUrl . '"';
        }
        if ($httpMethod !== 'GET' || $requestBody) {
            $apiParams[] = "'{$httpMethod}'";
        }
        if ($requestBody) {
            $apiParams[] = '$data';
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
