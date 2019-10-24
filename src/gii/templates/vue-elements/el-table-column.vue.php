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
 * @var array $properties
 * @var string $optionsName
 */

/** @var VueViewGenerator $generator */
$generator = $this->context;
$messageCategory = $generator->messageCategory;
$fieldVarName = Inflector::variablize($field);
$fieldLabelName = "\$t('{$messageCategory}.{$field}')";

$propTexts = [];
if (isset($properties)) {
    foreach ($properties as $key => $value) {
        $propTexts[] = $key . '="' . $value . '"';
    }
}
$propTexts = implode(' ', $propTexts);

switch ($type) {
    case VueViewGenerator::COLUMN_TYPE_INDEX:
    case VueViewGenerator::COLUMN_TYPE_SELECTION: ?>
      <el-table-column type="<?= strtolower($type) ?>" align="center" <?= $propTexts ?>/>
        <?php break;
    case VueViewGenerator::COLUMN_TYPE_OPTION: ?>
      <el-table-column :label="<?= $fieldLabelName ?>" prop="<?= $field ?>" <?= $propTexts ?>>
        <template slot-scope="scope">
          <el-tag :type="scope.row.<?= $field ?> | keyToLabel(<?= $optionsName ?>, 'key', 'tag')">
            {{ scope.row.<?= $field ?> | keyToLabel(<?= $optionsName ?>, 'key', 'label') }}
          </el-tag>
        </template>
      </el-table-column>
        <?php break;
    case VueViewGenerator::COLUMN_TYPE_TIMESTAMP: ?>
      <el-table-column :label="<?= $fieldLabelName ?>" prop="<?= $field ?>" <?= $propTexts ?>>
        <template slot-scope="scope">
          {{ scope.row.<?= $field ?> | parseTime }}
        </template>
      </el-table-column>
        <?php break;
    case VueViewGenerator::COLUMN_TYPE_TEXT: ?>
      <el-table-column :label="<?= $fieldLabelName ?>" prop="<?= $field ?>" <?= $propTexts ?>/>
        <?php break;
    case VueViewGenerator::COLUMN_TYPE_ACTION: ?>
      <el-table-column :label="$t('common.action')" align="center" <?= $propTexts ?>>
        <div slot-scope="scope" class="table-action">
          <el-button type="primary" size="small" icon="el-icon-edit" @click="handleUpdate(scope.row)" />
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row)" />
        </div>
      </el-table-column>
        <?php break;
}
