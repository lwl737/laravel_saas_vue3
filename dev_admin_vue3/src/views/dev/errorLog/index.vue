<template>
  <div class="table-box">
    <ProTable ref="proTable" title="错误日志" :columns="columns" :requestApi="getTableList" :row-key="`_id`">
      <!-- 表格 header 按钮 -->
      <!-- Expand -->
      <template #expand="scope">
        <div class="expand">
          <div class="expand-left">
            <span>
              错误信息: <span class="expand-params">{{ scope.row.error }}</span>
            </span>
          </div>

          <div class="expand-right">
            <div>请求参数:</div>
            <div class="params-content">
              <div v-for="(item, index) in scope.row.params" :key="index" class="expand-params interval">
                <span>{{ index }}: {{ item }}</span>
              </div>
            </div>
          </div>

          <div class="expand-left" v-show="scope.row.enum">
            <span>
              枚举: <span class="expand-params">{{ scope.row.enum }}</span>
            </span>
          </div>
        </div>
      </template>
    </ProTable>
  </div>
</template>

<script setup lang="tsx" name="DevErrorLogIndex">
import { ref } from "vue";
import { ColumnProps } from "@/components/ProTable/interface";
import ProTable from "@/components/ProTable/index.vue";
import { errorLogList, errorLogDate } from "@/api/modules/develop";

import { getDateTime } from "@/utils/util";

// 获取 ProTable 元素，调用其获取刷新数据方法（还能获取到当前查询参数，方便导出携带参数）
const proTable = ref();

// 如果表格需要初始化请求参数，直接定义传给 ProTable(之后每次请求都会自动带上该参数，此参数更改之后也会一直带上，改变此参数会自动刷新表格数据)
// dataCallback 是对于返回的表格数据做处理，如果你后台返回的数据不是 datalist && total && pageNum && pageSize 这些字段，那么你可以在这里进行处理成这些字段

// 如果你想在请求之前对当前请求参数做一些操作，可以自定义如下函数：params 为当前所有的请求参数（包括分页），最后返回请求列表接口
// 默认不做操作就直接在 ProTable 组件上绑定	:requestApi="getUserList"
const getTableList = async (params: any) => {
  params.ymd = getDateTime(params.ymd);
  return errorLogList(params);
};

const holidays = ref<Array<string>>([]);
const initErrorLogDate = async () => {
  const { date } = (await errorLogDate()).data;
  holidays.value = date.map((item: string): string => {
    return item.replace(/(\d{4})(\d{2})(\d{2})/, "$1-$2-$3");
  });
};
initErrorLogDate();

// 页面按钮权限（按钮权限既可以使用 hooks，也可以直接使用 v-auth 指令，指令适合直接绑定在按钮上，hooks 适合根据按钮权限显示不同的内容）
const isHoliday = ({ dayjs }: any) => {
  return holidays.value.includes(dayjs.format("YYYY-MM-DD"));
};
// 表格配置项
const columns: ColumnProps[] = [
  { type: "index", label: "#", width: 80 },
  { type: "expand", label: "错误和参数", width: 100 },
  {
    prop: "full_url",
    label: "错误api",
    width: 400
  },
  {
    prop: "method",
    label: "请求方式",
    width: 100
  },
  // 多级 prop
  { prop: "file", label: "错误文件", width: 300 },

  { prop: "line", label: "错误行数", width: 150 },
  { prop: "ip", label: "请求ip", width: 150 },
  {
    prop: "create_time",
    label: "错误时间",
    width: 200
  },
  {
    prop: "ymd",
    isShow: false,
    label: "日期",
    search: { el: "date-picker", defaultValue: getDateTime() },
    changeSearch: true,
    formRender: (scope: any, cell: any) => {
      return (
        <div class={["holiday-cell", cell.isCurrent ? "current" : ""]}>
          <span class="text">{cell.text}</span>
          <span v-show={isHoliday(cell)} class="holiday" />
        </div>
      );
    }
  }
];
</script>
<style lang="scss" scope>
@import "./index.scss";
</style>
