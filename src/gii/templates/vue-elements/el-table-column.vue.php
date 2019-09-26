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
 * @var string $columnType
 * @var string $messageCategory
 * @var array $properties
 * @var string $optionsName
 */

$fieldVarName = Inflector::variablize($field);
$fieldLabelName = "\$t('{$messageCategory}.{$field}')";

$propTexts = [];
if (isset($properties)) {
    foreach ($properties as $key => $value) {
        $propTexts[] = $key . '="' . $value . '"';
    }
}
$propTexts = implode(' ', $propTexts);

switch ($columnType) {
    case VueViewGenerator::COLUMN_TYPE_INDEX:
    case VueViewGenerator::COLUMN_TYPE_SELECTION: ?>
      <el-table-column type="<?= strtolower($columnType) ?>" align="center" <?= $propTexts ?>/>
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
          <span v-if="scope.row.<?= $field ?>">{{ scope.row.<?= $field ?> | parseTime }}</span>
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
