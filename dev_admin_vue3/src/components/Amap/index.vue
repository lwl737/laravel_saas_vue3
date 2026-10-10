<template>
  <div class="map_box">
    <div class="input">
      <el-autocomplete v-model="keyWord" :fetch-suggestions="querySearchAsync" placeholder="请输入地点" @select="handleSelect" />
    </div>
    <div ref="mapRef" class="map"></div>
  </div>
</template>
<script setup lang="ts" name="">
import { ref, onMounted, watch } from "vue";
import { ElMessage } from "element-plus";

interface Props {
  lnglat?: [number, number]; //经度
  keyWord?: string;
  mapConfig?: {
    key: string;
    securityJsCode: string;
    version: string;
  };
  autoOptions?: any;
}

interface LinkItem {
  value: string;
  link: string;
}

const props = withDefaults(defineProps<Props>(), {
  lnglat: undefined,
  mapConfig: undefined,
  keyWord: undefined,
  autoOptions: () => {
    return {};
  }
});
const is_complete = ref<boolean>(false);
const keyWord = ref<string>("");
const mapRef = ref();
const map = ref();
const geocoder = ref();
const marker = ref();
const autoComplete = ref();

const amap = ref();

onMounted(() => {
  let config: any = {};
  if (props.lnglat) config.lnglat = props.lnglat;
  if (props.keyWord) config.keyWord = props.keyWord;

  initAMapLoader().then(() => createInit(props.autoOptions, config.lnglat || config.keyWord ? config : undefined));
});

const initAMapLoader = () => {
  if (!props.mapConfig) return Promise.reject("mapConfig 没有传进来");
  //高德地图安全密钥
  window._AMapSecurityConfig = {
    securityJsCode: props.mapConfig.securityJsCode
  };
  return new Promise((reslove, reject) => {
    if (!props.mapConfig) {
      reject("mapConfig 没有传进来");
    } else {
      AMapLoader.load({
        version: props.mapConfig.version,
        key: props.mapConfig.key,
        plugins: ["AMap.ToolBar", "AMap.Geolocation", "AMap.Geocoder", "AMap.PlaceSearch"]
      })
        .then(res => {
          amap.value = res;
          reslove(amap.value);
        })
        .catch(res => {
          reject("高德地图调用错误" + res);
          ElMessage.error("高德地图调用错误");
        });
    }
  });
};

const content: string =
  '<div class="amap-icon" style="position: absolute; overflow: hidden; width: 19px; height: 33px; opacity: 1;"><img src="https://webapi.amap.com/theme/v1.3/markers/b/mark_bs.png" style="width: 19px; height: 33px; top: 0px; left: 0px;"></div>';
//标点的style
onMounted(() => {});
const emits = defineEmits<{
  (event: "clickCallBack", val: { longitude: string; latitude: string; address: string }): void;
  (event: "setLngLat", val: { lnglat: [number, number] }): void;
  (event: "changeKeyWord", val: string): void;
}>();

watch(
  () => keyWord.value,
  newVal => {
    emits("changeKeyWord", newVal);
  }
);

const getMsg = (lngLats: number[]) => {
  geocoder.value.getAddress(lngLats, (status: any, result: any) => {
    if (status === "complete" && result.info === "OK") {
      // result中对应详细地理坐标信息
      map.value.setCenter(lngLats); // 设置中心点
      marker.value.setPosition(lngLats); // 标记点位置
      keyWord.value = result.regeocode.formattedAddress;
      emits("clickCallBack", {
        longitude: lngLats[0].toString(),
        latitude: lngLats[1].toString(),
        address: keyWord.value
      });
    } else {
      ElMessage.error("获取失败");
    }
  });
};

const links = ref<LinkItem[]>([]);
const querySearchAsync = (queryString: string, cb: (arg: any) => void) => {
  autoComplete.value.search(queryString, function (status: any, result: any) {
    // 搜索成功时，result即是对应的匹配数据
    if (result.info === "OK") {
      links.value = result.tips.map((item: any) => {
        return { lngLat: [item.location.lng, item.location.lat], value: item.name };
      });
    }
    cb(links.value);
  });
};
const handleSelect = (item: any) => {
  getMsg(item.lngLat);
};
const mapTime = ref<any>(undefined);
const setLngLat = (lngLats: [number, number]) => {
  clearInterval(mapTime.value);
  // if (lngLats[0] === "" && lngLats[1] === "") lngLats = props.lnglat;
  if (is_complete.value) {
    map.value.setCenter([Number(lngLats[0]), Number(lngLats[1])]);
    marker.value.setPosition([Number(lngLats[0]), Number(lngLats[1])]);
  } else {
    mapTime.value = setInterval(() => {
      if (is_complete.value) {
        map.value.setCenter([Number(lngLats[0]), Number(lngLats[1])]);
        marker.value.setPosition([Number(lngLats[0]), Number(lngLats[1])]);
        clearInterval(mapTime.value);
      }
    }, 200);
  }

  emits("clickCallBack", {
    longitude: lngLats[0].toString(),
    latitude: lngLats[1].toString(),
    address: keyWord.value
  });
};

const initFirst = ref(true);

const init = (
  autoOptionsConfig: any = undefined,
  config: { keyWord?: string; lnglat?: [number, number] } | undefined = undefined
) => {
  console.log(config, "config");
  if (!amap.value) {
    initAMapLoader().then(() => createInit(autoOptionsConfig, config));
  } else {
    createInit(autoOptionsConfig, config);
  }
};

const createInit = (
  autoOptionsConfig: any = undefined,
  config: { keyWord?: string; lnglat?: [number, number] } | undefined = undefined
) => {
  const autoCompleteCallBack = () => {
    let autoOptions: any = autoOptionsConfig ? autoOptionsConfig : props.autoOptions;
    autoComplete.value = new amap.value.AutoComplete(autoOptions);
    if (config) {
      if (config.lnglat) setLngLat(config.lnglat);
      if (config.keyWord) keyWord.value = config.keyWord;
    } else if (autoOptions.city) initInput(autoOptions.city);
  };

  if (!initFirst.value) {
    autoCompleteCallBack();
    return;
  }
  initFirst.value = false;
  map.value = new amap.value.Map(mapRef.value, { zoom: 16 });

  marker.value = new amap.value.Marker({
    // icon: icon,
    content,
    // position: new AMap.LngLat(116.397428, 39.90923), // 经纬度对象，也可以是经纬度构成的一维数组[116.39, 39.9]
    // position: locationArr.value, // 经纬度对象，也可以是经纬度构成的一维数组[116.39, 39.9]
    title: "",
    offset: new amap.value.Pixel(-10, -30)
  });

  map.value.add(marker.value);
  amap.value.plugin("AMap.Geocoder", function () {
    geocoder.value = new amap.value.Geocoder({ city: "" });
  });

  amap.value.plugin("AMap.AutoComplete", function () {
    autoCompleteCallBack();
    // 根据关键字进行搜索
  });

  // 为地图注册click事件获取鼠标点击出的经纬度坐标
  map.value.on("click", function (e: { lnglat: { getLng: () => string; getLat: () => string } }) {
    getMsg([+e.lnglat.getLng(), +e.lnglat.getLat()]);
  });
  map.value.on("complete", function () {
    is_complete.value = true;
  });
};

const initOptions = (
  autoOptionsConfig: any = undefined,
  config: { keyWord: string; lnglat: [number, number] } | undefined = undefined
) => {
  if (!amap.value) return;
  let autoOptions: any = autoOptionsConfig ? autoOptionsConfig : props.autoOptions;
  // {
  //city 限定城市，默认全国
  // city: "广州"
  // };
  autoComplete.value = new amap.value.AutoComplete(autoOptions);
  if (config) {
    setLngLat(config.lnglat);
    keyWord.value = config.keyWord;
  } else if (autoOptions.city) initInput(autoOptions.city);
};

const initInput = (queryString: string) => {
  // console.log(queryString, "queryString");
  autoComplete.value.search(queryString, (status: any, result: any) => {
    // 搜索成功时，result即是对应的匹配数据
    if (result.info === "OK" && result.tips.length > 0) {
      let item = result.tips.find(item => item.location);
      if (item) {
        keyWord.value = item.name;
        setLngLat([item.location.lng, item.location.lat]);
      }
    }
  });
};

defineExpose({ map, setLngLat, init, initOptions });
</script>
<style scoped lang="scss">
.input {
  position: relative;
  z-index: 99;
  display: flex;
  width: 100%;
  height: 30px;
  margin-bottom: 20px;
  .part-s {
    position: absolute;
    top: 30px;
    width: 100%;
    min-height: 50px;
    background: white;
  }
  :deep(.el-autocomplete) {
    width: 100%;
  }
}
.map_box {
  display: flex;
  flex-direction: column;
  width: 100%;
  height: 100%;
}
.map {
  flex: 1;
}
</style>
