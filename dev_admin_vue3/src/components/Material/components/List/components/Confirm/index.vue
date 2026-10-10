<script setup lang="ts">
import { WarningFilled, Close } from "@element-plus/icons-vue";
import { ref, watch } from "vue";
interface Props {
  dialogVisible?: boolean;
  realDelDisabled?: boolean;
  realDel?: 1 | 0;
}
const props = withDefaults(defineProps<Props>(), {
  dialogVisible: false,
  realDelDisabled: false,
  realDel: 0
});
const dialogVisible = ref<boolean>(props.dialogVisible);
const realDelDisabled = ref<boolean>(props.realDelDisabled);
const realDel = ref<1 | 0>(props.realDel);

watch(
  () => props.dialogVisible,
  newVal => {
    dialogVisible.value = newVal;
  }
);
watch(
  () => props.realDelDisabled,
  newVal => {
    realDelDisabled.value = newVal;
  }
);
watch(
  () => props.realDel,
  newVal => {
    realDel.value = newVal;
  }
);

const emits = defineEmits<{
  (event: "update:dialogVisible", newVal: boolean): void;
  (event: "update:realDelDisabled", newVal: boolean): void;
  (event: "update:realDel", newVal: 1 | 0): void;
  (event: "sucess"): void;
}>();

watch(
  () => dialogVisible.value,
  newVal => {
    emits("update:dialogVisible", newVal);
  }
);
watch(
  () => realDelDisabled.value,
  newVal => {
    emits("update:realDelDisabled", newVal);
  }
);

watch(
  () => realDel.value,
  newVal => {
    emits("update:realDel", newVal);
  }
);

const closeDialog = () => {
  dialogVisible.value = false;
};

const confirmDel = () => {
  dialogVisible.value = false;
  emits("sucess");
};
</script>
<template>
  <div class="el-dialog-confirm">
    <el-dialog v-model="dialogVisible" width="30%">
      <div class="relative">
        <div class="h-[20px] flex justify-end">
          <el-icon class="thumb" @click="closeDialog" size="25"><Close /></el-icon>
        </div>
        <div class="flex items-center">
          <el-icon color="#e6a23c" size="25"><WarningFilled /></el-icon>
          <span class="text-[20px] ml-[5px]">确定要删除文件吗?</span>
        </div>
        <el-checkbox
          :disabled="realDelDisabled"
          :true-value="1"
          :false-value="0"
          class="mt-[5px] ml-[5px]"
          v-model="realDel"
          label="  彻底删除文件(释放文件空间)"
        />
      </div>
      <template #footer>
        <span class="dialog-footer">
          <el-button @click="dialogVisible = false">取消</el-button>
          <el-button type="primary" @click="confirmDel"> 确定 </el-button>
        </span>
      </template>
    </el-dialog>
  </div>
</template>
<style scoped lang="scss">
@import "./index.scss";
</style>
