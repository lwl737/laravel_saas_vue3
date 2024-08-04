<script lang="ts" setup>
import { watch, ref, CSSProperties, computed } from "vue";
interface Props {
  height?: string;
  src?: string;
  shade?: boolean;
  scale?: number;
  startBottomHeight?: number;
  imgShow?: "bg" | "element";
  imgStyle?: CSSProperties;
}

const props = withDefaults(defineProps<Props>(), {
  height: "auto",
  src: "",
  shade: true,
  scale: 1.1,
  startBottomHeight: 0,
  imgShow: "element",
  imgStyle: undefined
});
const srcs = ref<string>(props.src);
watch(
  () => props.src,
  newValue => {
    srcs.value = newValue;
  }
);

const imgStyle = computed(() => {
  return props.imgStyle ? props.imgStyle : {};
});

const mouseIn = ref<boolean>(false);
</script>
<template>
  <div class="scale-img" @mouseenter.stop="() => (mouseIn = true)" @mouseleave.stop="() => (mouseIn = false)">
    <div class="bg" v-show="shade && mouseIn"></div>

    <img
      :style="{ ...imgStyle, transform: `scale(${mouseIn ? scale : 1})` }"
      class="actions"
      :src="srcs"
      :key="srcs"
      v-if="imgShow === 'element'"
    />
    <div
      class="w-full h-full ele-bg actions"
      :style="{ 'background-image': `url(${srcs})`, transform: `scale(${mouseIn ? scale : 1})` }"
      :key="`bg-${srcs}`"
      v-else-if="imgShow === 'bg'"
    ></div>

    <div class="bottom-fixation">
      <slot name="bottom_fixation"></slot>
    </div>
    <div class="bottom" :style="{ bottom: mouseIn ? `0px` : `${startBottomHeight}px` }">
      <slot name="bottom"></slot>
    </div>
  </div>
</template>
<style scoped lang="scss">
@import "./index.scss";
</style>
