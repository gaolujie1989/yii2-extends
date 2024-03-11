<?php
/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var yii\gii\generators\controller\Generator $generator */

echo $form->field($generator, 'ns');
echo $form->field($generator, 'baseClass');
echo $form->field($generator, 'openapiJsonPath');
echo $form->field($generator, 'factoryClass');
echo $form->field($generator, 'factoryNs');
echo $form->field($generator, 'generateConstant')->checkbox();
