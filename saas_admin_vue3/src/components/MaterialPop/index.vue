<template>
  <div class="up-image thumb">
    <img class="none" @click="open" v-show="!showValues" src="@/assets/images/add-img.png" />
    <img class="img-value" v-show="showValues" :src="showValues" />
    <img
      class="img-clearable"
      title="删除"
      v-show="clearable && showValues"
      @click.stop="clear"
      src="@/assets/images/img-close.png"
    />
  </div>

  <el-dialog append-to-body class="no-header-dialog" v-model="show" :title="undefined" :show-close="false" width="1250px">
    <template #header="{ close, titleId, titleClass }">
    </template>
    <div v-if="fristOpen" class="min-h-[550px] h-[70vh] relative">
      <Material
        :fixation-val="fixationVal"
        @single-select-emit="singleSelectEmit"
        default-box-status="select"
        :init-mitt-bus="false"
        ref="material"
      />
    </div>
  </el-dialog>
</template>
<script setup lang="ts" name="">
import { ref, watch, nextTick } from "vue";
import Material from "@/components/Material/index.vue";
import { type ListShow } from "@/components/Material/components/List/index";
import { Close } from "@element-plus/icons-vue";

interface Props {
  modelValue?: string;
  showValue?: string;
  portrait?: string;
  clearable?: boolean;
  fixationVal?: string[];
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: undefined,
  showValue: "",
  clearable: false,
  fixationVal: () => []
});

watch(
  () => props.modelValue,
  newValue => {
    showValues.value = newValue;
  }
);

const show = ref<boolean>(false);

const fristOpen = ref<boolean>(false);

const material = ref<InstanceType<typeof Material>>();

const close = () => {
  show.value = false;
};

watch(
  () => props.showValue,
  newValue => {
    showValues.value = newValue;
  }
);

watch(
  () => show.value,
  newVal => {
    if (newVal === true) {
      fristOpen.value = true;
      nextTick(() => material.value?.mittBusInitFunc());
    } else {
      material.value?.mittBusClearFunc();
    }
  }
);

const emits = defineEmits<{
  (event: "update:modelValue", value: string | undefined): void;
  (event: "update:showValue", value: string): void;
}>();

const showValues = ref<string | undefined>(props.modelValue);

watch(
  () => showValues.value,
  newVal => {
    emits("update:modelValue", newVal);
  }
);

const open = () => {
  show.value = true;
};

const singleSelectEmit = (item: ListShow) => {
  showValues.value = item.full_url;
  close();
};

const clear = () => {
  showValues.value = "";
  emits("update:modelValue", undefined);
  emits("update:showValue", "");
};

defineExpose({ clear });
</script>
<style scoped lang="scss">
@import "./index.scss";
</style>
