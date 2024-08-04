<template>
  <div class="main-box">
    <TreeFilter
      title="页面列表"
      ref="treeFilter"
      :request-api="menuAll"
      label="title"
      id="menu_id"
      :defaultvalue="initParam.menu_id"
      @change="changeTreeFilter"
    >
      <template #filterHeader>
        <el-button type="primary" :icon="CirclePlus" v-auth="'add'" @click="() => openDrawer()">添加页面</el-button>
      </template>
      <template #lineRight="{ data }">
        <el-dropdown>
          <el-icon class="icon" title="更多"><MoreFilled /></el-icon>
          <template #dropdown>
            <el-dropdown-menu>
              <el-dropdown-item v-if="authStore.checkAuth('edit')" @click="() => treeEdit(data)">编辑</el-dropdown-item>
              <el-dropdown-item v-if="authStore.checkAuth('add')" @click="() => addSon(data)">添加子级</el-dropdown-item>
              <el-dropdown-item v-if="authStore.checkAuth('delete')" @click="() => treeDel(data)">删除</el-dropdown-item>
            </el-dropdown-menu>
          </template>
        </el-dropdown>
      </template>
    </TreeFilter>
    <div class="table-boxs">
      <el-tabs type="border-card" v-model="tabPane" @tab-change="reloadTable">
        <el-tab-pane name="apiTable">
          <template #label>
            <span class="custom-tabs-label">
              <span>公共API</span>
            </span>
          </template>
          <ProTable
            ref="apiTable"
            title="权限列表"
            :columns="apiColumns"
            row-key="auth_id"
            :request-api="menuApiListLoad"
            :search-col="{ xs: 1, sm: 1, md: 2, lg: 3, xl: 3 }"
          >
            <!-- 表格 header 按钮 -->
            <template #tableHeader>
              <el-button
                v-auth="'add'"
                type="primary"
                :icon="CirclePlus"
                @click="() => openMenuApiDialog()"
                v-show="initParam.menu_id !== 0"
              >
                新增api
              </el-button>
            </template>
            <!-- 表格操作 -->
            <template #operation="scope">
              <el-button
                type="primary"
                link
                :icon="EditPen"
                v-auth="'edit'"
                @click="() => openMenuApiDialog({ ...scope.row, api: scope.row.api.value })"
              >
                编辑
              </el-button>
              <el-button type="primary" link :icon="Delete" @click="() => menuApiDel(scope.row)">删除</el-button>
            </template>
          </ProTable>
        </el-tab-pane>
        <el-tab-pane label="权限" name="proTable">
          <div class="card-tabs">
            <ProTable
              ref="proTable"
              title="权限列表"
              :columns="columns"
              row-key="auth_id"
              :request-api="menuAuthListLoad"
              :search-col="{ xs: 1, sm: 1, md: 2, lg: 3, xl: 3 }"
            >
              <!-- 表格 header 按钮 -->
              <template #expand="scope">
                <div class="expand">
                  <div class="table-s">
                    <el-table :data="scope.row.buttons" border>
                      <el-table-column prop="buttons" label="按钮权限标识" />
                      <el-table-column prop="operation" label="操作" width="150">
                        <template #default="buttons">
                          <el-button
                            v-auth="'edit'"
                            type="primary"
                            link
                            :icon="EditPen"
                            @click="() => openButtons(scope.row, buttons.row)"
                          >
                            编辑
                          </el-button>
                          <el-button
                            v-auth="'delete'"
                            type="primary"
                            link
                            :icon="Delete"
                            @click="() => authSonDel('buttons', buttons.row)"
                          >
                            删除
                          </el-button>
                        </template>
                      </el-table-column>
                    </el-table>
                  </div>
                  <div class="table-s">
                    <el-table :data="scope.row.api" border>
                      <el-table-column prop="api" label="api">
                        <template #default="scoped"> {{ scoped.row.api.full_url }}</template>
                      </el-table-column>
                      <el-table-column prop="add_log" label="添加日志" width="120">
                        <template #default="scoped">
                          <el-switch
                            v-model="scoped.row.add_log"
                            :active-value="1"
                            :inactive-value="0"
                            :active-text="`是`"
                            :inactive-text="`否`"
                            @change="(value:any) => addLogChange(value, scoped.row.api_id)"
                          />
                        </template>
                      </el-table-column>
                      <el-table-column prop="operation" label="操作" width="150">
                        <template #default="api">
                          <el-button
                            v-auth="'edit'"
                            type="primary"
                            link
                            :icon="EditPen"
                            @click="() => openApi(scope.row, api.row)"
                          >
                            编辑
                          </el-button>
                          <el-button
                            v-auth="'delete'"
                            type="primary"
                            link
                            :icon="Delete"
                            @click="() => authSonDel('api', api.row)"
                            >删除</el-button
                          >
                        </template>
                      </el-table-column>
                    </el-table>
                  </div>
                </div>

                <div class="table-b" v-show="scope.row.pages && scope.row.pages.length > 0">
                  <el-table :data="scope.row.pages" border>
                    <el-table-column prop="path" label="页面地址" />
                    <el-table-column prop="component" label="组件路径" />
                    <el-table-column prop="title" label="栏目名称" />
                    <el-table-column prop="operation" label="操作" width="150">
                      <template #default="pages">
                        <el-button
                          v-auth="'edit'"
                          type="primary"
                          link
                          :icon="EditPen"
                          @click="() => openPages(scope.row, pages.row)"
                        >
                          编辑
                        </el-button>
                        <el-button
                          v-auth="'delete'"
                          type="primary"
                          link
                          :icon="Delete"
                          @click="() => authSonDel('pages', pages.row)"
                        >
                          删除
                        </el-button>
                      </template>
                    </el-table-column>
                  </el-table>
                </div>
              </template>

              <!-- 表格 header 按钮 -->
              <template #tableHeader>
                <el-button
                  v-auth="'add'"
                  type="primary"
                  :icon="CirclePlus"
                  @click="() => openDialog()"
                  v-show="initParam.menu_id !== 0"
                >
                  新增权限
                </el-button>
              </template>
              <!-- 表格操作 -->
              <template #operation="scope">
                <el-button type="primary" link :icon="Plus" @click="() => openButtons(scope.row)">添加按钮</el-button>
                <el-button type="primary" link :icon="Plus" @click="() => openApi(scope.row)">添加api</el-button>
                <el-button type="primary" link :icon="Plus" @click="() => openPages(scope.row)">添加页面</el-button>
                <el-button type="primary" link :icon="EditPen" @click="() => openDialog(scope.row)">编辑</el-button>
                <el-button type="primary" link :icon="Delete" @click="() => authDel(scope.row)">删除</el-button>
              </template>
            </ProTable>
          </div>
        </el-tab-pane>
      </el-tabs>
    </div>
    <PagesFormDrawer ref="pagesFormDrawer" @close="close" />
    <AuthFormDialog ref="authFormDialog" @close="dialogClose" />
    <ButtonsFormDialog ref="buttonsFormDialog" @close="dialogClose" />
    <ApiFormDialog ref="apiFormDialog" @close="dialogClose" />
    <PagesFormDialog ref="pagesFormDialog" @close="dialogClose" />
    <MenuApiFormDialog ref="menuApiFormDialog" @close="menuApiDialogClose" />
  </div>
</template>
<script setup lang="tsx" name="SaasDevMenuIndex">
import { ref, reactive } from "vue";
import { Develop } from "@/api/interface/develop";
import { ElMessage, ElMessageBox } from "element-plus";
import { ColumnProps } from "@/components/ProTable/interface";
import ProTable from "@/components/ProTable/index.vue";
import TreeFilter from "@/components/TreeFilter/index.vue";
import { CirclePlus, Delete, EditPen, Plus, MoreFilled } from "@element-plus/icons-vue";
import { ResultEnum } from "@/enums/httpEnum";
import {
  menuAll,
  menuDel,
  menuAuthList,
  menuAuthDel,
  menuAuthApiDel,
  menuAuthButtonsDel,
  menuAuthApiAddLog,
  menuAuthPagesDel,
  menuApiList,
  menuApiAddLog,
  menuApiDel as menuApiDelete
} from "@/api/modules/saasDevelopMenu";

import PagesFormDrawer from "./components/PagesFormDrawer.vue";
import AuthFormDialog from "./components/AuthFormDialog.vue";
import ButtonsFormDialog from "./components/ButtonsFormDialog.vue";
import ApiFormDialog from "./components/ApiFormDialog.vue";
import PagesFormDialog from "./components/PagesFormDialog.vue";
import MenuApiFormDialog from "./components/MenuApiFormDialog.vue";
import { useAuthStore } from "@/stores/modules/auth";
const authStore = useAuthStore();
// 获取 ProTable 元素，调用其获取刷新数据方法（还能获取到当前查询参数，方便导出携带参数）
const proTable = ref();
const apiTable = ref();
const tabPane = ref<"apiTable" | "proTable">("apiTable");
// 如果表格需要初始化请求参数，直接定义传给 ProTable(之后每次请求都会自动带上该参数，此参数更改之后也会一直带上，改变此参数会自动刷新表格数据)
const initParam = reactive<{ menu_id: number }>({
  menu_id: 0
});

// 树形筛选切换
const changeTreeFilter = (val: number) => {
  proTable.value.pageable.pageNum = 1;
  apiTable.value.pageable.pageNum = 1;
  initParam.menu_id = val;
  // console.log(val,'val');
  reloadTable();
};

const reloadTable = () => {
  switch (tabPane.value) {
    case "apiTable":
      apiTable.value.getTableList();
      break;
    case "proTable":
      proTable.value.getTableList();
      break;
  }
};

const menuAuthListLoad = (params: any) => {
  params.menu_id = initParam.menu_id;
  if (params.menu_id > 0) return menuAuthList(params);
  else
    return new Promise(res => {
      res({
        code: ResultEnum.SUCCESS,
        msg: "查询成功",
        data: { list: [], total: 0, pageNum: params.pageNum, pageSize: params.pageSize }
      });
    });
};
const menuApiListLoad = (params: any) => {
  params.menu_id = initParam.menu_id;
  if (params.menu_id > 0) return menuApiList(params);
  else
    return new Promise(res => {
      res({
        code: ResultEnum.SUCCESS,
        msg: "查询成功",
        data: { list: [], total: 0, pageNum: params.pageNum, pageSize: params.pageSize }
      });
    });
};

// 表格配置项
const columns: ColumnProps[] = [
  { type: "index", label: "#", width: 80 },
  { type: "expand", label: "按钮、api、页面", width: 150 },
  { prop: "auth_name", label: "权限名称" },
  { prop: "auth_sort", label: "排序" },
  { prop: "operation", label: "操作", width: 450 }
];

// 表格配置项
const apiColumns: ColumnProps[] = [
  { type: "index", label: "#", width: 80 },
  {
    prop: "api",
    label: "api",
    render: scope => {
      return <span>{scope.row.api.full_url}</span>;
    }
  },
  { prop: "api_name", label: "接口名称" },
  {
    prop: "add_log",
    label: "添加日志",
    render: scope => {
      return (
        <el-switch
          v-model={scope.row.add_log}
          active-text="启用"
          active-value={1}
          inactive-value={0}
          inactive-text="禁用"
          onChange={(val: 1 | 0) => menuApiAddLogChange(val, scope.row.api_id)}
        />
      );
    },
    width: 200
  },
  { prop: "operation", label: "操作", width: 200 }
];

// 删除权限
const authDel = async (params: Develop.Menu.Auth.List) => {
  ElMessageBox.confirm("确认要删除该权限吗?", "温馨提示", { type: "warning" }).then(async () => {
    await menuAuthDel({ auth_id: params.auth_id });
    ElMessage.success("删除成功");
    proTable.value.getTableList();
  });
};

/* ref 组件 */
const treeFilter = ref<InstanceType<typeof TreeFilter>>();
const authFormDialog = ref<InstanceType<typeof AuthFormDialog>>();
const buttonsFormDialog = ref<InstanceType<typeof ButtonsFormDialog>>();
const apiFormDialog = ref<InstanceType<typeof ApiFormDialog>>();
const pagesFormDialog = ref<InstanceType<typeof PagesFormDialog>>();
const menuApiFormDialog = ref<InstanceType<typeof MenuApiFormDialog>>();

const close = () => treeFilter.value?.initData();
const dialogClose = () => proTable.value.getTableList();
const menuApiDialogClose = () => apiTable.value.getTableList();
// 打开 drawer(新增、查看、编辑)
const pagesFormDrawer = ref<InstanceType<typeof PagesFormDrawer>>();
const openDrawer = () => {
  pagesFormDrawer.value?.open();
};
const openDialog = (data: any = null) => {
  authFormDialog.value?.open(initParam.menu_id, data);
};

const openMenuApiDialog = (data: Develop.Menu.Api.List | null = null) => {
  menuApiFormDialog.value?.open(initParam.menu_id, data);
};
const menuApiDel = (data: Develop.Menu.Api.List) => {
  ElMessageBox.confirm("确认要删除该接口吗?", "温馨提示", { type: "warning" }).then(async () => {
    await menuApiDelete({ api_id: data.api_id });
    ElMessage.success("删除成功");
    apiTable.value.getTableList();
  });
};

const openButtons = (data: Develop.Menu.Auth.List, buttons: Develop.Menu.Auth.ButtonsList | null = null) => {
  buttonsFormDialog.value?.open(data.auth_id, buttons);
};
const openApi = (data: Develop.Menu.Auth.List, api: Develop.Menu.Auth.Api.List | null = null) => {
  apiFormDialog.value?.open(data.auth_id, api === null ? api : { ...api, api: api.api.value }, initParam.menu_id);
};
const openPages = (data: Develop.Menu.Auth.List, pages: Develop.Menu.Auth.PagesList | null = null) => {
  let menu_pid = treeFilter.value?.getClickRowId();
  // console.log(menu_pid);
  if (menu_pid !== undefined) pagesFormDialog.value?.open(Number(menu_pid), data.auth_id, pages?.menu_id, pages);
};
//Develop.Menu.Auth.ButtonsList | Develop.Menu.Auth.ApiList | Develop.Menu.Auth.PagesList
const authSonDel = (type: "buttons" | "api" | "pages", data: any) => {
  ElMessageBox.confirm("确认要删除该栏目吗?", "温馨提示", { type: "warning" }).then(async () => {
    switch (type) {
      case "buttons":
        await menuAuthButtonsDel({ buttons_id: data.buttons_id });
        break;
      case "api":
        await menuAuthApiDel({ api_id: data.api_id });
        break;
      case "pages":
        await menuAuthPagesDel({ menu_id: data.menu_id });
        break;
    }
    ElMessage.success("删除成功");
    proTable.value.getTableList();
  });
};

const treeDel = (data: any) => {
  ElMessageBox.confirm("确认要删除该栏目吗?", "温馨提示", { type: "warning" }).then(async () => {
    await menuDel({ menu_id: data.menu_id });
    ElMessage.success("删除成功");
    treeFilter.value?.initData(initParam.menu_id === data.menu_id);
  });
};
const treeEdit = (data: any) => {
  pagesFormDrawer.value?.open(0, data.menu_id, data);
};
const addSon = (data: any) => {
  pagesFormDrawer.value?.open(data.menu_id);
};

const addLogChange = async (value: 1 | 0, api_id: number) => {
  if (!authStore.checkAuth("edit")) return ElMessage.error("没有编辑权限");
  await menuAuthApiAddLog({ add_log: value }, api_id);
  ElMessage.success("编辑成功");
};
const menuApiAddLogChange = async (value: 1 | 0, api_id: number) => {
  await menuApiAddLog({ add_log: value, api_id });
  ElMessage.success("编辑成功");
};
</script>
<style lang="scss" scoped>
@import "./index.scss";
</style>
