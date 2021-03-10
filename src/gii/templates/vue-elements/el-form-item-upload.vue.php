<?php /** @noinspection ALL */ ?>
<template>
  <div>
    <el-upload
        :file-list="uploadFileList"
        :action="uploadUrl"
        :data="additionalData"
        :on-remove="handleRemove"
        :on-success="handleSuccess"
        :on-preview="handlePreview"
        multiple
        with-credentials>
      <el-button size="small" type="primary">{{ $t('common.clickToUpload') }}</el-button>
      <div slot="tip" class="el-upload__tip">{{ $t('common.uploadSupportFileWithSizeLimit', { ext: 'xxx', size: '500kb' }) }}</div>
    </el-upload>
  </div>
</template>

<script>
    import Upload from 'common/mixins/upload'
    import Download from 'common/mixins/download'

    export default {
        name: 'xxxModelUpload',
        mixins: [Upload, Download],
        props: {
            xxxModel: {
                type: Object,
                default: function () {
                    return {}
                }
            }
        },
        data() {
            return {}
        },
        computed: {
            additionalData() {
                return {
                    model_id: this.xxxModel.id
                }
            }
        },
        watch: {
            'xxxModel.xxxField': function () {
                this.uploadFileList = this.xxxModel.xxxField
            },
            'lastUploadedFileList': function () {
                this.xxxModel.xxxField = this.lastUploadedFileList
            }
        },
        methods: {
            getService() {
                return xxxApi
            },
            handlePreview(file) {
                this.download(xxxApi.url + '/' + file.id + '/download')
            }
        }
    }
</script>
