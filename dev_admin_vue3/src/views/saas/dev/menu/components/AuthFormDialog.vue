<template>
  <div>
    <el-dialog
      @closed="closed"
      v-model="data.dialogShow"
      :title="(data.auth_id !== 0 ? `编辑` : `添加`) + `权限`"
      :with-header="true"
    >
      <el-form ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="170px">
        <el-form-item label="权限名称" prop="auth_name">
          <el-input v-model="ruleForm.auth_name" />
        </el-form-item>

        <el-form-item label="排序(从大到小排序)" prop="auth_sort">
          <el-input-number v-model="ruleForm.auth_sort" :min="0" />
        </el-form-item>

        <el-form-item class="save-button">
          <el-button type="primary" @click="submitForm(ruleFormRef)">{{ data.auth_id !== 0 ? `编辑` : `添加` }}</el-button>
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
import { menuAuthAdd, menuAuthEdit } from "@/api/modules/saasDevelopMenu";

/* 表单 */
const ruleFormRef = ref<FormInstance>();

interface RuleForm {
  menu_id: number;
  auth_name: string;
  auth_sort: number;
  [key: string]: any;
}

const originalForm = (): RuleForm => {
  return {
    menu_id: 0,
    auth_name: "",
    auth_sort: 50
  };
};
/* 参数 */
const data = reactive<{ dialogShow: boolean; auth_id: number; oldForm: RuleForm }>({
  dialogShow: false,
  auth_id: 0,
  oldForm: originalForm()
});

/*  */

const ruleForm = reactive<RuleForm>(originalForm());

const rules = reactive<FormRules>({
  auth_name: [
    { required: true, message: "权限名称不为空", trigger: "blur" },
    {
      max: 255,
      message: "不能超过255字符",
      trigger: "blur"
    }
  ],
  auth_sort: [{ required: true, message: "权限排序不为空", trigger: "blur" }]
});

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return;
  await formEl.validate(async (valid, fields) => {
    if (valid) {
      if (data.auth_id !== 0) {
        await menuAuthEdit(ruleForm, data.auth_id);
        ElMessage.success("编辑成功");
      } else {
        await menuAuthAdd(ruleForm);
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

  const original: RuleForm = data.auth_id === 0 ? originalForm() : data.oldForm;
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
const open = (menu_id: number = 0, params: RuleForm | null = null) => {
  if (params !== null) {
    data.auth_id = params.auth_id;
    for (let key in params) {
      if (ruleForm.hasOwnProperty(key)) ruleForm[key] = params[key];
      if (data.oldForm.hasOwnProperty(key)) data.oldForm[key] = params[key];
    }
  } else {
    data.auth_id = 0;
    ruleForm.menu_id = menu_id;
  }
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
