<?php /** @noinspection ALL */
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $properties array list of properties (property => [type, name. comment]) */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

unset(
    $properties['created_at'],
    $properties['created_by'],
    $properties['updated_at'],
    $properties['updated_by'],
    $labels['created_at'],
    $labels['created_by'],
    $labels['updated_at'],
    $labels['updated_by']
);
$createdUpdatedKeys = ['created_at', 'created_by', 'updated_at', 'updated_by'];
foreach ($rules as $key => $rule) {
    foreach ($createdUpdatedKeys as $createdUpdatedKey) {
        if (strpos($rule, ", '{$createdUpdatedKey}'") !== false) {
            $rules[$key] = $rule = strtr($rule, [", '{$createdUpdatedKey}'" => '']);
        }
    }
}

$useTrait = strpos($generator->baseClass, 'yii') !== false;

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

<?php if ($useTrait): ?>
  use lujie\alias\behaviors\AliasBehaviorTrait;
  use lujie\extend\db\AliasFieldTrait;
  use lujie\extend\db\DbConnectionTrait;
  use lujie\extend\db\DeleteTrait;
  use lujie\extend\db\RowPrepareTrait;
  use lujie\extend\db\SaveTrait;
  use lujie\extend\db\TraceableBehaviorTrait;
  use lujie\extend\db\TransactionTrait;
<?php endif; ?>
use Yii;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($properties as $property => $data): ?>
 * @property <?= "{$data['type']} \${$property}"  . ($data['comment'] ? ' ' . strtr($data['comment'], ["\n" => ' ']) : '') . "\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 *
 * @method array|<?= $className ?>|null findOne($condition)
 * @method array|<?= $className ?>[] findAll($condition)
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
<?php if ($useTrait): ?>
  use RowPrepareTrait;
  use TraceableBehaviorTrait, AliasBehaviorTrait, AliasFieldTrait;
  use SaveTrait, DeleteTrait, TransactionTrait, DbConnectionTrait;

<?php endif; ?>
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if (false && $generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        ") ?>];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>(): <?= $relationsClassHints[$name] . "\n" ?>
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * {@inheritdoc}
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find(): <?= $queryClassFullName . "\n" ?>
    {
        return new <?= $queryClassFullName ?>(static::class);
    }
<?php endif; ?>
}
