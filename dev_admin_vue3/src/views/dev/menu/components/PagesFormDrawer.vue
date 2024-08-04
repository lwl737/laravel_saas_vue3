<template>
  <div>
    <el-drawer
      @closed="closed"
      v-model="data.drawerShow"
      direction="ltr"
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

        <el-form-item label="排序(从大到小排序)" prop="menu_sort">
          <el-input-number v-model="ruleForm.menu_sort" :min="0" />
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

        <el-form-item label="菜单隐藏" prop="isHide">
          <el-switch v-model="ruleForm.isHide" :active-value="1" :inactive-value="0" :active-text="`是`" :inactive-text="`否`" />
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
    </el-drawer>
  </div>
</template>
<script setup lang="ts" name="PagesFormDrawer">
import { reactive, ref } from "vue";
import type { FormInstance, FormRules } from "element-plus";
// import { checkPhoneNumber } from "@/utils/eleValidate";
import { ElMessage } from "element-plus";
import { menuAdd, menuEdit } from "@/api/modules/develop";

/* 表单 */
const ruleFormRef = ref<FormInstance>();

interface RuleForm {
  menu_pid: number;
  path: string;
  name: string;
  title: string;
  icon: string;
  redirect: string;
  component: string;
  menu_sort: number;
  isLink: string;
  isHide: 1 | 0;
  isFull: 1 | 0;
  isAffix: 1 | 0;
  isKeepAlive: 1 | 0;
  [key: string]: any;
}

const originalForm = (): RuleForm => {
  return {
    menu_pid: 0,
    path: "",
    name: "",
    icon: "",
    title: "",
    redirect: "",
    component: "",
    menu_sort: 50,
    isLink: "",
    isHide: 0,
    isFull: 0,
    isAffix: 0,
    isKeepAlive: 1
  };
};
/* 参数 */
const data = reactive<{ drawerShow: boolean; menu_id: number; oldForm: RuleForm }>({
  drawerShow: false,
  menu_id: 0,
  oldForm: originalForm()
});

/*  */

const ruleForm = reactive<RuleForm>(originalForm());

const rules = reactive<FormRules>({
  path: [
    { required: true, message: "路由路径不为空", trigger: "blur" },
    {
      max: 255,
      message: "不能超过255字符",
      trigger: "blur"
    }
  ],
  component: [
    {
      max: 255,
      message: "不能超过255字符",
      trigger: "blur"
    }
  ],
  isLink: [
    {
      max: 512,
      message: "外链不能超过512字符",
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
  icon: [
    {
      max: 20,
      message: "不能超过20字符",
      trigger: "blur"
    }
  ]
});

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return;
  await formEl.validate(async (valid, fields) => {
    if (valid) {
      if (data.menu_id !== 0) {
        await menuEdit(ruleForm, data.menu_id);
        ElMessage.success("编辑成功");
      } else {
        await menuAdd(ruleForm);
        ElMessage.success("添加成功");
      }

      data.drawerShow = false;
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
const open = (menu_pid: number = 0, menu_id: number = 0, params: RuleForm | null = null) => {
  data.menu_id = menu_id;
  if (params !== null) {
    for (let key in params) {
      if (ruleForm.hasOwnProperty(key)) ruleForm[key] = params[key];
      if (data.oldForm.hasOwnProperty(key)) data.oldForm[key] = params[key];
    }
  } else {
    ruleForm.menu_pid = menu_pid;
  }
  data.drawerShow = true;
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
