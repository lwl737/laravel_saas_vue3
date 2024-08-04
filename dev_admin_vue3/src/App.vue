<template>
  <el-config-provider :locale="locale" :size="assemblySize" :button="buttonConfig">
    <router-view></router-view>
  </el-config-provider>
  <ViewsJs
    :default-img="viewsJsReactive.images"
    :img-index="viewsJsReactive.imgIndex"
    :key="viewsJsReactive.viewsNum"
    ref="viewsJs"
  />
  <Plyr :videoSrc="plyrReactive.scr" :key="plyrReactive.scr" ref="plyr" />
</template>

<script setup lang="ts">
import { onMounted, reactive, computed, ref, onBeforeUnmount, nextTick, onBeforeMount } from "vue";
import { useI18n } from "vue-i18n";
import { getBrowserLang } from "@/utils";
import { useTheme } from "@/hooks/useTheme";
import { ElConfigProvider } from "element-plus";
import { LanguageType } from "./stores/interface";
import { useGlobalStore } from "@/stores/modules/global";
import en from "element-plus/es/locale/lang/en";
import zhCn from "element-plus/es/locale/lang/zh-cn";
import ViewsJs from "@/components/ViewsJs/index.vue";
import Plyr from "@/components/Plyr/index.vue";
import mittBus from "@/utils/mittBus";

const globalStore = useGlobalStore();

// init theme
const { initTheme } = useTheme();
initTheme();

// init language
const i18n = useI18n();
onMounted(() => {
  const language = globalStore.language ?? getBrowserLang();
  i18n.locale.value = language;
  globalStore.setGlobalState("language", language as LanguageType);
});

// element language
const locale = computed(() => {
  if (globalStore.language == "zh") return zhCn;
  if (globalStore.language == "en") return en;
  return getBrowserLang() == "zh" ? zhCn : en;
});

// element assemblySize
const assemblySize = computed(() => globalStore.assemblySize);

// element button config
const buttonConfig = reactive({ autoInsertSpace: false });

const viewsJs = ref<InstanceType<typeof ViewsJs>>();
const plyr = ref<InstanceType<typeof Plyr>>();

mittBus.on("openImg", ({ imgArr, showIndex }) => {
  viewsJsReactive.images = imgArr;
  viewsJsReactive.viewsNum++;
  viewsJsReactive.imgIndex = showIndex;
  nextTick(() => viewsJs.value?.open());
});

mittBus.on("openVideo", ({ src }) => {
  console.log("openVideo", src);
  plyrReactive.scr = src;

  nextTick(() => plyr.value?.initPlyr());
});

onBeforeUnmount(() => {
  mittBus.off("openImg");
  mittBus.off("openVideo");
});

const viewsJsReactive = reactive<{
  images: Array<{ title: string; url: string }>;
  imgIndex: number | undefined;
  viewsNum: number;
}>({
  images: [],
  imgIndex: undefined,
  viewsNum: 0
});

const plyrReactive = reactive<{
  scr: string;
}>({
  scr: ""
});
</script>
