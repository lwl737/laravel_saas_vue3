<template>
  <div class="table-box">
    <ProTable useOrgani ref="proTable" :columns="columns" :request-api="list" :row-key="`role_id`">
      <!-- 表格 header 按钮 -->
      <template #tableHeader="scope">
        <el-button type="primary" :icon="CirclePlus" v-auth="`add`" @click="() => openFrom()">新增权限</el-button>
        <el-button type="primary" :icon="Aim" @click="() => proTable?.element?.toggleAllSelection()">全选 / 全不选</el-button>
        <el-button
          type="danger"
          :icon="Delete"
          plain
          @click="() => batchDelete(scope.selectedListIds as number[])"
          :disabled="!scope.isSelected"
          v-auth="`delete`"
        >
          批量删除
        </el-button>
      </template>
      <!-- Expand -->
      <template #operation="scope">
        <el-button type="primary" link :icon="Setting" v-auth="`setAuth`" @click="() => setRole(scope.row)">设置权限</el-button>
        <el-button type="primary" link :icon="EditPen" v-auth="`edit`" @click="() => openFrom(scope.row)">编辑</el-button>
        <el-button type="primary" link :icon="Delete" v-auth="`delete`" @click="() => batchDelete([scope.row.role_id])">
          删除
        </el-button>
      </template>
    </ProTable>
    <Form ref="form" @close="close" />
    <RoleTree ref="roleTree" :tree-data-api="treeData" @close="close" />
  </div>
</template>
<script setup lang="tsx" name="AccountRoleIndex">
import { ref } from "vue";
import { ElMessage, ElMessageBox } from "element-plus";
import { ColumnProps } from "@/components/ProTable/interface";
import { CirclePlus, Delete, Aim, EditPen, Setting } from "@element-plus/icons-vue";
import ProTable from "@/components/ProTable/index.vue";
import Form from "./components/form/index.vue";
import RoleTree from "./components/roleTree/index.vue";
import { list, del, treeData, editStatus } from "@/api/modules/role";
import { Role } from "@/api/interface/role";
import { useAuthStore } from "@/stores/modules/auth";

// 获取 ProTable 元素，调用其获取刷新数据方法（还能获取到当前查询参数，方便导出携带参数）
const proTable = ref<InstanceType<typeof ProTable>>();
const form = ref<InstanceType<typeof Form>>();
const roleTree = ref<InstanceType<typeof RoleTree>>();
const authStore = useAuthStore();
// 表格配置项
const columns: ColumnProps[] = [
  { type: "selection", fixed: "left", width: 80 },
  { type: "index", label: "#", width: 80 },
  {
    prop: "role_name",
    label: "权限名称",
    width: 300,
    search: { el: "input" }
  },
  {
    prop: "status",
    width: 200,
    label: "状态",
    render: scope => {
      return (
        <el-switch
          v-model={scope.row.status}
          active-text="启用"
          active-value={1}
          inactive-value={0}
          inactive-text="禁用"
          onChange={(val: 1 | 0) => statusChange(val, scope.row)}
        />
      );
    }
  },
  {
    prop: "role_sort",
    label: "排序",
    width: 100
  },

  // 多级 prop
  { prop: "role_describe", width: 300, label: "权限描述" },
  { prop: "operation", label: "操作", width: 250, fixed: "right" }
];
const openFrom = (row: Role.List | undefined = undefined) => {
  form.value?.open(row);
};
const close = () => {
  proTable.value?.getTableList();
};
const setRole = (row: Role.List) => {
  roleTree.value?.open(row.role_id, row.role_json);
};

const statusChange = async (nVal: 1 | 0, row: Role.List) => {
  if (!authStore.checkAuth(["edit"])) return ElMessage.warning("没有修改权限");
  await editStatus({ status: nVal, role_id: row.role_id });
  ElMessage.success("修改状态成功");
  proTable.value?.getTableList();
};
const batchDelete = (ids: Array<number>) => {
  ElMessageBox.confirm("确认要删除该权限吗?", "温馨提示", { type: "warning" }).then(async () => {
    await del({ ids });
    ElMessage.success("删除成功");
    proTable.value?.getTableList();
  });
};
</script>
<style lang="scss" scope>
@import "./index.scss";
</style>
