<template>
  <div class="message">
    <el-popover v-model:visible="visible" @after-leave="aftLea" placement="bottom-end" :width="520" trigger="click">
      <template #reference>
        <el-badge :value="num" :hidden="!showNum" class="item">
          <div class="transition toolBar-icon" :class="action ? 'big' : ''">
            <el-icon size="22.4px">
              <Files class="thumb" />
            </el-icon>
          </div>
        </el-badge>
      </template>
      <el-tabs v-model="activeName">
        <el-tab-pane :label="`上传(${uploadList.length})`" name="first">
          <div class="message-list" v-show="uploadList.length > 0">
            <div class="message-item" v-for="(item, index) in uploadList" :key="index">
              <img src="@/assets/images/file_upload.png" alt="" class="message-icon" />
              <div class="message-content">
                <div class="message-first-line">
                  <div class="message-title overOneSpec">{{ item.file_name }}</div>
                  <div class="size">{{ `大小:${item.sizeText}` }}</div>
                </div>
                <div class="message-date" v-if="!errorArr[index]">
                  <el-progress
                    :stroke-width="8"
                    striped
                    :percentage="percentageArr[index] ? percentageArr[index] : 0"
                    :striped-flow="percentageArr[index] === 100 ? false : true"
                    :duration="10"
                    v-show="percentageArr[index]"
                  />
                  <span v-show="!percentageArr[index]">等待上传...</span>
                </div>

                <div class="message-date" v-else>
                  <el-progress
                    status="exception"
                    :percentage="percentageArr[index] ? percentageArr[index] : 0"
                    v-show="percentageArr[index]"
                  />
                  <span class="red-text" v-show="!percentageArr[index]"> {{ errorArr[index] }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="h-full" v-show="uploadList.length === 0">
            <el-empty description="暂无上传列表" />
          </div>
        </el-tab-pane>
        <el-tab-pane label="导出(0)" name="second">
          <div class="message-empty">
            <img src="@/assets/images/notData.png" alt="notData" />
            <div>暂无消息</div>
          </div>
        </el-tab-pane>
        <el-tab-pane label="导入(0)" name="third">
          <div class="message-empty">
            <img src="@/assets/images/notData.png" alt="notData" />
            <div>暂无代办</div>
          </div>
        </el-tab-pane>
      </el-tabs>
    </el-popover>
  </div>
</template>

<script setup lang="ts">
import { ref, h } from "vue";
import { Files } from "@element-plus/icons-vue";
import uploadInit from "@/hooks/useUpload";
import mittBus from "@/utils/mittBus";
import { ElNotification, ElPopover } from "element-plus";
import { onBeforeUnmount } from "vue";

const num = ref<number>(0);
const showNum = ref<boolean>(false);
const activeName = ref("first");
const visible = ref<boolean>(false);
const { upload, uploadList, percentageArr, errorArr } = uploadInit(
  () => {
    num.value++;
    showNum.value = true;
  },
  (errIdx, successIdx) => {
    if (errIdx.length === 0) {
      mittBus.emit("uploadSuccess");
    } else {
      let elNotification = ElNotification({
        title: "文件上传失败",
        message:
          successIdx.length === 0
            ? h("i", { style: "text-decoration: underline" }, `点击查看`)
            : `${errIdx.length}个文件上传失败，${successIdx.length}个文件上传成功，点击查看`,
        type: "error",
        duration: 0,
        offset: 55,
        onClick: () => {
          visible.value = true;
          elNotification.close();
        }
      });
    }
  }
);

const aftLea = () => {
  showNum.value = false;
  setTimeout(() => (num.value = 0), 500);
};

const action = ref<boolean>(false);

mittBus.on("uploadFile", ({ dir_id, dir_link }) => {
  upload(dir_id, dir_link, () => {
    action.value = true;
    setTimeout(() => {
      action.value = false;
    }, 500);
  });
});

onBeforeUnmount(() => {
  mittBus.off("uploadFile");
});
</script>

<style scoped lang="scss">
:deep(.el-popover) {
  padding: 0 !important;
}
:deep(.el-popper) {
  padding: 0 !important;
}
.big {
  scale: 1.5;
}
.icon-color {
  //  color:	var(--el-text-color-primary);
}
.transition {
  transition: scale 0.5s;
}
.message-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 260px;
  line-height: 45px;
}
:deep(.toolBar-icon) {
  display: flex;
  align-items: flex-end;
}
:deep(.el-badge__content) {
  top: 1px !important;
}
.message-list {
  display: flex;
  flex-direction: column;
  max-height: 70vh;

  // max-height: 500px;
  overflow-y: auto;
  .message-item {
    display: flex;
    align-items: center;
    padding: 20px 0;
    border-bottom: 1px solid var(--el-border-color-light);

    // border: 1px solid red;
    &:last-child {
      border: none;
    }
    .message-icon {
      width: 40px;
      height: 40px;
      margin: 0 20px 0 5px;
    }
    .message-content {
      display: flex;
      flex-direction: column;

      // border: 1px solid red;
      width: 100%;
    }
  }
}
.message-first-line {
  display: flex;
  align-items: center;
  justify-content: space-between;

  // border: 1px solid red;
  width: 100%;
  .message-title {
    width: calc(85% - 30px);
    margin-right: 20px;
    margin-bottom: 5px;
  }
  .size {
    // font-size: 12px;
    width: 15%;
    margin-right: 10px;
    font-size: 12px;
    color: var(--el-text-color-secondary);
    color: #a1a0a0;
  }
}
.red-text {
  color: red;
}
.message-date {
  margin-top: 5px;
}
</style>
