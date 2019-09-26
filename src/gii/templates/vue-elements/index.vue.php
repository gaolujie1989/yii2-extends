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
import outboundOrderApi from 'inventory/api/outboundOrder'
import fulfillmentOrderApi from 'fulfillment/api/order'
import List from 'common/mixins/list'
import Search from 'common/mixins/search'
import Download from 'common/mixins/download'
import Save from 'common/mixins/save'
import Delete from 'common/mixins/delete'
import Upload from 'common/mixins/upload'
import BatchUpdate from 'common/mixins/batchUpdate'
import Pagination from 'components/Pagination'
import waves from 'directive/waves'
import AddressFormItem from 'common/views/address/formItem'
import { carrierConst } from 'inventory/constants/carrier'
import { outboundOrderConst, outboundOrderStatusTransition } from 'inventory/constants/outboundOrder'

export default {
  name: 'OutboundOrderIndex',
  components: { Pagination, AddressFormItem },
  directives: { waves },
  mixins: [Save, Delete, Upload, BatchUpdate, List, Search, Download],
  data() {
    const carrierList = [
      { key: carrierConst.DHL, label: 'DHL' },
      { key: carrierConst.DPD, label: 'DPD' },
      { key: carrierConst.GLS, label: 'GLS' }
    ]
    const orderTypeList = [
      { key: outboundOrderConst.ORDER_TYPE_SALES, label: this.$t('outboundOrder.orderTypeList.sales') },
      { key: outboundOrderConst.ORDER_TYPE_FBA, label: this.$t('outboundOrder.orderTypeList.fba') }
    ]
    const outboundOrderStatusList = [
      { key: outboundOrderConst.STATUS_DRAFT, label: this.$t('outboundOrder.orderStatusList.draft'), 'tag': 'info' },
      {
        key: outboundOrderConst.STATUS_SELLER_CONFIRMED,
        label: this.$t('outboundOrder.orderStatusList.sellerConfirmed'),
        'tag': 'warning'
      },
      {
        key: outboundOrderConst.STATUS_ADMIN_APPROVED,
        label: this.$t('outboundOrder.orderStatusList.adminApproved'),
        'tag': 'primary'
      },
      { key: outboundOrderConst.STATUS_PICKING, label: this.$t('outboundOrder.orderStatusList.picking'), 'tag': 'primary' },
      {
        key: outboundOrderConst.STATUS_PICKING_CANCELING,
        label: this.$t('outboundOrder.orderStatusList.pickingCanceling'),
        'tag': 'primary'
      },
      { key: outboundOrderConst.STATUS_SHIPPED, label: this.$t('outboundOrder.orderStatusList.shipped'), 'tag': 'success' },
      // {
      //   key: outboundOrderConst.STATUS_CUSTOMER_RECEIVED,
      //   label: this.$t('outboundOrder.orderStatusList.customerReceived'),
      //   'tag': 'success'
      // },
      { key: outboundOrderConst.STATUS_CANCELLED, label: this.$t('outboundOrder.orderStatusList.cancelled'), 'tag': 'info' },
      { key: outboundOrderConst.STATUS_PICKING_CANCELLED, label: this.$t('outboundOrder.orderStatusList.pickingCancelled'), 'tag': 'info' },
      {
        key: outboundOrderConst.STATUS_ADMIN_REJECTED,
        label: this.$t('outboundOrder.orderStatusList.adminRejected'),
        'tag': 'danger'
      }
    ]
    const canBatchedOutboundOrderStatusList = [
      {
        key: outboundOrderConst.STATUS_ADMIN_APPROVED,
        label: this.$t('outboundOrder.orderStatusList.adminApproved'),
        'tag': 'primary'
      },
      {
        key: outboundOrderConst.STATUS_PICKING_CANCELING,
        label: this.$t('outboundOrder.orderStatusList.pickingCanceling'),
        'tag': 'primary'
      },
      { key: outboundOrderConst.STATUS_CANCELLED, label: this.$t('outboundOrder.orderStatusList.cancelled'), 'tag': 'info' },
      {
        key: outboundOrderConst.STATUS_ADMIN_REJECTED,
        label: this.$t('outboundOrder.orderStatusList.adminRejected'),
        'tag': 'danger'
      }
    ]
    return {
      activeName: 'outboundOrder',
      carrierList: carrierList,
      orderTypeList: orderTypeList,
      outboundOrderStatusList: outboundOrderStatusList,
      canBatchedOutboundOrderStatusList: canBatchedOutboundOrderStatusList,
      transitionOutboundStatusList: [],
      listQuery: {
        // expand: 'orderItems,shippingAddress',
        sort: '-created_at',
        order_type: outboundOrderConst.ORDER_TYPE_SALES,
        external_order_no: '',
        warehouse_id: '',
        owner_id: '',
        status: '',
        itemNo: '',
        barcode: ''
      },
      errors: {
        shippingAddress: {}
      },
      selectItems: [],
      importTemp: {
        file: [],
        ownerId: '',
        warehouseCode: ''
      },
      fulfillmentOrderLoading: false,
      fulfillmentOrders: []
    }
  },
  computed: {
    ...mapGetters(['sellers', 'warehouses', 'items'])
  },
  watch: {
    temp: 'setTransitionOutboundStatusList'
  },
  created() {
    this.GetSellers()
    this.GetWarehouses()
  },
  methods: {
    ...mapActions(['GetSellers', 'GetWarehouses', 'GetItems']),
    getService() {
      return outboundOrderApi
    },
    getModel() {
      return {
        order_type: outboundOrderConst.ORDER_TYPE_SALES,
        warehouse_code: '',
        carrier: '',
        external_order_no: '',
        estimate_ship_time: '',
        actual_ship_time: '',
        owner_id: '',
        status: '',
        orderItems: [],
        shippingAddress: {},
        created_at: '',

        isEditing: true,
        isProcessing: false,
        isShipped: false,
        isFinished: false
      }
    },
    getOrderItemModel() {
      return {
        item_id: '',
        item_no: '',
        order_item_name: '',
        ordered_qty: '',
        shipped_qty: ''
      }
    },
    handleAddOrderItem() {
      this.temp.orderItems.push(this.getOrderItemModel())
    },
    handleRemoveOrderItem(row) {
      const index = this.temp.orderItems.indexOf(row)
      if (index !== -1) {
        this.temp.orderItems.splice(index, 1)
      }
    },
    fetchTemp(row) {
      return outboundOrderApi.detail(row, { expand: 'orderItems,shippingAddress' }).then(response => {
        this.temp = Object.assign({}, this.temp, row, response.data)
      })
    },
    getOwnerItems(itemNoOrBarcode) {
      if (!this.temp.owner_id) {
        this.selectItems = []
        return
      }
      const barcodeQueryPayload = { ownerId: this.temp.owner_id, itemNo: '', barcode: itemNoOrBarcode }
      const itemNoQueryPayload = { ownerId: this.temp.owner_id, itemNo: itemNoOrBarcode, barcode: '' }
      Promise.all([this.GetItems(barcodeQueryPayload), this.GetItems(itemNoQueryPayload)]).then(values => {
        this.selectItems = [].concat(values[0], values[1])
      })
    },
    getItemNoWithBarcode(item) {
      return item.item_no +
      (item.ean ? ' (' + this.$t('item.ean') + ': ' + item.ean + ')' : '') +
      (item.fnSku ? ' (' + this.$t('item.fnSku') + ': ' + item.fnSku + ')' : '') +
      (item.ownSku ? ' (' + this.$t('item.ownSku') + ': ' + item.ownSku + ')' : '')
    },
    itemNoChanged(row) {
      const findItem = this.selectItems.find(item => item.item_no === row.item_no)
      if (findItem) {
        row.item_no = findItem.item_no
        row.ean = findItem.ean
        row.fnSku = findItem.fnSku
        row.ownSku = findItem.ownSku
      }
    },
    setTransitionOutboundStatusList() {
      const currentStatus = this.temp.status ? this.temp.status : outboundOrderConst.STATUS_DRAFT
      this.transitionOutboundStatusList = this.outboundOrderStatusList.filter(item => {
        return item.key === currentStatus ||
        (outboundOrderStatusTransition[currentStatus] && outboundOrderStatusTransition[currentStatus].indexOf(item.key) !== -1)
      })
    },
    afterList() {
      this.getFulfillmentOrders()
    },
    getFulfillmentOrders() {
      this.fulfillmentOrderLoading = true
      const orderIds = this.listData.map(order => order.id)
      fulfillmentOrderApi.list({ order_id: orderIds, limit: 500 }).then(response => {
        this.fulfillmentOrderLoading = false
        this.fulfillmentOrders = response.data.items
      }).catch(() => {
        this.fulfillmentOrderLoading = false
      })
    },
    getOrderFulfillmentOrder(orderId) {
      return this.fulfillmentOrders.filter(order => order.order_id === orderId)
    }
  }
}
</script>
