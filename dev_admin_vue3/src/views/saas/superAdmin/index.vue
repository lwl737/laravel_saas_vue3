<template>
  <div class="table-box">
    <ProTable
      ref="proTable"
      :key="tenant_list?.length"
      :columns="columns"
      :request-api="superAdminListApi"
      :row-key="`admins_id`"
    >
      <template #portrait="{ row }">
        <div class="video-cover">
          <Img :src="row.portrait.full_url" />
        </div>
      </template>

      <!-- 表格 header 按钮 -->
      <template #tableHeader="scope">
        <el-button type="primary" v-show="tenant_id" :icon="CirclePlus" @click="() => openFrom()">新增超级管理员</el-button>
        <el-button type="primary" :icon="Aim" @click="() => proTable?.element?.toggleAllSelection()">全选 / 全不选</el-button>
        <el-button
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
        <el-button type="primary" link :icon="EditPen" @click="() => openFrom(scope.row)">编辑</el-button>
        <el-button type="primary" link :icon="Delete" @click="() => batchDelete([scope.row.admins_id])"> 删除 </el-button>
      </template>
    </ProTable>
    <Form ref="form" @close="close" />
  </div>
</template>
<script setup lang="tsx" name="SaasSuperAdminIndex">
import { ref, onMounted, computed } from "vue";
import { ElMessage, ElMessageBox } from "element-plus";
import { ColumnProps } from "@/components/ProTable/interface";
import { CirclePlus, Delete, Aim, EditPen } from "@element-plus/icons-vue";
import ProTable from "@/components/ProTable/index.vue";
import Form from "./components/form/index.vue";
import { superAdminList, superAdminStatus, superAdminDel } from "@/api/modules/saasSuperAdmin";
import { all as tenant } from "@/api/modules/tenant";
import { SaasSuperAdmin } from "@/api/interface/saasSuperAdmin";
import { Tenant } from "@/api/interface/tenant";
import Img from "@/components/Img/index.vue";

// 获取 ProTable 元素，调用其获取刷新数据方法（还能获取到当前查询参数，方便导出携带参数）
const proTable = ref<InstanceType<typeof ProTable>>();
const form = ref<InstanceType<typeof Form>>();
const tenant_id = ref<number | null>(null);
const tenant_list = ref<Tenant.All["all"]>([]);

onMounted(() => {
  tenant().then(res => {
    tenant_id.value = res.data.all.length > 0 ? res.data.all[0].tenant_id : null;
    tenant_list.value = res.data.all;
  });
});

const superAdminListApi = async (params: SaasSuperAdmin.PageReq) => {
  if (!params.tenant_id) {
    return new Promise(resolve => {
      resolve({
        errCode: 0,
        data: {
          list: [],
          total: 0,
          pageNum: params.pageNum,
          pageSize: params.pageSize
        }
      });
    });
  }
  let res = await superAdminList({ ...params });
  tenant_id.value = params.tenant_id;
  return res;
};

// 表格配置项
const columns = computed((): ColumnProps[] => {
  return [
    { type: "selection", fixed: "left", width: 80 },
    { type: "index", label: "#", width: 80 },
    {
      prop: "tenant_id",
      label: "租户",
      isShow: false,
      enum: () => {
        return new Promise(async reslove => {
          reslove({ data: tenant_list.value });
        });
      },
      changeSearch: true,
      fieldNames: { label: "tenant_name", value: "tenant_id" },
      search: { el: "select", defaultValue: tenant_id.value ? tenant_id.value : undefined }
    },
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
});
const openFrom = (row?: SaasSuperAdmin.List) => {
  if (!tenant_id.value) return ElMessage.error("请选择租户");
  row
    ? form.value?.open({ ...row, portrait: row.portrait.value }, tenant_id.value)
    : form.value?.open(undefined, tenant_id.value);
};
const close = () => {
  proTable.value?.getTableList();
};
const stautChange = async (nVal: 1 | 0, row: SaasSuperAdmin.List) => {
  if (!tenant_id.value) return ElMessage.error("请选择租户");
  await superAdminStatus({ status: nVal, admins_id: row.admins_id, tenant_id: tenant_id.value });
  ElMessage.success("修改状态成功");
  proTable.value?.getTableList();
};
const batchDelete = (ids: Array<number>) => {
  ElMessageBox.confirm("确认要删除该用户吗?", "温馨提示", { type: "warning" }).then(async () => {
    if (!tenant_id.value) return ElMessage.error("请选择租户");
    await superAdminDel({ ids, tenant_id: tenant_id.value });
    ElMessage.success("删除成功");
    proTable.value?.getTableList();
  });
};
</script>
<style lang="scss" scope>
@import "./index.scss";
</style>
