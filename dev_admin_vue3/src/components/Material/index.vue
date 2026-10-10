<template>
  <el-tabs type="border-card" style="width: 100%" class="material-tabs" v-model="activeName">
    <el-tab-pane label="我的素材" name="mine">
      <List
        class="material-list-deep"
        ref="mine"
        @tree-del="treeDel"
        :key="mineKey"
        :fixation-val="fixationVal"
        :default-box-status="defaultBoxStatus"
        @tree-edit="treeEdit"
        @upload="upload"
        :multiple="multiple"
        @single-select-emit="singleSelectEmit"
        @open-drawer="openDrawer"
    /></el-tab-pane>
  </el-tabs>
  <Form ref="form" class="material-form-deep" @close="refresh" />
</template>
<script setup lang="ts" name="">
import { ref, nextTick, onBeforeUnmount, onActivated } from "vue";
import Form from "./components/Form/index.vue";
import List from "./components/List/index.vue";
import { dirDel } from "@/api/modules/upload";
import { ElMessage, ElMessageBox } from "element-plus";
import mittBus from "@/utils/mittBus";
import { type BoxStatus, type ListShow } from "./components/List/index";
import { onBeforeRouteLeave } from "vue-router";
interface Props {
  initMittBus?: boolean;
  defaultBoxStatus?: BoxStatus;
  multiple?: boolean;
  fixationVal?: string[];
}

const props = withDefaults(defineProps<Props>(), {
  initMittBus: true,
  defaultBoxStatus: "none",
  multiple: false,
  fixationVal: () => []
});

const form = ref<InstanceType<typeof Form>>();

const mine = ref<InstanceType<typeof List>>();

const common = ref<InstanceType<typeof List>>();

const activeName = ref<"mine" | "common">("mine");

const openDrawer = (dir_pid: number | string) => {
  form.value?.open(dir_pid.toString());
};

const mineKey = ref<number>(0);

const commonKey = ref<number>(0);

const refresh = () => {
  nextTick(() => (activeName.value === "mine" ? mine.value?.treeFilter?.initData() : common.value?.treeFilter?.initData()));
};
const treeEdit = (data: any) => {
  form.value?.open(data.dir_pid, data.dir_id, data);
};

const treeDel = async (data: any) => {
  ElMessageBox.confirm("确认要删除该目录吗?", "温馨提示", { type: "warning" }).then(async () => {
    await dirDel({ ids: [data.dir_id] });
    ElMessage.success("删除成功");
    activeName.value === "mine" ? ++mineKey.value : ++commonKey.value;
  });
};

const upload = (dir_id: string, dir_link: string) => {
  mittBus.emit("uploadFile", { dir_id, dir_link });
};

const uploadSuccessFunc = () => {
  ElMessageBox.confirm("所有文件上传完成是否刷新素材", "提示", {
    confirmButtonText: "是",
    cancelButtonText: "否",
    type: "success"
  }).then(() => {
    // initData();

    if (activeName.value === "mine") {
      mine.value?.initData();
    } else {
      common.value?.initData();
    }
  });
};

const mittBusInitFunc = () => {
  mittBus.on("uploadSuccess", uploadSuccessFunc);
};

const mittBusClearFunc = () => {
  mittBus.off("uploadSuccess", uploadSuccessFunc);
};

const emits = defineEmits<{
  (event: "singleSelectEmit", item: ListShow): void;
}>();

const singleSelectEmit = (item: ListShow) => {
  emits("singleSelectEmit", item);
};

onBeforeUnmount(() => {
  mittBusClearFunc();
});

onActivated(() => {
  if (props.initMittBus) mittBusInitFunc();
});

onBeforeRouteLeave(() => {
  mittBusClearFunc();
});

defineExpose({ mittBusInitFunc, mittBusClearFunc });
</script>
<style scoped lang="scss">
@import "./index.scss";
</style>
