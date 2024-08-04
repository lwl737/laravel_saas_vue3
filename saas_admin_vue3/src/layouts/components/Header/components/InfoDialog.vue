<template>
  <el-drawer @closed="closed" size="30%" v-model="dialogVisible" direction="rtl" :title="`个人信息`" :with-header="true">
    <el-form ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="80px">
      <el-form-item label="用户名" prop="username">
        <el-input v-model="ruleForm.username" />
      </el-form-item>

      <el-form-item label="真实姓名" prop="real_name">
        <el-input v-model="ruleForm.real_name" />
      </el-form-item>
      <el-form-item label="手机号码" prop="phone">
        <el-input v-model="ruleForm.phone" />
      </el-form-item>
      <el-form-item label="昵称" prop="nick_name">
        <el-input v-model="ruleForm.nick_name" />
      </el-form-item>
      <el-form-item label="头像" prop="portrait">
        <MaterialPop :fixation-val="[stStore.getImgConfig()]" v-model="ruleForm.portrait" clearable />
      </el-form-item>

      <el-form-item class="save-button">
        <el-button type="primary" @click="() => submitForm(ruleFormRef)">编辑</el-button>
        <el-button @click="() => resetForm()">重置</el-button>
      </el-form-item>
    </el-form>
  </el-drawer>
</template>
<script setup lang="ts" name="PagesFormDrawer">
import { reactive, ref } from "vue";
import type { FormInstance, FormRules } from "element-plus";
import { checkPhoneNumber } from "@/utils/eleValidate";
import MaterialPop from "@/components/MaterialPop/SingleImage/index.vue";
import { useUserStore } from "@/stores/modules/user";
import { editUserInfo } from "@/api/modules/admin";
import { ElMessage } from "element-plus";
import { StStore } from "@/stores/modules/sts";

const dialogVisible = ref(false);

const globalStore = useUserStore();
const stStore = StStore();

// openDialog
const openDialog = () => {
  dialogVisible.value = true;
};

/* 表单 */
const ruleFormRef = ref<FormInstance>();

interface RuleForm {
  username: string;
  nick_name: string;
  portrait: string | undefined;
  real_name: string;
  phone: string;
  [key: string]: any;
}
const originalForm = (): RuleForm => {
  return {
    username: globalStore.userInfoGet?.username ? globalStore.userInfoGet?.username : "",
    nick_name: globalStore.userInfoGet?.nick_name ? globalStore.userInfoGet.nick_name : "",
    portrait: globalStore.userInfoGet?.portrait.value ? globalStore.userInfoGet.portrait.full_url : "",
    real_name: globalStore.userInfoGet?.real_name ? globalStore.userInfoGet?.real_name : "",
    phone: globalStore.userInfoGet?.phone ? globalStore.userInfoGet.phone : ""
  };
};

/* 参数 */
const data = reactive<{
  drawerShow: boolean;
  admins_id: string;
  oldForm: RuleForm;
  restPass: boolean;
  color: string;
  portraitValue: string;
}>({
  drawerShow: false,
  restPass: false,
  portraitValue: "",
  admins_id: "",
  oldForm: originalForm(),
  color: document.documentElement.style.getPropertyValue("--el-color-primary") //全局皮肤颜色
});
/*  */

const ruleForm = reactive<RuleForm>(originalForm());

const rules = reactive<FormRules>({
  username: [
    { required: true, message: "用户名称不为空", trigger: "blur" },
    {
      min: 5,
      message: "不能小于5字符",
      trigger: "blur"
    },
    {
      max: 20,
      message: "不能超过20字符",
      trigger: "blur"
    }
  ],
  nick_name: [
    { required: true, message: "昵称不为空", trigger: "blur" },
    {
      max: 20,
      message: "不能超过20字符",
      trigger: "blur"
    }
  ],
  real_name: [
    { required: true, message: "真实姓名不为空", trigger: "blur" },
    {
      max: 20,
      message: "不能超过20字符",
      trigger: "blur"
    }
  ],
  phone: [
    { required: true, message: "电话号码", trigger: "blur" },
    { required: true, validator: checkPhoneNumber, trigger: "blur" }
  ]
});

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return;
  await formEl.validate(async (valid, fields) => {
    if (valid) {
      await editUserInfo(ruleForm);

      ElMessage.success("编辑成功");

      globalStore.setUserInfo({
        nick_name: ruleForm.nick_name,
        portrait: ruleForm.portrait ? { full_url: ruleForm.portrait, value: ruleForm.portrait } : { full_url: "", value: "" },
        username: ruleForm.username,
        real_name: ruleForm.real_name,
        phone: ruleForm.phone
      });

      dialogVisible.value = false;
    } else {
      console.log("error submit!", fields);
    }
  });
};

const resetForm = () => {
  const original: RuleForm = data.admins_id === "" ? originalForm() : data.oldForm;
  for (let key in original) if (ruleForm.hasOwnProperty(key)) ruleForm[key] = original[key];
};

/**
 * 关闭弹窗清空表单
 */
const closed = () => {
  const original: RuleForm = originalForm();
  for (let key in original) if (ruleForm.hasOwnProperty(key)) ruleForm[key] = original[key];
};

/*  */

defineExpose({ openDialog });
</script>
<style scoped lang="scss">
.save-button {
  margin-top: 50px;
}
</style>
