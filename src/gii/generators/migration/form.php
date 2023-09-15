<?php
/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var yii\gii\generators\controller\Generator $generator */

echo $form->field($generator, 'ns');
echo $form->field($generator, 'baseClass');
echo $form->field($generator, 'dataFilePath');
echo $form->field($generator, 'detectRows');
echo $form->field($generator, 'skipEmptyContentColumns')->checkbox();
