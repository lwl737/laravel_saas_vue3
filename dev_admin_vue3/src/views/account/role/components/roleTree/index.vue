<template>
  <el-drawer
    v-model="data.drawerShow"
    direction="rtl"
    :title="(data.role_id !== 0 ? `编辑` : `添加`) + `权限`"
    :with-header="true"
  >
    <div v-loading="data.treeLoad">
      <CheckBoxTree
        ref="checkBoxTree"
        :mapping="data.mapping"
        :attach-mapping="data.attachMapping"
        :tree="data.tree"
        :default="data.default"
        class="checkBoxTree"
      />
    </div>
    <el-button type="primary" @click="() => save()" class="form-bottom-save">保存</el-button>
  </el-drawer>
</template>
<script setup lang="ts">
import { reactive, ref } from "vue";
import { ElMessage } from "element-plus";
import CheckBoxTree from "@/components/CheckBoxTree/index.vue";
import { treeData, set } from "@/api/modules/role";
import { Role } from "@/api/interface/role";

/* 表单 */
const checkBoxTree = ref<InstanceType<typeof CheckBoxTree>>();

/* 参数 */
const data = reactive<{
  drawerShow: boolean;
  role_id: number;
  mapping: {
    title: string;
    id: string;
    children: string;
    attach: string;
  };
  attachMapping: {
    attach_id: string;
    attach_name: string;
  };
  default: { [key: number]: Array<number> };
  tree: Array<Role.TreeData>;
  treeLoad: boolean;
}>({
  drawerShow: false,
  default: {},
  role_id: 0,
  mapping: {
    title: "title",
    id: "menu_id",
    children: "children",
    attach: "auth"
  },
  attachMapping: {
    attach_id: "auth_id",
    attach_name: "auth_name"
  },
  tree: [],
  treeLoad: false
});

/*  */

/**
 * 关闭弹窗清空表单
 */

/*  */

/**
 * 对外暴露子组建方法
 */
const open = async (role_id: number, defaultData: { [key: number]: Array<number> }) => {
  data.role_id = role_id;
  data.drawerShow = true;
  data.default = defaultData;
  data.treeLoad = true;
  data.tree = (await treeData()).data;
  data.treeLoad = false;
  checkBoxTree.value?.initData();
};

const save = async () => {
  // console.log(checkBoxTree.value?.getResult());
  await set({ role_json: checkBoxTree.value?.getResult() }, data.role_id);
  ElMessage.success("设置成功");
  data.drawerShow = false;
  emits("close");
};

defineExpose({
  open
});
/*  */
/**
 * 事件回调
 */
const emits = defineEmits<{
  (event: "close"): void;
}>();
/*  */
</script>
<style scoped lang="scss">
@import "./index.scss";
.save-button {
  margin-top: 50px;
}
</style>
