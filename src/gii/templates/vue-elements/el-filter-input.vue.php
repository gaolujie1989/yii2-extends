<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\extend\gii\VueViewGenerator;
use yii\base\View;
use yii\helpers\Inflector;

/**
 * @var View $this
 * @var string $field
 * @var string $type
 * @var string $optionsName
 */

/** @var VueViewGenerator $generator */
$generator = $this->context;
$messageCategory = $generator->messageCategory;
$fieldVarName = Inflector::variablize($field);
$fieldLabelName = "\$t('{$messageCategory}.{$fieldVarName}')";
$modelField = 'listQuery.' . $field;

switch ($type) {
    case VueViewGenerator::INPUT_TYPE_TEXT: ?>
      <el-input
        v-model="<?= $modelField ?>"
        :placeholder="<?= $fieldLabelName ?>"
        class="filter-item"
        @keyup.enter.native="handleFilter"
      />
        <?php break;
    case VueViewGenerator::INPUT_TYPE_DATE_RANGE: ?>
      <el-date-picker
        v-model="<?= $modelField ?>"
        :start-placeholder="$t('<?= $messageCategory . '.' . $fieldVarName ?>From')"
        :end-placeholder="$t('<?= $messageCategory . '.' . $fieldVarName ?>To')"
        :default-time="['00:00:00', '23:59:59']"
        type="daterange"
        range-separator=" - "
        class="filter-item"
      />
        <?php break;
    case VueViewGenerator::INPUT_TYPE_SELECT:
        $multipleProp = isset($multiple) && !$multiple ? '' : 'multiple'; ?>
      <el-select v-model="<?= $modelField ?>" :placeholder="<?= $fieldLabelName ?>" clearable <?= $multipleProp ?> class="filter-item">
        <el-option v-for="item in <?= $optionsName ?>" :key="item.key" :value="item.key" :label="item.label" />
      </el-select>
        <?php break;
    case VueViewGenerator::INPUT_TYPE_CHECKBOX: ?>
        <?php if (isset($optionsName)) { ?>
        <el-checkbox-group v-model="<?= $modelField ?>" class="filter-item">
          <el-checkbox v-for="item in <?= $optionsName ?>" :key="item.key" :label="item.key">
            {{ item.label }}
          </el-checkbox>
        </el-checkbox-group>
        <?php } else { ?>
        <el-checkbox v-model="<?= $modelField ?>" class="filter-item"><?= $fieldLabelName ?></el-checkbox>
        <?php } ?>
        <?php break;
}
