<template>
  <div class="main-box main-organi">
    <TreeFilter
      :title="organi_name"
      ref="treeFilter"
      :request-api="get"
      label="organi_name"
      id="organi_id"
      :defaultvalue="initParam.organi_id"
      @change="changeTreeFilter"
    >
      <template #filterHeader>
        <div class="flex">
          <el-button v-auth="['add']" type="primary" :icon="CirclePlus" @click="() => openDrawer()">添加部门</el-button>
          <el-button type="primary" :icon="RefreshRight" title="刷新" @click="() => refresh()">刷新</el-button>
        </div>
      </template>
      <template #lineRight="{ data }">
        <el-dropdown>
          <el-icon class="icon" title="更多"><MoreFilled /></el-icon>
          <template #dropdown>
            <el-dropdown-menu>
              <el-dropdown-item v-if="authStore.checkAuth(['edit'])" @click="() => treeEdit(data)">编辑</el-dropdown-item>
              <el-dropdown-item v-if="authStore.checkAuth(['add'])" @click="() => addSon(data)">添加子级</el-dropdown-item>
              <el-dropdown-item v-if="authStore.checkAuth(['delete'])" @click="() => treeDel(data)">删除</el-dropdown-item>
            </el-dropdown-menu>
          </template>
        </el-dropdown>
      </template>
    </TreeFilter>
    <div class="table-box organi-box">
      <ProTable ref="proTable" :columns="columns" :request-api="tabList" :row-key="`admins_id`">
        <template #portrait="{ row }">
          <div class="video-cover">
            <Img :src="row.portrait.full_url" />
          </div>
        </template>
      </ProTable>
    </div>
    <OrganiDrawer ref="organiDrawer" @close="close"></OrganiDrawer>
  </div>
</template>

<script setup lang="tsx" name="OrganiIndex">
import { reactive, ref, watch } from "vue";
import TreeFilter from "@/components/TreeFilter/index.vue";
import { CirclePlus, RefreshRight } from "@element-plus/icons-vue";
import { all, del } from "@/api/modules/organi";
import OrganiDrawer from "./components/OrganiDrawer/index.vue";
import { Organi } from "@/api/interface/organi";
import { ElMessage, ElMessageBox } from "element-plus";
import { useAuthStore } from "@/stores/modules/auth";
import { ColumnProps } from "@/components/ProTable/interface";
import ProTable from "@/components/ProTable/index.vue";
import { list, roleAll } from "@/api/modules/admin";
import Img from "@/components/Img/index.vue";

const organi = ref<Organi.OrganiAllRes["tree"] | undefined>(undefined);
const organi_name = ref<string>("");
const proTable = ref();

const authStore = useAuthStore();

const columns: ColumnProps[] = [
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
      return scope.row.status ? `启用` : `禁用`;
    }
  }
];

const tabList = (config: any) => {
  config.organi_id = initParam.organi_id ? [initParam.organi_id] : undefined;
  return config.organi_id
    ? list(config)
    : new Promise(reslove => reslove({ data: { list: [], total: 0, pageNum: config.pageNum, pageSize: config.pageSize } }));
};

const get = () => {
  return new Promise(reslove => {
    all().then(res => {
      organi.value = res.data.tree;
      organi_name.value = res.data.organi_name;
      reslove({ data: res.data.tree });
    });
  });
};

const initParam = reactive<{ organi_id: number }>({
  organi_id: 0
});

watch(
  () => initParam.organi_id,
  () => {
    proTable.value.getTableList();
  }
);

const treeFilter = ref<InstanceType<typeof TreeFilter>>();

const organiDrawer = ref<InstanceType<typeof OrganiDrawer>>();

const changeTreeFilter = (val: number) => {
  initParam.organi_id = val;
  // proTable.value.getTableList();
};

const close = () => {
  treeFilter.value?.initData(false, initParam.organi_id);
};

const treeEdit = (item: Organi.OrganiItem) => {
  organiDrawer.value?.open(item.organi_pid, item.organi_id, {
    organi_name: item.organi_name,
    organi_sort: item.organi_sort,
    organi_pid: item.organi_pid
  });
};

const treeDel = (item: Organi.OrganiItem) => {
  ElMessageBox.confirm("确认要删除该组织吗?", "温馨提示", { type: "warning" }).then(async () => {
    del({ organi_id: item.organi_id }).then(res => {
      let rowId = treeFilter.value?.getClickRowId();
      treeFilter.value?.initData(false, item.organi_id === rowId ? 0 : rowId);
      ElMessage.success("删除成功");
    });
  });
};

const addSon = (item: Organi.OrganiItem) => {
  organiDrawer.value?.open(item.organi_id, 0, null);
};

const openDrawer = () => {
  organiDrawer.value?.open(0, 0, null);
};

const refresh = () => {
  treeFilter.value?.initData(false, treeFilter.value?.getClickRowId());
};
</script>

<style scoped lang="scss">
@import "./index.scss";
</style>
