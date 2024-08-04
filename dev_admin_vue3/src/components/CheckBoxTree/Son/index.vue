<template>
  <div class="check-group">
    <div v-for="(item, index) in tree" :key="pIndex + '-' + index">
      <div class="check-group-part">
        <div class="angle" :style="{ 'margin-left': level * 20 + 'px' }" v-show="level !== 0"></div>
        <el-checkbox
          :label="mappingFunc(item, mapping.id)"
          @change="(value: any) => handleCheckChange(value, index)"
          v-model="item.checked"
        >
          {{ mappingFunc(item, mapping.title) }}
        </el-checkbox>

        <div v-if="item[mapping.attach]" class="attach">
          <el-checkbox
            v-model="items.checked"
            @change="(value: any) => handleAttachChange(value, index)"
            v-for="(items, keys) in item[mapping.attach]"
            :key="`attach-${pIndex}-${index}-${keys}`"
            :label="items[attachMapping.attach_id]"
          >
            {{ items[attachMapping.attach_name] }}
          </el-checkbox>
        </div>
      </div>

      <div v-if="item[mapping.children] && item[mapping.children].length > 0">
        <CheckBoxTree
          :ref="(el: Element) => setItemRef(el, `checkBoxTree-${pIndex}-${index}`)"
          :level="level + 1"
          :mapping="mapping"
          :default="props.default"
          :attach-mapping="attachMapping"
          :tree-data="item[mapping.children]"
          :p-index="pIndex + '-' + index"
          @check-clear="sonCheckClear(index)"
          @on-check-son="onCheckSon(index)"
        />
      </div>
    </div>
  </div>
</template>
<script lang="ts" setup name="CheckBoxTree">
import { ref, onBeforeMount } from "vue";
interface PropParams {
  level?: number;
  pIndex?: string;
  default?: { [key: string]: any };
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
  treeData: any;
}

const props = withDefaults(defineProps<PropParams>(), {
  level: 0,
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
  pIndex: "0",
  treeData: [],
  default: () => {
    return {};
  }
});

const tree = ref<any>(props.treeData);

const initData = (recursion: boolean = false) => {
  tree.value = tree.value.map((item: any, index: number) => {
    item.checked = props.default.hasOwnProperty(item[props.mapping.id]) ? true : false;

    item[props.mapping.attach] = item.hasOwnProperty(props.mapping.attach)
      ? item[props.mapping.attach].map((items: any) => {
          items.checked =
            item.checked === true && props.default[item[props.mapping.id]].indexOf(items[props.attachMapping.attach_id]) !== -1
              ? true
              : false;

          return items;
        })
      : [];
    if (recursion === true && tree.value[index][props.mapping.children] && tree.value[index][props.mapping.children].length > 0)
      checkBoxRefs[`checkBoxTree-${props.pIndex}-${index}`].$nextTick(() =>
        checkBoxRefs[`checkBoxTree-${props.pIndex}-${index}`].initData(recursion)
      );

    return item;
  });
};
onBeforeMount(() => {
  initData();
});

const checkBoxRefs: { [key: string]: any } = {};
const setItemRef = (el: Element, key: string) => {
  if (el) {
    checkBoxRefs[key] = el;
  }
};

const emit = defineEmits(["onCheckSon", "checkClear"]);

const mappingFunc = (item: any, keys: string) => {
  let keysArr: Array<string> = keys.split(".");
  return keysArr.reduce(
    (previousValue, currentValue) => (previousValue[currentValue] ? previousValue[currentValue] : previousValue),
    item
  );
};
const handleCheckChange = (value: boolean, index: number) => {
  if (value === false) {
    //取消树
    if (tree.value[index][props.mapping.children] && tree.value[index][props.mapping.children].length > 0)
      checkBoxRefs[`checkBoxTree-${props.pIndex}-${index}`].setAllCheck(false);

    tree.value.reduce((previousValue: number, currentValue: any) => {
      if (currentValue.checked === true) previousValue++;
      return previousValue;
    }, 0) === 0 &&
      props.level !== 0 &&
      emit("checkClear"); //全部清空
    if (tree.value[index][props.mapping.attach]) {
      //存在附加的权限
      for (let i = 0; i < tree.value[index][props.mapping.attach].length; i++) {
        tree.value[index][props.mapping.attach][i].checked = false;
      }
    }
  } else {
    //选中树
    if (tree.value[index][props.mapping.children] && tree.value[index][props.mapping.children].length > 0) {
      checkBoxRefs[`checkBoxTree-${props.pIndex}-${index}`].setAllCheck(true);
    }
    props.level !== 0 && emit("onCheckSon");
    if (tree.value[index][props.mapping.attach]) {
      //存在附加的权限
      for (let i = 0; i < tree.value[index][props.mapping.attach].length; i++) {
        tree.value[index][props.mapping.attach][i].checked = true;
      }
    }
  }
};

const onCheckSon = (index: number) => {
  tree.value[index].checked = true;
  props.level !== 0 && emit("onCheckSon"); //全部选中
};
const sonCheckClear = (index: number) => {
  tree.value[index].checked = false;
  tree.value.reduce((previousValue: number, currentValue: any) => {
    if (currentValue.checked === true) previousValue++;
    return previousValue;
  }, 0) === 0 &&
    props.level !== 0 &&
    emit("checkClear");
};

const setAllCheck = (checked: boolean) => {
  for (let i = 0; i < tree.value.length; i++) tree.value[i].checked = checked;
  for (let i = 0; i < tree.value.length; i++) {
    if (tree.value[i][props.mapping.children] && tree.value[i][props.mapping.children].length > 0) {
      checkBoxRefs[`checkBoxTree-${props.pIndex}-${i}`].setAllCheck(checked);
    }
    if (tree.value[i][props.mapping.attach]) {
      for (let j = 0; j < tree.value[i][props.mapping.attach].length; j++) {
        tree.value[i][props.mapping.attach][j].checked = checked;
      }
    }
  }
};

const handleAttachChange = (checked: boolean, index: number) => {
  if (checked === true && tree.value[index].checked !== true) {
    tree.value[index].checked = true;
    props.level !== 0 && emit("onCheckSon");
  }
};

const resultData = (): { [key: string | number]: Array<number | string> } => {
  let resData: { [key: string | number]: Array<number | string> } = {};
  for (let i = 0; i < tree.value.length; i++) {
    if (tree.value[i].checked === true) {
      let path: string = mappingFunc(tree.value[i], props.mapping.id);
      resData[path] = [];
      if (tree.value[i][props.mapping.attach]) {
        //附加
        tree.value[i][props.mapping.attach].map((item: { [key: string]: any; checked: boolean }) => {
          if (item.checked === true) resData[path].push(item[props.attachMapping.attach_id]);
        });
      }
      if (tree.value[i][props.mapping.children] && tree.value[i][props.mapping.children].length > 0) {
        resData = Object.assign(resData, checkBoxRefs[`checkBoxTree-${props.pIndex}-${i}`].resultData());
      }
    }
  }
  return resData;
};

defineExpose({
  setAllCheck,
  resultData,
  initData
});
</script>

<style lang="scss" scoped>
@import "./index.scss";
</style>
