<template>
  <div class="dir">
    <TreeFilter
      title="目录"
      :right-menu-filter="data => data.dir_id !== '0' && data.dir_id !== '-1'"
      ref="treeFilter"
      :request-api="dirAllPromise"
      label="dir_name"
      id="dir_id"
      :icon="{
        open: 'FolderOpened',
        close: 'Folder'
      }"
      :default-value="dir_id"
      @handle-node-click-item="changeTreeFilter"
      @add-son="addSon"
      @del="treeDel"
      @edit="treeEdit"
    >
      <template #filterHeader>
        <el-button type="primary" :icon="CirclePlus" @click="() => openDrawer()">添加目录</el-button>
      </template>
    </TreeFilter>
    <div class="right card">
      <div class="right-top">
        <div class="flex items-center right-top-left">
          <el-tooltip class="item" effect="dark" content="按住CTRL+左键可多选文件" placement="bottom">
            <el-button
              v-show="boxStatus === 'none' || boxStatus === 'select'"
              class="button"
              type="primary"
              :icon="CirclePlus"
              @click="upload"
            >
              上传素材
            </el-button>
          </el-tooltip>

          <el-button
            v-show="boxStatus === 'none' || boxStatus === 'select'"
            type="primary"
            :icon="Delete"
            @click="() => changeBoxStatus('batchDel')"
          >
            批量删除
          </el-button>
          <el-button-group v-show="boxStatus === 'batchDel'">
            <el-button type="primary" @click="allSel">全选</el-button>
            <el-button type="primary" @click="clearAll">清空</el-button>
          </el-button-group>

          <el-dropdown
            @click="confirm"
            :disabled="batchDelArr.length === 0"
            max-height="300px"
            split-button
            type="primary"
            v-show="boxStatus === 'batchDel'"
          >
            <span>{{ batchDelArr.length === 0 ? `未选中` : `删除(${batchDelArr.length})` }}</span>
            <template #dropdown>
              <el-dropdown-menu class="w-[220px]">
                <el-dropdown-item
                  @click="() => openOneFile(item)"
                  :title="item.file_name"
                  v-for="(item, index) in batchDelArr"
                  :key="index"
                  class="flex items-center h-[40px]"
                >
                  <div class="flex flex-1 w-[0]">
                    <div class="overOne flex-1 w-[0]">{{ item.file_name_before }}.</div>
                    <div>{{ item.file_name_last }}</div>
                  </div>
                  <div class="ml-[15px] flex w-[30px] text-[18px] h-full items-center justify-center">
                    <el-icon class="w-full" :title="`取消选中`" @click.stop="() => clearSel(index)">
                      <Delete />
                    </el-icon>
                  </div>
                </el-dropdown-item>
              </el-dropdown-menu>
            </template>
          </el-dropdown>

          <el-button
            v-show="boxStatus === 'none' || boxStatus === 'select'"
            class="button"
            type="primary"
            :icon="Refresh"
            @click="refresh"
          >
            刷新
          </el-button>
          <el-dropdown
            @click="selectConfirm"
            :disabled="!multiple && !single_select"
            max-height="300px"
            split-button
            type="primary"
            v-show="boxStatus === 'select' && !multiple"
          >
            <span v-show="!multiple">{{ !single_select ? `未选中` : `确定` }}</span>
            <template #dropdown>
              <el-dropdown-menu class="w-[220px]">
                <template v-if="!multiple && single_select">
                  <el-dropdown-item
                    @click="() => openOneFile(single_select as ListShow)"
                    :title="single_select.file_name"
                    class="flex items-center h-[40px]"
                  >
                    <div class="flex flex-1 w-[0]">
                      <div class="overOne flex-1 w-[0]">{{ single_select.file_name_before }}.</div>
                      <div>{{ single_select.file_name_last }}</div>
                    </div>
                    <div class="ml-[15px] flex w-[30px] text-[18px] h-full items-center justify-center">
                      <el-icon class="w-full" :title="`取消选中`" @click.stop="() => clearSingleSel()">
                        <Delete />
                      </el-icon>
                    </div>
                  </el-dropdown-item>
                </template>
              </el-dropdown-menu>
            </template>
          </el-dropdown>

          <el-button class="button" v-show="boxStatus === 'batchDel'" @click="() => changeBoxStatus(props.defaultBoxStatus)">
            取消
          </el-button>
        </div>
        <div class="flex items-center">
          <el-select
            :disabled="fixationVal.length > 0"
            multiple
            collapse-tags
            collapse-tags-tooltip
            clearable
            v-model="searchForm.file_type"
            class="m-2"
            placeholder="请选择文件类型"
            style="width: 160px"
          >
            <el-option v-for="item in option" :key="item.value" :label="item.title" :value="item.value" />
          </el-select>

          <div class="w-[150px]"><el-input clearable v-model="searchForm.keyWord" placeholder="请输入文件名称"></el-input></div>

          <el-button class="ml-[10px]" :icon="Search" @click="clickSearch">搜索</el-button>
        </div>
      </div>

      <div class="list" v-loading="loading">
        <div class="w-full h-full flex justify-center items-center" v-show="listShow.length === 0 && !loading">
          <el-empty description="暂无素材"></el-empty>
        </div>

        <ul v-show="listShow.length > 0">
          <li @click="() => clickItem(item)" class="thumb list-li" v-for="item in listShow" :key="item._id">
            <div
              class="absolute left-[-4px] top-[-4px] w-[calc(100%+8px)] border-[3px] h-[calc(100%+8px)] z-[2]"
              :class="[
                (boxStatus === 'batchDel' && batchDelIdArr.indexOf(item._id) === -1) ||
                (boxStatus === 'select' && !multiple && single_select?._id !== item._id)
                  ? 'border-dashed border-[#b3b4b6]'
                  : 'border border-[#e34141]'
              ]"
              v-show="boxStatus === 'batchDel' || boxStatus === 'select'"
            ></div>

            <div class="right-top-icon">
              <el-dropdown>
                <el-icon @click.stop="() => {}">
                  <MoreFilled class="text-[white]" />
                </el-icon>
                <template #dropdown>
                  <el-dropdown-menu class="w-[80px]">
                    <el-dropdown-item @click="() => seeFile(item)">查看</el-dropdown-item>
                    <el-dropdown-item @click="() => delOne(item._id)">删除</el-dropdown-item>
                  </el-dropdown-menu>
                </template>
              </el-dropdown>
            </div>
            <div class="error" v-if="item.schedule !== 1">
              <img :src="publicUrl('/upload_error.png')" />
            </div>
            <div v-else-if="item.webFileType === 'video'" class="video">
              <el-icon :size="40"><VideoCamera /></el-icon>
              <div class="img-bottom-bg video-title">
                <div class="top">
                  <div class="left overOne" :title="item.file_name">
                    <div class="left-file-name overOne">
                      {{ item.file_name_before }}<span v-show="item.file_name_last">.</span>
                    </div>
                    <div v-show="item.file_name_last">{{ item.file_name_last }}</div>
                  </div>
                </div>
              </div>
            </div>
            <ScaleImg
              v-else
              :img-style="
                item.webFileType === 'image' ? { objectFit: 'cover' } : { width: '70%', height: 'auto', margin: 'auto' }
              "
              :src="item.cover"
              :start-bottom-height="0"
              :scale="1"
              :shade="false"
            >
              <template #bottom>
                <div class="img-bottom-bg">
                  <div class="top">
                    <div class="left overOne" :title="item.file_name">
                      <div class="left-file-name overOne">
                        {{ item.file_name_before }}<span v-show="item.file_name_last">.</span>
                      </div>
                      <div v-show="item.file_name_last">{{ item.file_name_last }}</div>
                    </div>
                  </div>
                </div>
              </template>
            </ScaleImg>
          </li>
        </ul>
      </div>

      <div class="right-bottom flex justify-between items-center">
        <div class="flex-1">
          <div class="progress-box">
            <div class="left-title">容量:</div>
            <div class="progress">
              <el-progress
                :color="percentFormatData.color"
                :format="formatData"
                :percentage="percentFormatData.percent"
                :stroke-width="8"
              />
            </div>
          </div>
        </div>
        <div class="flex items-center">
          <el-pagination
            :pager-count="5"
            background
            @current-change="handleCurrentChange"
            :current-page="pageNum"
            :page-size="pageSize"
            layout="total, prev, pager, next, jumper"
            :total="total"
          >
          </el-pagination>
        </div>
      </div>
    </div>
    <Confirm
      @sucess="confirmDel"
      v-model:dialog-visible="dialogVisible"
      v-model:real-del="real_del"
      v-model:real-del-disabled="real_del_disabled"
    />
    <Confirm
      @sucess="confirmDelOne"
      v-model:dialog-visible="dialogVisible_one"
      v-model:real-del="real_del_one"
      v-model:real-del-disabled="real_del_disabled_one"
    />
  </div>
</template>
<script setup lang="ts" name="">
import { ref, computed, reactive, watch } from "vue";
import { dirAll, fileList, fileDel } from "@/api/modules/upload";
import { Upload } from "@/api/interface/upload";
import { CirclePlus, Delete, Refresh, MoreFilled, Search } from "@element-plus/icons-vue";
import TreeFilter from "../TreeFilter/index.vue";
import ScaleImg from "@/components/ScaleImg/index.vue";
import { ElMessage } from "element-plus";
import { StStore } from "@/stores/modules/sts";
import { fileUnit, dateChange, publicUrl } from "@/utils/util";
import mittBus from "@/utils/mittBus";
import Confirm from "./components/Confirm/index.vue";
import { type BoxStatus, type ListShow, type WebFileType } from "./index";

// import SvgIcon from "@/components/SvgIcon/index.vue";

// type BoxStatus = "select" | "batchDel" | "none";
interface Props {
  defaultBoxStatus?: BoxStatus;
  multiple?: boolean;
  fixationVal?: string[];
}

const props = withDefaults(defineProps<Props>(), {
  is_common: 0,
  defaultBoxStatus: "none",
  multiple: false,
  fixationVal: () => []
});

const dirAllPromise = () => {
  return new Promise(async resolve => {
    let res = await dirAll();
    res.data.unshift({
      dir_name: "回收站",
      dir_pid: "0",
      dir_sort: 0,
      dir_link: "-1",
      dir_id: "-1"
    });
    res.data.unshift({
      dir_name: "全部资源",
      dir_pid: "0",
      dir_sort: 0,
      dir_link: "0",
      dir_id: "0"
    });

    resolve(res);
  });
};
const stStore = StStore();

const dialogVisible = ref<boolean>(false);
const real_del = ref<1 | 0>(1);
const real_del_disabled = ref<boolean>(false);

const dialogVisible_one = ref<boolean>(false);
const real_del_one = ref<1 | 0>(1);
const real_del_disabled_one = ref<boolean>(false);
const one_del_id = ref<string>("");

const single_select = ref<ListShow | undefined>(undefined);

const formatData = () => {
  return `${fileUnit(stStore.capacityGet !== undefined ? stStore.capacityGet : 0)}/${fileUnit(stStore.max_capacity !== undefined ? stStore.max_capacity : 0)}`;
};

const option = computed(() => {
  // return [];
  return stStore.uploadConfigGet
    ? stStore.uploadConfigGet?.upload_file_type.map(item => {
        return { title: item.name, value: item.type };
      })
    : [];
});

const clearSingleSel = () => {
  single_select.value = undefined;
};

const boxStatus = ref<BoxStatus>(props.defaultBoxStatus);

const delOne = (oid: string) => {
  one_del_id.value = oid;

  real_del_one.value = dir_id.value === "-1" ? 1 : 0;

  real_del_disabled_one.value = dir_id.value === "-1" ? true : false; //回收站里面只能硬删除

  dialogVisible_one.value = true;
};

const changeBoxStatus = (status: BoxStatus) => {
  boxStatus.value = status;
  if (status === "none") {
    initBatchDelFunc();
  }
};

const initBatchDelFunc = () => {
  batchDelArr.value = [];
  batchDelIdArr.value = [];
};

const handleCurrentChange = (num: number) => {
  pageNum.value = num;
  initData();
};
const pageNum = ref<number>(1);
const pageSize = ref<number>(36);
const dir_id = ref<string>("0");
const dir_link = ref<string>("0");
const total = ref<number>(0);
const list = ref<Upload.File.ListRes[]>([]);
const searchForm = reactive<{
  file_type: string[];
  keyWord: string;
}>({
  file_type: props.fixationVal,
  keyWord: ""
});

watch(
  () => searchForm.file_type.length,
  () => {
    pageNum.value = 1;
    initData();
  }
);

const listShow = computed((): ListShow[] => {
  return list.value.map((item): ListShow => {
    let file_type_arr = item.file_name.split(".");
    let file_name_last = file_type_arr.length > 0 ? (file_type_arr.at(-1) as string) : "";
    let file_name_before =
      file_type_arr.length > 0 ? file_type_arr.splice(0, file_type_arr.length - 1).join(".") : item.file_name;

    let webFileType: WebFileType = "file";
    switch (item.file_type.split("/").at(0)) {
      case "video":
        webFileType = "video";
        break;
      case "image":
        webFileType = "image";
        break;
      default:
        webFileType = "file";
    }

    return {
      ...item,
      webFileType,
      cover: webFileType === "image" ? item.full_url : publicUrl("/upload_success.png"),
      dateChange: dateChange(item.create_time * 1000, "ymdhis"),
      file_name_last,
      file_name_before
    };
  });
});
const openImgShow = computed(() => {
  let arr: Array<{
    _id: string;
    title: string;
    url: string;
  }> = [];

  for (let i = 0; i < list.value.length; i++) {
    if (imgTypeArr.value.indexOf(list.value[i].file_type) !== -1 && list.value[i].schedule === 1)
      arr.push({ _id: list.value[i]._id, title: list.value[i].file_name, url: list.value[i].full_url });
  }

  return arr;
});

watch(
  () => dir_id.value,
  () => {
    initBatchDelFunc();
    boxStatus.value = props.defaultBoxStatus;
  }
);

const confirm = () => {
  real_del.value = dir_id.value === "-1" ? 1 : 0;

  real_del_disabled.value = dir_id.value === "-1" ? true : false; //回收站里面只能硬删除

  dialogVisible.value = true;
};

const confirmDelOne = async () => {
  try {
    await fileDel({ oids: [one_del_id.value], real_del: real_del_one.value });
    let indexOf = batchDelIdArr.value.indexOf(one_del_id.value);
    if (indexOf !== -1) {
      batchDelIdArr.value.splice(indexOf, 1);
      batchDelArr.value.splice(indexOf, 1);
    }
    if (props.defaultBoxStatus === "select") {
      //为选择文件时

      if (!props.multiple) {
        //单选时
        if (single_select.value && one_del_id.value === single_select.value._id) {
          //把单选删除
          single_select.value = undefined;
        }
      } else {
      }
    }
    initData();
  } finally {
    dialogVisible.value = false;
  }
};

const confirmDel = async () => {
  if (batchDelIdArr.value.length < 1) return;
  try {
    await fileDel({ oids: batchDelIdArr.value, real_del: real_del.value });

    if (props.defaultBoxStatus === "select") {
      //为选择文件时

      if (!props.multiple) {
        //单选时
        if (single_select.value) {
          let index = batchDelIdArr.value.indexOf(single_select.value._id);
          //把单选删除
          if (index !== -1) single_select.value = undefined;
        }
      } else {
      }
    }

    initBatchDelFunc();

    boxStatus.value = props.defaultBoxStatus;

    initData();
  } finally {
    dialogVisible.value = false;
  }
};

const clickItem = (items: ListShow) => {
  if (boxStatus.value === "batchDel") {
    let indexOf = batchDelArr.value.findIndex(item => item._id === items._id);

    if (indexOf !== -1) {
      batchDelIdArr.value.splice(indexOf, 1);
      batchDelArr.value.splice(indexOf, 1);
    } else if (batchDelArr.value.length === maxBatchDelCount.value) {
      return ElMessage.warning(`最多选中${maxBatchDelCount.value}个素材`);
    } else {
      batchDelIdArr.value.push(items._id);
      batchDelArr.value.push(items);
    }
  } else if (boxStatus.value === "none") seeFile(items);
  else if (boxStatus.value === "select") {
    if (!props.multiple)
      single_select.value = single_select.value?._id === items._id ? undefined : items; //单选
    else {
      //多选
    }
  }
};
const maxBatchDelCount = ref<number>(36);

const batchDelArr = ref<Array<ListShow>>([]);

const batchDelIdArr = ref<Array<string>>([]);

const allSel = () => {
  let difference_set = listShow.value.filter(item => batchDelIdArr.value.indexOf(item._id) === -1);

  if (batchDelIdArr.value.length + difference_set.length > maxBatchDelCount.value)
    return ElMessage.warning(`最多选中${maxBatchDelCount.value}个素材`);
  else {
    difference_set.forEach(items => {
      batchDelIdArr.value.push(items._id);
      batchDelArr.value.push(items);
    });
  }
};

const clearSel = (index: number) => {
  batchDelArr.value.splice(index, 1);
  batchDelIdArr.value.splice(index, 1);
};

const imgTypeArr = computed(() => {
  let config = stStore.getImgConfig();
  return config ? config.split(",") : [];
});

const seeFile = (item: ListShow) => {
  switch (item.webFileType) {
    case "video":
      openVideo(item.full_url);
      break;
    case "image":
      openImg(item._id);
      break;
    default:
      break;
  }
};

const clickSearch = () => {
  pageNum.value = 1;
  initData();
};

const openImg = (_id: string) => {
  let index = openImgShow.value.findIndex(item => item._id === _id);
  if (index !== -1) mittBus.emit("openImg", { imgArr: openImgShow.value, showIndex: index });
};

const openVideo = (video_url: string) => {
  mittBus.emit("openVideo", { src: video_url });
};

const openOneFile = (item: ListShow) => {
  if (item.schedule !== 1) return ElMessage.error("该文件未上传完成无法查看");
  switch (item.file_type) {
    case "image":
      mittBus.emit("openImg", { imgArr: [{ title: item.file_name, url: item.full_url }] });
      break;
  }
};

const loading = ref<boolean>(false);

const percentFormatData = computed(() => {
  let percent =
    stStore.capacityGet !== undefined && stStore.max_capacity !== undefined
      ? (stStore.capacityGet / stStore.max_capacity) * 100
      : 0;
  let color: undefined | string = undefined;
  if (percent > 80) color = "#F56C6C";
  else if (percent > 50) color = "#E6A23C";
  return { percent, color };
});

const initData = () => {
  loading.value = true;

  fileList({
    pageNum: pageNum.value,
    pageSize: pageSize.value,
    dir_link: dir_link.value,
    keyWord: searchForm?.keyWord,
    file_type: searchForm?.file_type?.join(",")
  }).then(res => {
    list.value = res.data.datalist;
    total.value = res.data.total;
    stStore.setCapacity(res.data.capacity, res.data.max_capacity); //保存当前容量和最大容量
    loading.value = false;
  });
};

const refresh = () => {
  initData();
};

const clearAll = () => {
  batchDelArr.value = [];
  batchDelIdArr.value = [];
};

/* ref 组件 */
const treeFilter = ref<InstanceType<typeof TreeFilter>>();

const emits = defineEmits<{
  // (event: "changeTreeFilter", data: any): void;
  (event: "treeDel", data: any, now_dir_id: string): void;
  (event: "treeEdit", data: any): void;
  // (event: "addSon", is_common: 1|0,dir_pid:number): void;
  (event: "openDrawer", dir_pid: string): void;
  (event: "upload", dir_id: string, dir_link: string): void;
  (event: "singleSelectEmit", item: ListShow): void;
}>();

const addSon = (data: any) => {
  emits("openDrawer", data.dir_id);
};
const changeTreeFilter = (data: Upload.Dir.All) => {
  dir_id.value = data.dir_id;
  dir_link.value = data.dir_link;
  pageNum.value = 1;
  initData();
};
const treeDel = (data: any) => {
  // pagesFormDrawer.value?.open(data.menu_id);
  emits("treeDel", data, dir_id.value);
};
const treeEdit = (data: any) => {
  // pagesFormDrawer.value?.open(data.menu_id);
  emits("treeEdit", data);
};
const openDrawer = () => {
  emits("openDrawer", "0");
};
const upload = () => {
  emits("upload", dir_id.value, dir_link.value);
};

const selectConfirm = () => {
  if (!props.multiple && single_select.value) {
    emits("singleSelectEmit", single_select.value);
  }
};

defineExpose({ treeFilter, initData });
</script>
<style scoped lang="scss">
@import "./index.scss";
</style>
