<template>
  <img class="thumb" :src="src" @click="clickItem" />
</template>

<script setup lang="ts">
import mittBus from "@/utils/mittBus";

interface Props {
  src?: string;
}

const props = withDefaults(defineProps<Props>(), {
  src: ""
});

const clickItem = () => {
  mittBus.emit("openImg", { imgArr: [{ title: props.src, url: props.src }] });
};
</script>

<style lang="scss" scoped></style>
