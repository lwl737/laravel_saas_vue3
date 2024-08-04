<template>
  <div class="table-box">
    <ProTable ref="proTable" :columns="columns" :request-api="superAdminList" :row-key="`admins_id`">
      <template #portrait="{ row }">
        <div class="video-cover">
          <Img :src="row.portrait.full_url" />
        </div>
      </template>

      <!-- 表格 header 按钮 -->
      <template #tableHeader="scope">
        <el-button type="primary" v-auth="['add']" :icon="CirclePlus" @click="() => openFrom()">新增超级管理员</el-button>
        <el-button type="primary" :icon="Aim" @click="() => proTable?.element?.toggleAllSelection()">全选 / 全不选</el-button>
        <el-button
          v-auth="['delete']"
          type="danger"
          :icon="Delete"
          plain
          @click="() => batchDelete(scope.selectedListIds as number[])"
          :disabled="!scope.isSelected"
        >
          批量删除
        </el-button>
      </template>

      <!-- 表格 header 按钮 -->
      <!-- Expand -->
      <template #operation="scope">
        <el-button type="primary" v-auth="['edit']" link :icon="EditPen" @click="() => openFrom(scope.row)">编辑</el-button>
        <el-button type="primary" v-auth="['delete']" link :icon="Delete" @click="() => batchDelete([scope.row.admins_id])">
          删除
        </el-button>
      </template>
    </ProTable>
    <Form ref="form" @close="close" />
  </div>
</template>
<script setup lang="tsx" name="DevSuperAdminIndex">
import { ref } from "vue";
import { ElMessage, ElMessageBox } from "element-plus";
import { ColumnProps } from "@/components/ProTable/interface";
import { CirclePlus, Delete, Aim, EditPen } from "@element-plus/icons-vue";
import ProTable from "@/components/ProTable/index.vue";
import Form from "./components/form/index.vue";
import { superAdminList, superAdminStatus, superAdminDel } from "@/api/modules/develop";
import { Develop } from "@/api/interface/develop";
import Img from "@/components/Img/index.vue";
import { useAuthStore } from "@/stores/modules/auth";

// 获取 ProTable 元素，调用其获取刷新数据方法（还能获取到当前查询参数，方便导出携带参数）
const proTable = ref<InstanceType<typeof ProTable>>();
const form = ref<InstanceType<typeof Form>>();
const authStore = useAuthStore();
// 表格配置项
const columns: ColumnProps[] = [
  { type: "selection", fixed: "left", width: 80 },
  { type: "index", label: "#", width: 80 },
  {
    prop: "username",
    label: "用户名",
    width: 220,
    search: { el: "input" }
  },
  {
    prop: "nick_name",
    label: "昵称",
    search: { el: "input" }
  },
  {
    prop: "portrait",
    label: "头像",
    width: 100,
    showOverflowTooltip: false
  },
  {
    prop: "real_name",
    label: "真实姓名",
    width: 150,
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
          onChange={(val: 1 | 0) => stautChange(val, scope.row)}
        />
      );
    }
  },
  { prop: "operation", label: "操作", width: 250, fixed: "right" }
];
const openFrom = (row?: Develop.SuperAdmin.List) => {
  row ? form.value?.open({ ...row, portrait: row.portrait.value }) : form.value?.open();
};
const close = () => {
  proTable.value?.getTableList();
};
const stautChange = async (nVal: 1 | 0, row: Develop.SuperAdmin.List) => {
  if (!authStore.checkAuth(["edit"])) return ElMessage.warning("没有修改权限");
  await superAdminStatus({ status: nVal, admins_id: row.admins_id });
  ElMessage.success("修改状态成功");
  proTable.value?.getTableList();
};
const batchDelete = (ids: Array<number>) => {
  ElMessageBox.confirm("确认要删除该用户吗?", "温馨提示", { type: "warning" }).then(async () => {
    await superAdminDel({ ids });
    ElMessage.success("删除成功");
    proTable.value?.getTableList();
  });
};
</script>
<style lang="scss" scope>
@import "./index.scss";
</style>
