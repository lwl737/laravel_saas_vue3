<template>
  <div class="table-box">
    <ProTable ref="proTable" :columns="columns" :request-api="list" :row-key="`admins_id`">
      <template #portrait="{ row }">
        <div class="video-cover">
          <Img :src="row.portrait.full_url" />
        </div>
      </template>

      <!-- 表格 header 按钮 -->
      <template #tableHeader="scope">
        <el-button type="primary" :icon="CirclePlus" @click="() => openFrom()" v-auth="`add`">新增用户</el-button>
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

      <!-- 表格 header 按钮 -->
      <!-- Expand -->
      <template #operation="scope">
        <el-button type="primary" link :icon="EditPen" v-auth="`edit`" @click="() => openFrom(scope.row)">编辑</el-button>
        <el-button type="primary" link :icon="Delete" v-auth="`delete`" @click="() => batchDelete([scope.row.admins_id])">
          删除
        </el-button>
      </template>
    </ProTable>
    <Form ref="form" @close="close" />
  </div>
</template>
<script setup lang="tsx" name="AccountAdminIndex">
import { ref } from "vue";
import { ElMessage, ElMessageBox } from "element-plus";
import { ColumnProps } from "@/components/ProTable/interface";
import { CirclePlus, Delete, Aim, EditPen } from "@element-plus/icons-vue";
import ProTable from "@/components/ProTable/index.vue";
import Form from "./components/form/index.vue";
import { list, status, del, roleAll, organiAll } from "@/api/modules/admin";
import { Admin } from "@/api/interface/admin";
import Img from "@/components/Img/index.vue";
import { useAuthStore } from "@/stores/modules/auth";

// 获取 ProTable 元素，调用其获取刷新数据方法（还能获取到当前查询参数，方便导出携带参数）
const proTable = ref<InstanceType<typeof ProTable>>();
const authStore = useAuthStore();
const form = ref<InstanceType<typeof Form>>();
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
    prop: "role_id",
    label: "权限",
    enum: () => {
      return new Promise(async reslove => {
        let res = await roleAll();
        reslove({ data: res.data.all });
      });
    },
    changeSearch: true,
    isShow: false,
    fieldNames: { label: "role_name", value: "role_id" },
    search: { el: "select" }
  },
  {
    prop: "organi_id",
    label: "所属部门",
    enum: () => {
      return new Promise(async reslove => {
        let res = await organiAll();
        reslove({ data: res.data.tree });
      });
    },
    isShow: false,
    fieldNames: { label: "organi_name", value: "organi_id" },
    search: {
      el: "tree-select",
      props: {
        multiple: true,
        defaultExpandAll: true,
        checkStrictly: true,
        renderAfterexpand: false,
        showCheckbox: true,
        checkOnClickNode: true
      }
    }
  },
  {
    prop: "role_name",
    width: 220,
    label: "权限",
    render: scope => {
      return <span>{scope.row.role_name}</span>;
    }
  },
  {
    prop: "organi_name",
    width: 220,
    label: "所属部门",
    render: scope => {
      return <pre>{scope.row.organi_name}</pre>;
    }
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
const openFrom = (row?: Admin.List) => {
  row ? form.value?.open({ ...row, portrait: row.portrait.value }) : form.value?.open();
};
const close = () => {
  proTable.value?.getTableList();
};
const stautChange = async (nVal: 1 | 0, row: Admin.List) => {
  if (!authStore.checkAuth(["edit"])) return ElMessage.warning("没有修改权限");
  await status({ status: nVal, admins_id: row.admins_id });
  ElMessage.success("修改状态成功");
  proTable.value?.getTableList();
};
const batchDelete = (ids: Array<number>) => {
  ElMessageBox.confirm("确认要删除该用户吗?", "温馨提示", { type: "warning" }).then(async () => {
    await del({ ids });
    ElMessage.success("删除成功");
    proTable.value?.getTableList();
  });
};
</script>
<style lang="scss" scope>
@import "./index.scss";
</style>
