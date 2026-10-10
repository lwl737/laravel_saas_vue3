<template>
  <div class="plyr-player" v-show="plyrShow" @click.self="closePlyr">
    <video @click.stop="" ref="videoPlayer" :src="videoSrc" class="plyr-video" controls></video>
  </div>
</template>

<style>
.plyr-player {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 99999999999999999999999;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100vh;
  background-color: rgb(0 0 0 / 60%);
}
.plyr-video {
  max-height: 80vh;
}
</style>

<script setup lang="ts">
import { onMounted, onUnmounted, ref } from "vue";
import Plyr from "plyr";
import "plyr/dist/plyr.css";
interface Props {
  videoSrc?: string;
}

const props = withDefaults(defineProps<Props>(), {
  videoSrc: ""
});

const videoPlayer = ref<HTMLVideoElement | undefined>(undefined);
const videoSrc = ref<string>(props.videoSrc); // 应该通过props传递
const options = ref<string[]>([
  "play-large",
  "play",
  "progress",
  "current-time",
  "mute",
  "volume",
  "captions",
  "settings",
  "pip",
  "airplay",
  "fullscreen"
]);

const plyrShow = ref<boolean>(false);
let plyr: InstanceType<typeof Plyr> | null = null;

onMounted(() => {});

let initPlyr = () => {
  console.log("试试");
  plyrShow.value = true;
  if (videoPlayer.value !== undefined) {
    plyr = new Plyr(videoPlayer.value, {
      controls: options.value,
      hideControls: false
    });
  }
};

let closePlyr = () => {
  console.log("关闭呀");
  if (plyr) {
    plyrShow.value = false;
    plyr.destroy();
    plyr = null;
  }
};

// 通过props接收视频源
defineExpose({ initPlyr });
</script>
