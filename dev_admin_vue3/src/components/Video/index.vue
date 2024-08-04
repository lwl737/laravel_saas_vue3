<template>
  <div class="up-image thumb">
    <!-- <img class="img-value" v-show="showValues" :src="'@/assets/images/add-video.png'" @click="openVideo" /> -->

    <div clsss="img-value flex" v-show="showValues" @click="openVideo" :title="showValues">
      <!-- <el-icon :size="40"><VideoCamera /></el-icon> -->
      <el-icon :size="40"><VideoCamera /></el-icon>
      <div class="img-bottom-bg video-title">
        <div class="top">
          <div class="left overOne">
            <div class="left-file-name overOne">点击查看</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref, watch } from "vue";
import mittBus from "@/utils/mittBus";

interface Props {
  modelValue?: string | null;
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: undefined
});

watch(
  () => props.modelValue,
  newValue => {
    showValues.value = newValue ? newValue : undefined;
  }
);

const emits = defineEmits<{
  (event: "update:modelValue", value: string | undefined): void;
  (event: "update:showValue", value: string): void;
}>();

const showValues = ref<string | undefined>(props.modelValue ? props.modelValue : undefined);

watch(
  () => showValues.value,
  newVal => {
    emits("update:modelValue", newVal);
  }
);

const openVideo = () => {
  mittBus.emit("openVideo", { src: showValues.value ? showValues.value : "" });
  // mittBus.emit("openImg", { imgArr: [{ title: "", url: showValues.value ? showValues.value : "" }] });
};
</script>
<style scoped lang="scss">
@import "./index.scss";
</style>
