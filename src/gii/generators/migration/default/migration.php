<?php /** @noinspection ALL */
/**
 * This is the template for generating the migration class.
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $generator \lujie\extend\gii\generators\openapi\Generator */
/* @var $className string Class Name */
/* @var $tableName string Table Name */
/* @var $columns array Columns */
/* @var $indexes array Indexes */

echo "<?php\n";
?>

class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
    public $tableName = '{{%<?= $tableName ?>}}';

    public function safeUp(): void
    {
        $this->createTable($this->tableName, [
<?php foreach ($columns as $name => $column): ?>
            '<?= $name ?>' => $this-><?= $column ?>,
<?php endforeach; ?>

<?php foreach ($indexes as $name => $indexKeys): ?>
            $this->createIndex('<?= $name ?>', $this->tableName, ['<?= implode("', '", $indexKeys) ?>']);
<?php endforeach; ?>
    ]);
    }
}
