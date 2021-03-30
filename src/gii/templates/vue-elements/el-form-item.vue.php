<?php /** @noinspection ALL */

/**
 * @copyright Copyright (c) 2019
 */

use lujie\extend\gii\VueViewGenerator;
use yii\base\View;
use yii\helpers\Inflector;

/**
 * @var View $this
 * @var string $modelName
 * @var string $field
 * @var string $type
 * @var string $optionsName
 */

/** @var VueViewGenerator $generator */
$generator = $this->context;
$messageCategory = $generator->messageCategory;
$fieldVarName = Inflector::variablize($field);
$fieldLabelName = "\$t('{$messageCategory}.{$fieldVarName}')";
$modelField = ($modelName ?? 'temp') . '.' . $field;
$errorField = strtr($modelName ?? 'temp', ['temp' => 'errors']) . '.' . $field;
?>
<el-form-item :error="<?= $errorField ?>" :label="<?= $fieldLabelName ?>">
    <?php switch ($type) {
        case VueViewGenerator::INPUT_TYPE_TEXT:
            ?>
          <el-input v-model="<?= $modelField ?>" :placeholder="<?= $fieldLabelName ?>"/>
            <?php
            break;
        case VueViewGenerator::INPUT_TYPE_TEXTAREA:
            ?>
          <el-input
              v-model="<?= $modelField ?>"
              :placeholder="<?= $fieldLabelName ?>"
              :autosize="{ minRows: 2, maxRows: 8}"
              type="textarea"/>
            <?php
            break;
        case VueViewGenerator::INPUT_TYPE_DATE:
        case VueViewGenerator::INPUT_TYPE_DATETIME:
            ?>
          <el-date-picker v-model="<?= $modelField ?>" :placeholder="<?= $fieldLabelName ?>" type="<?= strtolower($type) ?>"/>
            <?php
            break;
        case VueViewGenerator::INPUT_TYPE_SELECT:
            $multipleProp = isset($multiple) && $multiple ? ' multiple' : ''; ?>
          <el-select v-model="<?= $modelField ?>" :placeholder="<?= $fieldLabelName ?>" filterable <?= $multipleProp ?>>
            <el-option v-for="item in <?= $optionsName ?>" :key="item.key" :value="item.key" :label="item.label"/>
          </el-select>
            <?php
            break;
        case VueViewGenerator::INPUT_TYPE_CHECKBOX:
            ?>
            <?php if (isset($optionsName)) { ?>
          <el-checkbox-group v-model="<?= $modelField ?>">
            <el-checkbox v-for="item in <?= $optionsName ?>" :key="item.key" :label="item.key">
              {{ item.label }}
            </el-checkbox>
          </el-checkbox-group>
        <?php } else { ?>
          <el-checkbox v-model="<?= $modelField ?>"><?= $fieldLabelName ?></el-checkbox>
        <?php } ?>
            <?php
            break;
        case VueViewGenerator::INPUT_TYPE_RADIO:
            ?>
          <el-radio v-for="item in <?= $optionsName ?>" v-model="<?= $modelField ?>" :key="item.key" :label="item.key">
            {{ item.label }}
          </el-radio>
            <?php
            break;
        case VueViewGenerator::INPUT_TYPE_UPLOAD:
            ?>
          <xxx-upload :xxxModel="temp"/>
            <?php
            break;
    } ?>
</el-form-item>
