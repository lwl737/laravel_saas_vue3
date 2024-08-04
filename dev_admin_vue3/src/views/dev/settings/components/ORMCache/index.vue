<template>
  <div class="">
    <el-button type="danger" icon="delete" class="mt-[20px] ml-[20px]" @click="clearAll">清除所有缓存</el-button>
  </div>
</template>

<script setup lang="ts">
import { ElMessageBox } from "element-plus";

const emits = defineEmits<{
  (event: "clearAll"): void;
}>();

const clearAll = () => {
  ElMessageBox.confirm("确认要清除缓存吗", "温馨提示", { type: "warning" }).then(() => {
    emits("clearAll");
  });
};
</script>

<style scoped lang="scss">
@import "./index.scss";
</style>
