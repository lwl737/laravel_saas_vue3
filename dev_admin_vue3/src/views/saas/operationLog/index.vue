<template>
  <div class="table-box">
    <ProTable
      :key="tenant_list.length"
      ref="proTable"
      title="操作日志"
      :columns="columns"
      :requestApi="getTableList"
      :row-key="`log_id`"
    >
    </ProTable>
  </div>
</template>

<script setup lang="tsx" name="SaasOperationLogIndex">
import { ref, onMounted, computed } from "vue";
import { ColumnProps } from "@/components/ProTable/interface";
import ProTable from "@/components/ProTable/index.vue";
import { list } from "@/api/modules/saasOperationLog";
import { SaasOperationLog } from "@/api/interface/saasOperationLog";
import { getDateTime } from "@/utils/util";
import { all as tenant } from "@/api/modules/tenant";
import { Tenant } from "@/api/interface/tenant";

// 获取 ProTable 元素，调用其获取刷新数据方法（还能获取到当前查询参数，方便导出携带参数）
const proTable = ref();
const tenant_id = ref<number | null>(null);
const tenant_list = ref<Tenant.All["all"]>([]);
// 如果表格需要初始化请求参数，直接定义传给 ProTable(之后每次请求都会自动带上该参数，此参数更改之后也会一直带上，改变此参数会自动刷新表格数据)
// dataCallback 是对于返回的表格数据做处理，如果你后台返回的数据不是 datalist && total && pageNum && pageSize 这些字段，那么你可以在这里进行处理成这些字段

// 如果你想在请求之前对当前请求参数做一些操作，可以自定义如下函数：params 为当前所有的请求参数（包括分页），最后返回请求列表接口
// 默认不做操作就直接在 ProTable 组件上绑定	:requestApi="getUserList"
const getTableList = async (params: SaasOperationLog.ListReq) => {
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
  if (params.operation_time) params.operation_time = getDateTime(params.operation_time);
  let res = await list({ ...params });
  tenant_id.value = params.tenant_id;
  return res;
};

onMounted(() => {
  tenant().then(res => {
    tenant_id.value = res.data.all.length > 0 ? res.data.all[0].tenant_id : null;
    tenant_list.value = res.data.all;
  });
});

// 表格配置项
const columns = computed((): ColumnProps[] => {
  return [
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
    { prop: "log_id", label: "id", width: 80, search: { el: "input" } },
    {
      prop: "operation_time",
      label: "操作时间",
      search: { el: "date-picker" },
      changeSearch: true,
      formRender: (scope: any, cell: any) => {
        return (
          <div class={["holiday-cell", cell.isCurrent ? "current" : ""]}>
            <span class="text">{cell.text}</span>
          </div>
        );
      }
    },
    {
      prop: "menu_title",
      label: "栏目",
      search: { el: "input" },
      width: 200
    },
    {
      prop: "auth_name",
      label: "操作",
      search: { el: "input" },
      width: 150
    },

    {
      prop: "insert_organi_name",
      label: "所属部门",
      search: { el: "input" },
      width: 150
    },
    // 多级 prop
    // { prop: "nick_name", label: "昵称", width: 200, search: { el: "input" } },

    { prop: "real_name", label: "真实姓名", width: 200, search: { el: "input" } },
    { prop: "ip", label: "请求ip", width: 150, search: { el: "input" } }
  ];
});
</script>
<style lang="scss" scope>
@import "./index.scss";
</style>
