<template>
  <div>
    <el-button type="primary" size="small" @click="() => allChecked(true)">全选</el-button>
    <el-button type="primary" size="small" @click="() => allChecked(false)">清空</el-button>
    <div class="son" v-if="props.tree.length > 0">
      <Son
        ref="son"
        :default="defaultData"
        :mapping="props.mapping"
        :attach-mapping="props.attachMapping"
        :tree-data="props.tree"
      />
    </div>
  </div>
</template>
<script setup lang="ts" >
import { ref, computed } from "vue";
import Son from "./Son/index.vue";
const son = ref<InstanceType<typeof Son>>();
interface PropParams {
  mapping?: {
    title: string;
    id: string;
    children: string;
    attach: string;
  };
  attachMapping?: {
    attach_id: string;
    attach_name: string;
  };
  tree?: any;
  default?: { [key: string]: any };
}
const defaultData: { [key: string]: any } = computed(() => props.default);

const props = withDefaults(defineProps<PropParams>(), {
  mapping: () => {
    return {
      title: "title", //标题
      id: "id", //返回的id
      children: "children", //子标题
      attach: "attach"
    };
  },
  attachMapping: () => {
    return {
      attach_id: "attach_id", //标题
      attach_name: "attach_name" //返回的id
    };
  },
  tree: [],
  default: () => {
    return {};
  }
});
//深拷贝
const allChecked = (checked: boolean) => son.value?.setAllCheck(checked);

const getResult = (): { [key: string | number]: Array<string | number> } | any => son.value?.resultData();
const initData = () => {
  if (props.tree.length > 0) {
    son.value?.$nextTick(() => son.value?.initData(true));
  }
};
defineExpose({
  getResult,
  initData
});
</script>
<style scoped lang="scss">
@import "./index.scss";
</style>
