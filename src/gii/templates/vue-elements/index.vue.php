<?php
/**
 * @copyright Copyright (c) 2019
 */

use lujie\extend\gii\VueViewGenerator;
use yii\base\View;

/**
 * @var View $this
 * @var string $filterInputContent
 * @var string $filterButtonContent
 * @var string $tableColumnContent
 * @var string $formItemContent
 * @var string $batchFormItemContent
 * @var string $uploadFormItemContent
 */

/** @var VueViewGenerator $generator */
$generator = $this->context;
$enabledUpload = in_array(VueViewGenerator::BUTTON_UPLOAD, $generator->buttons, true);
$enabledBatch = in_array(VueViewGenerator::BUTTON_BATCH_UPDATE, $generator->buttons, true);
$fields = array_keys($generator->formFields);
$fields = array_map(static function ($field) {
    return $field . ": '',";
}, $fields);
?>
<template>
  <div class="app-container">
    <div class="filter-container">

        <?= $filterInputContent ?>

        <?= $filterButtonContent ?>

    </div>

    <el-table v-loading.body="listLoading" :data="listData" border highlight-current-row style="width: 100%"
        <?= $enabledBatch ? '@selection-change="handleSelectionChange"' : '' ?>>

        <?= $tableColumnContent ?>

    </el-table>

    <pagination v-show="total > 0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />

    <el-dialog
      :title="$t(textMap[dialogStatus])"
      :visible.sync="dialogFormVisible"
      :close-on-click-modal="false"
      class="dialog-container"
      width="60%"
      top="2vh">
      <el-form
        v-loading="fetchTempLoading"
        :model="temp"
        label-position="right"
        label-suffix=": "
        label-width="140px"
        style="width: 95%">

          <?= $formItemContent ?>

      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">{{ $t('common.cancel') }}</el-button>
        <el-button :loading="saveLoading" type="primary" @click="save">{{ $t('common.save') }}</el-button>
      </div>
    </el-dialog>

      <?php if ($enabledBatch) { ?>
        <el-dialog :title="$t('common.batchUpdate')" :visible.sync="batchFormVisible" class="dialog-container">
          <el-form :model="batchTemp" label-position="right" label-suffix=": " label-width="120px" style="width: 90%;">

              <?= $batchFormItemContent ?>

          </el-form>
          <div slot="footer" class="dialog-footer">
            <el-button @click="batchFormVisible = false">{{ $t('common.cancel') }}</el-button>
            <el-button :loading="batchUpdateLoading" type="primary" @click="batchUpdate">{{ $t('common.batchUpdate') }}</el-button>
          </div>
        </el-dialog>
      <?php } ?>

      <?php if ($enabledUpload) { ?>
        <el-dialog :title="$t('common.upload')" :visible.sync="uploadFormVisible" class="dialog-container">
          <el-form :model="importTemp" class="small-space" label-position="right" label-suffix=": " label-width="120px" style="width: 90%;">

              <?= $batchFormItemContent ?>

            <el-form-item :label="$t('common.uploadFile')" :error="importErrors.file">
              <el-upload
                :action="uploadUrl"
                :headers="uploadHeaders"
                :file-list="uploadFileList"
                :on-success="handleSuccess"
                :on-error="handleError"
                :on-remove="handleRemove"
                drag
                multiple
                class="upload-demo">
                <i class="el-icon-upload" />
                <div class="el-upload__text">{{ $t('common.dragFile') }}<em>{{ $t('common.clickToUpload') }}</em></div>
                <div slot="tip" class="el-upload__tip">
                  <span>{{ $t('common.uploadSupportFile', { ext: 'xlsx' }) }}</span>
                  <span style="margin-left: 50px;">
                    <span>{{ $t('common.templateDownload') }}:</span>
                    <a :href="getRemoteUrl('xxx/template')" target="_blank">Template.xlsx</a>
                  </span>
                </div>
              </el-upload>
            </el-form-item>
            <el-form-item>
              <div v-for="(result, file) in importResults" :key="file">
                {{ file }}
                <el-tag v-if="result.created" type="success">Insert {{ result.created }} rows</el-tag>
                <el-tag v-if="result.updated" type="success">Update {{ result.updated }} rows</el-tag>
                <el-tag v-if="result.skipped" type="error">Failed {{ result.skipped }} rows</el-tag>
              </div>
            </el-form-item>
          </el-form>
          <div slot="footer" class="dialog-footer">
            <el-button @click="uploadFormVisible = false">{{ $t('common.cancel') }}</el-button>
            <el-button :loading="importLoading" type="primary" @click="importUpload">{{ $t('common.import') }}</el-button>
          </div>
        </el-dialog>
      <?php } ?>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import List from 'common/mixins/list'
import Search from 'common/mixins/search'
import Download from 'common/mixins/download'
import Save from 'common/mixins/save'
import Delete from 'common/mixins/delete'
import Upload from 'common/mixins/upload'
import BatchUpdate from 'common/mixins/batchUpdate'
import Pagination from 'components/Pagination'
import waves from 'directive/waves'

export default {
  name: 'XXXIndex',
  components: { Pagination },
  directives: { waves },
  mixins: [Save, Delete, Upload, BatchUpdate, List, Search, Download],
  data() {
    return {}
  },
  computed: {
    ...mapGetters([])
  },
  created() {
  },
  methods: {
    ...mapActions([]),
    getService() {
      return xxxApi
    },
    getModel() {
      return {
          <?= implode("\n", $fields) ?>
      }
    },
    fetchTemp(row) {
      this.temp = Object.assign({}, this.temp, row)
      return Promise.resolve()
    },
  }
}
</script>
