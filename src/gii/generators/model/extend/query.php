<?php
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
foreach ($labels as $name => $label) {
    if (in_array(substr($name, -3), ['_id', '_no'], true)) {
        $queryFields[lcfirst(Inflector::camelize($name))] = $name;
    }
}

echo "<?php\n";
?>

namespace <?= $generator->queryNs ?>;

/**
 * This is the ActiveQuery class for [[<?= $modelFullClassName ?>]].
 *
 * @method <?= $className ?> id($id)
 * @method <?= $className ?> orderById($sort = SORT_ASC)
 * @method int getId()
 * @method array getIds()
 *
<?php foreach ($queryFields as $name => $field) {
    echo " * @method $className $name(\$$name)\n";
} ?>
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
                ]
            ]
        ];
    }

}
