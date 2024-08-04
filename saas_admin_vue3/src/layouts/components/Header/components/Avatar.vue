<template>
  <el-dropdown trigger="click">
    <div class="avatar">
      <img
        :src="userStore.userInfo?.portrait.full_url ? userStore.userInfo?.portrait.full_url : getAssetsFile(`avatar.gif`)"
        alt="avatar"
      />
    </div>
    <template #dropdown>
      <el-dropdown-menu>
        <el-dropdown-item @click="openDialog('infoRef')">
          <el-icon><User /></el-icon>{{ $t("header.personalData") }}
        </el-dropdown-item>
        <el-dropdown-item @click="openDialog('passwordRef')">
          <el-icon><Edit /></el-icon>{{ $t("header.changePassword") }}
        </el-dropdown-item>
        <el-dropdown-item @click="openDialog('organiRef')">
          <el-icon><Connection /></el-icon>
          <view class="overOne max-w-[56px]">选择部门</view>
        </el-dropdown-item>
        <el-dropdown-item divided @click="logout">
          <el-icon><SwitchButton /></el-icon>{{ $t("header.logout") }}
        </el-dropdown-item>
      </el-dropdown-menu>
    </template>
  </el-dropdown>
  <!-- infoDialog -->
  <InfoDialog ref="infoRef"></InfoDialog>
  <!-- passwordDialog -->
  <PasswordDialog ref="passwordRef"></PasswordDialog>
  <OrganiDialog ref="organiRef"></OrganiDialog>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { LOGIN_URL } from "@/config";
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/modules/user";
import { ElMessageBox, ElMessage } from "element-plus";
import InfoDialog from "./InfoDialog.vue";
import PasswordDialog from "./PasswordDialog.vue";
import { getAssetsFile } from "@/utils/util";
import OrganiDialog from "./OrganiDialog.vue";

const router = useRouter();
const userStore = useUserStore();

// 退出登录
const logout = () => {
  ElMessageBox.confirm("您是否确认退出登录?", "温馨提示", {
    confirmButtonText: "确定",
    cancelButtonText: "取消",
    type: "warning"
  }).then(async () => {
    // 1.清除 Token
    userStore.loginOut();

    // 2.重定向到登陆页
    router.replace(userStore.tenantIdReplacePath(LOGIN_URL));
    ElMessage.success("退出登录成功！");
  });
};

// 打开修改密码和个人信息弹窗
const infoRef = ref<InstanceType<typeof InfoDialog> | null>(null);
const passwordRef = ref<InstanceType<typeof PasswordDialog> | null>(null);
const organiRef = ref<InstanceType<typeof OrganiDialog> | null>(null);

const openDialog = (ref: string) => {
  if (ref == "infoRef") infoRef.value?.openDialog();
  if (ref == "passwordRef") passwordRef.value?.openDialog();
  if (ref == "organiRef") organiRef.value?.openDialog();
};
</script>

<style scoped lang="scss">
.avatar {
  width: 40px;
  height: 40px;
  overflow: hidden;
  cursor: pointer;
  border-radius: 50%;
  img {
    width: 100%;
    height: 100%;
  }
}
</style>
