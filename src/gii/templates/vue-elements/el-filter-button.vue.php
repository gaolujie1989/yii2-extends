<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\extend\gii\VueViewGenerator;
use yii\base\View;

/**
 * @var View $this
 * @var string $button
 */

switch ($button) {
    case VueViewGenerator::BUTTON_SEARCH: ?>
      <el-button v-waves type="primary" icon="el-icon-search" class="filter-item" @click="handleFilter">
        {{ $t('common.search') }}
      </el-button>
        <?php break;
    case VueViewGenerator::BUTTON_CREATE: ?>
      <el-button v-waves type="success" icon="el-icon-plus" class="filter-item" style="float: right" @click="handleCreate">
        {{ $t('common.create') }}
      </el-button>
        <?php break;
    case VueViewGenerator::BUTTON_UPLOAD: ?>
      <el-button v-waves type="success" icon="el-icon-upload2" class="filter-item" style="float: right" @click="handleUpload">
        {{ $t('common.upload') }}
      </el-button>
        <?php break;
    case VueViewGenerator::BUTTON_DOWNLOAD: ?>
      <el-button
        v-waves
        type="info"
        icon="el-icon-download"
        class="filter-item"
        style="float: right"
        @click="download('xxx', listQuery)">
        {{ $t('common.download') }}
      </el-button>
        <?php break;
    case VueViewGenerator::BUTTON_BATCH_UPDATE: ?>
      <el-button
        v-waves
        :disabled="!multipleSelection.length"
        type="primary"
        icon="el-icon-edit"
        class="filter-item"
        style="float: right"
        @click="handleBatchUpdate">
        {{ $t('common.batchUpdate') }}
      </el-button>
        <?php break;
}
