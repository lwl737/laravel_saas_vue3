<template>
  <div class="main-box card filter main-box-part">
    <el-tabs v-model="activeName" class="tabs">
      <el-tab-pane label="上传设置" name="upload">
        <div class="content">
          <div class="content-part"><Upload ref="upload" @submit-form="() => submitForm()" /></div>
          <div class="content-part"></div>
        </div>
      </el-tab-pane>

      <el-tab-pane label="ORM缓存设置" name="cache">
        <div class="content">
          <div class="content-part"><ORMCache @clear-all="() => clearAll()" /></div>
          <div class="content-part"></div>
        </div>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<script setup lang="ts" name="DevSettingIndex">
import { ref } from "vue";
import { ElMessage } from "element-plus";
import Upload from "./components/Upload/index.vue";
import { settingsGet, settingSet, clearCache } from "@/api/modules/develop";
import ORMCache from "./components/ORMCache/index.vue";

const upload = ref<InstanceType<typeof Upload>>();

settingsGet({
  field: [
    "library_max_capacity",
    "upload_slice_size",
    "prefix_url",
    "upload_max_size",
    "upload_file_type",
    "upload_check_file_type",
    "oss_start",
    "oss_access_key_id",
    "oss_access_key_secret",
    "oss_endpoint",
    "oss_bucket",
    "oss_role_arr"
  ]
}).then(res => {
  upload.value?.initData(res.data);
});

const clearAll = () => {
  clearCache().then(() => {
    ElMessage.success("清除成功");
  });
};

const activeName = ref("upload");

const submitForm = async () => {
  if (!upload.value?.ruleForm) return;
  await settingSet({ ...upload.value?.ruleForm });
  ElMessage.success("设置成功");
};
</script>
<style scoped lang="scss">
@import "./index.scss";
</style>
