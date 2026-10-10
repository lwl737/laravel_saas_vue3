<template>
  <div>
    <el-dialog
      @closed="closed"
      v-model="data.dialogShow"
      :title="(data.menu_id !== 0 ? `编辑` : `添加`) + `页面`"
      :with-header="true"
    >
      <el-form ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="170px">
        <el-form-item label="路由路径(path)" prop="path">
          <el-input v-model="ruleForm.path" />
        </el-form-item>

        <el-form-item label="栏目名称(title)" prop="title">
          <el-input v-model="ruleForm.title" />
        </el-form-item>

        <el-form-item label="组件路径(component)" prop="component">
          <el-input v-model="ruleForm.component" />
        </el-form-item>

        <el-form-item label="图标(icon)" prop="icon">
          <el-input v-model="ruleForm.icon" />
        </el-form-item>

        <el-form-item label="路由名称(name)" prop="name">
          <el-input v-model="ruleForm.name" />
        </el-form-item>
        <el-form-item label="重定向(redirect)" prop="redirect">
          <el-input v-model="ruleForm.redirect" />
        </el-form-item>

        <el-form-item label="外链" prop="isLink">
          <el-input v-model="ruleForm.isLink" />
        </el-form-item>

        <el-form-item label="缓存" prop="isKeepAlive">
          <el-switch
            v-model="ruleForm.isKeepAlive"
            :active-value="1"
            :inactive-value="0"
            :active-text="`是`"
            :inactive-text="`否`"
          />
        </el-form-item>

        <el-form-item label="全屏" prop="isFull">
          <el-switch v-model="ruleForm.isFull" :active-value="1" :inactive-value="0" :active-text="`是`" :inactive-text="`否`" />
        </el-form-item>

        <el-form-item label="在头部tabs显示" prop="isAffix">
          <el-switch v-model="ruleForm.isAffix" :active-value="1" :inactive-value="0" :active-text="`是`" :inactive-text="`否`" />
        </el-form-item>

        <el-form-item class="save-button">
          <el-button type="primary" @click="submitForm(ruleFormRef)">{{ data.menu_id !== 0 ? `编辑` : `添加` }}</el-button>
          <el-button @click="resetForm(ruleFormRef)">重置</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>
<script setup lang="ts" name="PagesFormDrawer">
import { reactive, ref } from "vue";
import type { FormInstance, FormRules } from "element-plus";
// import { checkPhoneNumber } from "@/utils/eleValidate";
import { ElMessage } from "element-plus";
import { menuAuthPagesAdd, menuAuthPagesEdit } from "@/api/modules/develop";

/* 表单 */
const ruleFormRef = ref<FormInstance>();

interface RuleForm {
  path: string;
  component: string;
  name: string;
  title: string;
  icon: string;
  redirect: string;
  isLink: string;
  isFull: 1 | 0;
  isAffix: 1 | 0;
  isKeepAlive: 1 | 0;
  [key: string]: any;
}

const originalForm = (): RuleForm => {
  return {
    path: "",
    component: "",
    name: "",
    icon: "",
    title: "",
    redirect: "",
    isLink: "",
    isFull: 0,
    isAffix: 0,
    isKeepAlive: 1
  };
};
/* 参数 */
const data = reactive<{ dialogShow: boolean; auth_id: number; menu_id: number; menu_pid: number; oldForm: RuleForm }>({
  dialogShow: false,
  menu_id: 0,
  auth_id: 0,
  menu_pid: 0,
  oldForm: originalForm()
});

/*  */

const ruleForm = reactive<RuleForm>(originalForm());

const rules = reactive<FormRules>({
  path: [
    { required: true, message: "页面路径不为空", trigger: "blur" },
    {
      max: 255,
      message: "不能超过255字符",
      trigger: "blur"
    }
  ],
  router_name: [
    {
      max: 255,
      message: "不能超过255字符",
      trigger: "blur"
    }
  ],
  title: [
    { required: true, message: "栏目名称不为空", trigger: "blur" },
    {
      max: 20,
      message: "不能超过20字符",
      trigger: "blur"
    }
  ],
  component: [
    { required: true, message: "组件不为空", trigger: "blur" },
    {
      max: 255,
      message: "不能超过255字符",
      trigger: "blur"
    }
  ]
});

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return;
  await formEl.validate(async (valid, fields) => {
    if (valid) {
      if (data.menu_id !== 0) {
        await menuAuthPagesEdit({ ...ruleForm, menu_id: data.menu_id });
        ElMessage.success("编辑成功");
      } else {
        await menuAuthPagesAdd({ ...ruleForm, auth_id: data.auth_id, menu_pid: data.menu_pid });
        ElMessage.success("添加成功");
      }

      data.dialogShow = false;
      emit("close");
    } else {
      console.log("error submit!", fields);
    }
  });
};

const resetForm = (formEl: FormInstance | undefined) => {
  if (!formEl) return;

  const original: RuleForm = data.menu_id === 0 ? originalForm() : data.oldForm;
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

/**
 * 对外暴露子组建方法
 */
const open = (menu_pid: number = 0, auth_id: number = 0, menu_id: number = 0, params: RuleForm | null = null) => {
  if (params !== null) {
    for (let key in params) {
      if (ruleForm.hasOwnProperty(key)) ruleForm[key] = params[key];
      if (data.oldForm.hasOwnProperty(key)) data.oldForm[key] = params[key];
    }
  }
  data.menu_pid = menu_pid;
  data.menu_id = menu_id;
  data.auth_id = auth_id;
  data.dialogShow = true;
};
defineExpose({
  open
});
/*  */

/**
 * 事件回调
 */
const emit = defineEmits<{
  (event: "close"): void;
}>();
/*  */
</script>
<style scoped lang="scss">
.save-button {
  margin-top: 50px;
}
</style>
