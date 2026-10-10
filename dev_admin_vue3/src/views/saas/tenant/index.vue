<template>
  <div class="table-box">
    <ProTable ref="proTable" :columns="columns" :request-api="list" :row-key="`tenant_id`">
      <template #portrait="{ row }">
        <div class="video-cover">
          <Img :src="row.portrait.full_url" />
        </div>
      </template>

      <!-- 表格 header 按钮 -->
      <template #tableHeader="scope">
        <el-button type="primary" :icon="CirclePlus" @click="() => openFrom()" v-auth="`add`">新增租户</el-button>
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
        <el-button type="primary" link :icon="Link" @click="() => enter(scope.row)">进入</el-button>
        <el-button type="primary" link :icon="Link" @click="() => copy(scope.row)">复制链接</el-button>
        <el-button type="primary" link :icon="EditPen" v-auth="`edit`" @click="() => openFrom(scope.row)">编辑</el-button>
        <el-button type="primary" link :icon="Delete" v-auth="`delete`" @click="() => batchDelete([scope.row.tenant_id])">
          删除
        </el-button>
      </template>
    </ProTable>
    <Form ref="form" @close="close" />
  </div>
</template>
<script setup lang="tsx" name="SaasTenantIndex">
import { ref } from "vue";
import { ElMessage, ElMessageBox } from "element-plus";
import { ColumnProps } from "@/components/ProTable/interface";
import { CirclePlus, Delete, Aim, EditPen, Link } from "@element-plus/icons-vue";
import ProTable from "@/components/ProTable/index.vue";
import Form from "./components/form/index.vue";
import { list, editStatus, del } from "@/api/modules/tenant";
import { Tenant } from "@/api/interface/tenant";
import Img from "@/components/Img/index.vue";
import { useAuthStore } from "@/stores/modules/auth";
// import { useClipboard } from '@vueuse/core'

// 获取 ProTable 元素，调用其获取刷新数据方法（还能获取到当前查询参数，方便导出携带参数）
const proTable = ref<InstanceType<typeof ProTable>>();
const authStore = useAuthStore();
const form = ref<InstanceType<typeof Form>>();
// const copyText = ref<string>("");
// const {copy:copyHandle} = useClipboard();
// 表格配置项
const columns: ColumnProps[] = [
  { type: "selection", fixed: "left", width: 80 },
  { type: "index", label: "#", width: 80 },
  {
    prop: "tenant_name",
    label: "租户名称",
    search: { el: "input" }
  },
  {
    prop: "tenant_sort",
    label: "排序",
    width: 200
  },
  {
    prop: "created_time",
    label: "创建时间",
    width: 200
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
  {
    prop: "creating",
    label: "创建完成",
    render: scope => {
      return scope.row.creating ? "是" : "否";
    }
  },
  { prop: "operation", label: "操作", width: 320, fixed: "right" }
];
const openFrom = (row?: Tenant.List) => {
  row ? form.value?.open(row) : form.value?.open();
};
const close = () => {
  proTable.value?.getTableList();
};
const stautChange = async (nVal: 1 | 0, row: Tenant.List) => {
  if (!authStore.checkAuth(["edit"])) return ElMessage.warning("没有修改权限");
  await editStatus({ status: nVal, tenant_id: row.tenant_id });
  ElMessage.success("修改状态成功");
  proTable.value?.getTableList();
};
const batchDelete = (ids: Array<number>) => {
  ElMessageBox.confirm("确认要删除该租户吗?", "温馨提示", { type: "warning" }).then(async () => {
    await del({ ids });
    ElMessage.success("删除成功");
    proTable.value?.getTableList();
  });
};

const copy = (row: Tenant.List) => {
  if(!navigator?.clipboard?.writeText) return ElMessage.warning("当前域名不是https不支持复制");
  // copyText.value = row.tenant_url;
  // copyHandle(copyText.value);
  navigator.clipboard.writeText(row.tenant_url)
  ElMessage.success("复制成功");
};
const enter = (row: Tenant.List) => {
  window.open(row.tenant_url, '_blank');
};
</script>
<style lang="scss" scope>
@import "./index.scss";
</style>
