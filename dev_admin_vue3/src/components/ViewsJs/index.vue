<script lang="ts" setup>
import { onMounted, ref } from "vue";
import Viewer from "viewerjs";
import "viewerjs/dist/viewer.min.css";

interface Props {
  defaultImg?: Array<{ title: string; url: string }>;
  imgIndex?: number;
}

const { imgIndex } = withDefaults(defineProps<Props>(), {
  defaultImg: () => [],
  imgIndex: 0
});

const images = ref<HTMLElement>();

const viewsJs = ref<Viewer | undefined>(undefined);

onMounted(() => {
  if (images.value !== undefined) {
    viewsJs.value = new Viewer(images.value, {
      zIndex: 9999
    });

    viewsJs.value.view(imgIndex);
  }
});

const open = () => {
  viewsJs.value?.show();
};

// const setImgData = (arr:string[])=>{
//     // viewsJs.value?.show();
//     imageArr.value = arr;
// }

defineExpose({ open });
</script>
<template>
  <div class="img-list-box">
    <ul class="img-list" ref="images">
      <li v-for="(item, index) in defaultImg" :key="index"><img data-not-lazy :src="item.url" :alt="item.title" /></li>
    </ul>
  </div>
</template>
<style scoped lang="scss">
@import "./index.scss";
</style>
