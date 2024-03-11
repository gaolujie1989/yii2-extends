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

$className = $className ?: Inflector::camelize($openapi['info']['title']) . 'Const';

$apiConstants = [];
$definitions = $openapi['definitions'] ?? [];
foreach ($definitions as $definitionKey => $definition) {
    $properties = $definition['properties'] ?? null;
    if (empty($properties)) {
        continue;
    }
    foreach ($properties as $propertyKey => $property) {
        $propertyEnums = $property['enum'] ?? null;
        if (empty($propertyEnums)) {
            continue;
        }
        $propertyConstants = [];
        $constantPrefix = strtoupper(Inflector::underscore($definitionKey)) . '_' . strtoupper(Inflector::underscore($propertyKey)) . '_';
        foreach ($propertyEnums as $propertyEnumValue) {
            if (empty($propertyEnumValue)) {
                continue;
            }

            $propertyEnumKey = strtr(trim(preg_replace('/[^a-zA-Z0-9]/', '_', $propertyEnumValue), '_'), ['__' => '_']);
            if ($propertyEnumKey[0] === strtoupper($propertyEnumKey[0]) && $propertyEnumKey !== strtoupper($propertyEnumKey)) {
                $propertyEnumKey = strtoupper(Inflector::underscore($propertyEnumKey));
            } else {
                $propertyEnumKey = strtoupper(Inflector::underscore(strtolower($propertyEnumKey)));
            }
            $propertyConstants[$constantPrefix . $propertyEnumKey] = $propertyEnumValue;
        }
        $apiConstants[$constantPrefix] = $propertyConstants;
    }
}

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

/**
* This class is autogenerated by the OpenAPI gii generator
*/
class <?= $className . "\n" ?>
{
<?php foreach ($apiConstants as $propertyConstants): ?>
    <?php foreach ($propertyConstants as $constantName => $constantValue): ?>
    public const <?= $constantName ?> = '<?= $constantValue ?>';
    <?php endforeach; ?>
    <?= "\n" ?>
<?php endforeach; ?>
}
