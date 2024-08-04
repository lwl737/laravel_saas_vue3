<template>
  <div class="w-[20px]">
    <el-badge :value="userStore.organiNameGet" class="item">
      <div class="transition toolBar-icon" @click="openOrgani">
        <!-- <el-icon size="22.4px">
          <Share class="thumb" />
        </el-icon> -->
        <i class="icon icon-organi toolBar-icon"></i>
      </div>
    </el-badge>
  </div>
  <OrganiDialog ref="organiRef"></OrganiDialog>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Share } from "@element-plus/icons-vue";
import OrganiDialog from "./OrganiDialog.vue";
import { useUserStore } from "@/stores/modules/user";
const userStore = useUserStore();
const organiRef = ref<InstanceType<typeof OrganiDialog> | null>(null);
const openOrgani = () => {
  organiRef.value?.openDialog();
};
</script>

<style scoped lang="scss">
.item {
  // :deep(.--el-color-danger){

  // }
  --el-color-danger: var(--el-color-primary);
  --el-badge-font-size: 10px;

  // position: absolute;

  // margin-top: 10px;
  margin-right: 40px;
  :deep(.el-badge__content) {
    display: block !important;
    max-width: 65px !important;
    overflow: hidden !important;
    line-height: 16px;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
  }
}
</style>
