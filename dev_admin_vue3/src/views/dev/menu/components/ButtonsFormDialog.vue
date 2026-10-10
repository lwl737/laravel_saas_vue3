<template>
  <div>
    <el-dialog
      @closed="closed"
      v-model="data.dialogShow"
      :title="(data.buttons_id !== 0 ? `编辑` : `添加`) + `按钮权限`"
      :with-header="true"
    >
      <el-form ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="170px">
        <el-form-item label="按钮权限标识" prop="buttons">
          <el-input v-model="ruleForm.buttons" />
        </el-form-item>

        <el-form-item class="save-button">
          <el-button type="primary" @click="submitForm(ruleFormRef)">{{ data.buttons_id !== 0 ? `编辑` : `添加` }}</el-button>
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
import { menuAuthButtonsAdd, menuAuthButtonsEdit } from "@/api/modules/develop";

/* 表单 */
const ruleFormRef = ref<FormInstance>();

interface RuleForm {
  auth_id: number;
  buttons: string;
  [key: string]: any;
}

const originalForm = (): RuleForm => {
  return {
    auth_id: 0,
    buttons: ""
  };
};
/* 参数 */
const data = reactive<{ dialogShow: boolean; buttons_id: number; oldForm: RuleForm }>({
  dialogShow: false,
  buttons_id: 0,
  oldForm: originalForm()
});

/*  */

const ruleForm = reactive<RuleForm>(originalForm());

const rules = reactive<FormRules>({
  buttons: [
    { required: true, message: "按钮权限标识不为空", trigger: "blur" },
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
      if (data.buttons_id !== 0) {
        await menuAuthButtonsEdit(ruleForm, data.buttons_id);
        ElMessage.success("编辑成功");
      } else {
        await menuAuthButtonsAdd(ruleForm);
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

  const original: RuleForm = data.buttons_id === 0 ? originalForm() : data.oldForm;
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
const open = (auth_id: number = 0, params: RuleForm | null = null) => {
  if (params !== null) {
    data.buttons_id = params.buttons_id;
    for (let key in params) {
      if (ruleForm.hasOwnProperty(key)) ruleForm[key] = params[key];
      if (data.oldForm.hasOwnProperty(key)) data.oldForm[key] = params[key];
    }
  } else {
    data.buttons_id = 0;
    ruleForm.auth_id = auth_id;
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
