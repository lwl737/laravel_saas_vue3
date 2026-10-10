<template>
  <div class="card filter">
    <div class="card-container">
      <h4 class="title sle" v-if="title">{{ title }}</h4>
      <!-- <el-button type="primary" :icon="CirclePlus" @click="openDrawer('新增')">新增用户</el-button> -->
      <!-- {{typeof selectedRowId}} -->
      <div class="filterHeader"><slot name="filterHeader"></slot></div>

      <el-input v-model="filterText" placeholder="输入关键字进行过滤" clearable />
      <div
        v-loading="loading"
        class="el-scrollbar"
        :style="{ '--el-icon-show': icon ? `none` : `block`, height: title ? `calc(100% - 95px)` : `calc(100% - 56px)` }"
      >
        <el-tree
          v-if="!loading"
          ref="treeRef"
          class="custom-icons-tree"
          default-expand-all
          :node-key="id"
          :data="multiple ? treeData : treeAllData"
          :show-checkbox="multiple"
          :check-strictly="false"
          :current-node-key="!multiple ? selected : ''"
          :highlight-current="!multiple"
          :expand-on-click-node="false"
          :check-on-click-node="multiple"
          :props="defaultProps"
          :filter-node-method="filterNode"
          :default-checked-keys="multiple ? selected : []"
          @node-click="handleNodeClick"
          @check="handleCheckChange"
        >
          <template #default="{ node, data }">
            <div class="el-tree-part" :style="{ 'padding-left': icon ? '5px' : 0 }">
              <div class="el-tree-node__label" :title="node.label">
                <div v-if="icon" class="icon-cus">
                  <el-icon @click="node.expanded = !node.expanded" v-show="data.children && data.children.length > 0">
                    <component :is="node.expanded ? icon.open : icon.close"></component>
                  </el-icon>
                </div>
                <span class="overOne">{{ node.label }}</span>
              </div>

              <div v-if="rightMenuFilter(data)" class="handle-icon" @click.stop="() => {}">
                <el-dropdown>
                  <el-icon class="icon" title="更多"><MoreFilled /></el-icon>
                  <template #dropdown>
                    <el-dropdown-menu>
                      <el-dropdown-item @click="() => edit(data)">编辑</el-dropdown-item>
                      <el-dropdown-item @click="() => addSon(data)">添加子级</el-dropdown-item>
                      <el-dropdown-item @click="() => del(data)">删除</el-dropdown-item>
                      <!-- <el-dropdown-item @click.stop="() => edit(data)">编辑</el-dropdown-item>
											<el-dropdown-item @click.stop="() => addSon(data)">添加子级</el-dropdown-item>
											<el-dropdown-item @click.stop="() => del(data)">删除</el-dropdown-item> -->
                    </el-dropdown-menu>
                  </template>
                </el-dropdown>
              </div>
            </div>
          </template>
        </el-tree>
      </div>
    </div>
  </div>
</template>

<script setup lang="tsx" name="TreeFilter">
import { ref, watch, onMounted } from "vue";
import { ElTree } from "element-plus";
import { MoreFilled } from "@element-plus/icons-vue";

// 接收父组件参数并设置默认值
interface TreeFilterProps {
  requestApi?: (data?: any) => Promise<any>; // 请求分类数据的 api ==> 非必传
  data?: { [key: string]: any }[]; // 分类数据，如果有分类数据，则不会执行 api 请求 ==> 非必传
  title?: string; // treeFilter 标题 ==> 非必传
  id?: string; // 选择的id ==> 非必传，默认为 “id”
  label?: string; // 显示的label ==> 非必传，默认为 “label”
  multiple?: boolean; // 是否为多选 ==> 非必传，默认为 false
  defaultValue?: any; // 默认选中的值 ==> 非必传
  showAll?: boolean;
  rightMenuFilter?: (data: any) => boolean;
  icon?: {
    open: string;
    close: string;
  };
}
const props = withDefaults(defineProps<TreeFilterProps>(), {
  id: "id",
  label: "label",
  multiple: false,
  showAll: false,
  rightMenuFilter: () => true,
  icon: undefined
});

const defaultProps = {
  children: "children",
  label: props.label
};

const filterText = ref<string>("");

const treeRef = ref<InstanceType<typeof ElTree>>();
const treeData = ref<{ [key: string]: any }[]>([]);
const treeAllData = ref<{ [key: string]: any }[]>([]);
// 选中的值
const selected = ref();

const selectedRowId = ref<number | string>(0);

const edit = (data: any) => emit("edit", data);
const del = (data: any) => emit("del", data);
const addSon = (data: any) => emit("addSon", data);

onMounted(() => initData(true));

watch(filterText, val => {
  treeRef.value!.filter(val);
});

// 过滤
const filterNode = (value: string, data: { [key: string]: any }, node: any) => {
  if (!value) return true;
  let parentNode = node.parent,
    labels = [node.label],
    level = 1;
  while (level < node.level) {
    labels = [...labels, parentNode.label];
    parentNode = parentNode.parent;
    level++;
  }
  return labels.some(label => label.indexOf(value) !== -1);
};

interface FilterEmits {
  (e: "change", value: any): void;
  (event: "edit", data: any): void;
  (event: "handleNodeClickItem", item: any): void;
  (event: "del", data: any): void;
  (event: "addSon", data: any): void;
}
const emit = defineEmits<FilterEmits>();

const pre = ref<string | number | undefined>(undefined);

// 单选
const handleNodeClick = (data: { [key: string]: any }) => {
  if (props.multiple) return;
  if (pre.value !== data[props.id]) {
    selectedRowId.value = data[props.id];
    emit("change", data[props.id]);
    emit("handleNodeClickItem", data);
    pre.value = data[props.id];
  }
};

const getClickRowId = () => {
  return selectedRowId.value;
};

// 多选
const handleCheckChange = () => {
  emit("change", treeRef.value?.getCheckedKeys());
};

const loading = ref<boolean>(true);

const initData = async (first: boolean = false) => {
  if (props.multiple) selected.value = Array.isArray(props.defaultValue) ? props.defaultValue : [props.defaultValue];
  else selected.value = props.defaultValue;
  // 有数据就直接赋值，没有数据就执行请求函数
  // if (props.data?.length) {
  // 	treeData.value = props.data;
  // 	treeAllData.value = props.data;
  // 	return;
  // }
  loading.value = true;
  const { data } = await props.requestApi!();
  treeData.value = data;
  treeAllData.value = props.showAll === true ? [{ id: "", [props.label]: "全部" }, ...data] : data;

  if (!props.multiple && first === true && treeData.value.length > 0) {
    //第一次
    selected.value = treeData.value[0][props.id];
    handleNodeClick(treeData.value[0]);
  } else if (first === true) {
    selected.value = 0;
    selectedRowId.value = 0;
    emit("change", 0);
  }
  loading.value = false;
};

// 暴露给父组件使用
defineExpose({ treeData, treeAllData, initData, getClickRowId });
</script>

<style scoped lang="scss">
@import "./index.scss";
</style>
