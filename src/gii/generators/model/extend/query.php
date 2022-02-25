<?php /** @noinspection ALL */
/**
 * This is the template for generating the ActiveQuery class.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */
/* @var $className string class name */
/* @var $modelClassName string related model class name */

use yii\helpers\Inflector;

$modelFullClassName = $modelClassName;
if ($generator->ns !== $generator->queryNs) {
    $modelFullClassName = '\\' . $generator->ns . '\\' . $modelFullClassName;
}

$queryFields = [];
$queryBetweenFields = [];
$orderByFields = [];
$indexByFields = [];
$returnByFields = [];
foreach ($labels as $name => $label) {
    if (in_array($name, ['key', 'code', 'type', 'status'], true)
        || substr($name, -4) === '_key'
        || substr($name, -7) === '_status'
        || in_array(substr($name, -5), ['_code', '_type'], true)
        || in_array(substr($name, -3), ['_id', '_no'], true)
    ) {
        $queryFields[lcfirst(Inflector::camelize($name))] = $name;
    }
    if (in_array(substr($name, -3), ['_at'], true)
        || in_array(substr($name, -5), ['_date'], true)
    ) {
        $queryBetweenFields[lcfirst(Inflector::camelize($name)) . 'Between'] = $name;
    }
    if (in_array($name, ['position', 'priority'], true)
        || in_array(substr($name, -3), ['_id', '_at'], true)
        || in_array(substr($name, -5), ['_date'], true)
    ) {
        $orderByFields['orderBy' . Inflector::camelize($name)] = $name;
    }
    if (in_array($name, ['key', 'code'], true)
        || substr($name, -4) === '_key'
        || in_array(substr($name, -5), ['_code'], true)
        || in_array(substr($name, -3), ['_id', '_no'], true)
    ) {
        $indexByFields['indexBy' . Inflector::camelize($name)] = $name;
        $returnByFields['get' . Inflector::pluralize(Inflector::camelize($name))] = $name;
    }
}

echo "<?php\n";
?>

namespace <?= $generator->queryNs ?>;

use lujie\db\fieldQuery\behaviors\FieldQueryBehavior;

/**
 * This is the ActiveQuery class for [[<?= $modelFullClassName ?>]].
 *
 * @method <?= $className ?> id($id)
 * @method <?= $className ?> orderById($sort = SORT_ASC)
 * @method <?= $className ?> indexById()
 * @method int getId()
 * @method array getIds()
 *
<?php
foreach ($queryFields as $name => $field) {
    if (substr($field, -3) === '_no' || substr($field, -4) === '_key' || substr($field, -5) === '_code') {
        echo " * @method $className $name(\$$name, bool \$like = false)\n";
    } else {
        echo " * @method $className $name(\$$name)\n";
    }
}
echo " *\n";
foreach ($queryBetweenFields as $name => $field) {
    echo " * @method $className $name(\$from, \$to = null)\n";
}
echo " *\n";
foreach ($orderByFields as $name => $field) {
    echo " * @method $className $name(\$sort = SORT_ASC)\n";
}
echo " *\n";
foreach ($indexByFields as $name => $field) {
    echo " * @method $className $name()\n";
}
echo " *\n";
foreach ($returnByFields as $name => $field) {
    echo " * @method array $name()\n";
}
?>
 *
 * @method array|<?= $modelFullClassName ?>[] all($db = null)
 * @method array|<?= $modelFullClassName ?>|null one($db = null)
 * @method array|<?= $modelFullClassName ?>[] each($batchSize = 100, $db = null)
 *
 * @see <?= $modelFullClassName . "\n" ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->queryBaseClass, '\\') . "\n" ?>
{

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'fieldQuery' => [
                'class' => FieldQueryBehavior::class,
                'queryFields' => [
<?php foreach ($queryFields as $name => $field) {
    echo "                    '{$name}' => '{$field}',\n";
} ?>
<?php foreach ($queryBetweenFields as $name => $field) {
    echo "                    '{$name}' => ['{$field}' => 'BETWEEN'],\n";
} ?>
                ],
                'queryConditions' => [],
                'querySorts' => [
<?php foreach ($orderByFields as $name => $field) {
    echo "                    '{$name}' => '{$field}',\n";
} ?>
                ],
                'queryIndexes' => [
<?php foreach ($indexByFields as $name => $field) {
    echo "                    '{$name}' => '{$field}',\n";
} ?>
                ],
                'queryReturns' => [
<?php foreach ($returnByFields as $name => $field) {
    echo "                    '{$name}' => '{$field}',\n";
} ?>
                ]
            ]
        ];
    }

}
