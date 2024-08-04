<template>
  <div>
    <el-dialog
      @closed="closed"
      v-model="data.dialogShow"
      :title="(data.api_id !== 0 ? `编辑` : `添加`) + `接口权限`"
      :with-header="true"
    >
      <el-form ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="170px">
        <el-form-item label="接口地址" prop="api">
          <el-input v-model="ruleForm.api">
            <template #prepend>{{ data.domain_name + "/" }}</template>
          </el-input>
        </el-form-item>

        <el-form-item label="添加日志" prop="add_log">
          <el-switch v-model="ruleForm.add_log" :active-value="1" :inactive-value="0" :active-text="`是`" :inactive-text="`否`" />
        </el-form-item>
        <el-form-item class="save-button">
          <el-button type="primary" @click="submitForm(ruleFormRef)">{{ data.api_id !== 0 ? `编辑` : `添加` }}</el-button>
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
import { menuAuthApiAdd, menuAuthApiEdit } from "@/api/modules/saasDevelopMenu";

/* 表单 */
const ruleFormRef = ref<FormInstance>();

interface RuleForm {
  auth_id: number;
  menu_id: number;
  add_log: 1 | 0;
  api: string;
  [key: string]: any;
}

const originalForm = (): RuleForm => {
  return {
    auth_id: 0,
    menu_id: 0,
    add_log: 1,
    api: ""
  };
};
/* 参数 */
const data = reactive<{ dialogShow: boolean; api_id: number; oldForm: RuleForm; domain_name: string }>({
  dialogShow: false,
  api_id: 0,
  domain_name: import.meta.env.VITE_DEV_API_URL,
  oldForm: originalForm()
});

/*  */

const ruleForm = reactive<RuleForm>(originalForm());

const rules = reactive<FormRules>({
  api: [
    { required: true, message: "接口地址不为空", trigger: "blur" },
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
      if (data.api_id !== 0) {
        await menuAuthApiEdit(ruleForm, data.api_id);
        ElMessage.success("编辑成功");
      } else {
        await menuAuthApiAdd(ruleForm);
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

  const original: RuleForm = data.api_id === 0 ? originalForm() : data.oldForm;
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
const open = (auth_id: number = 0, params: RuleForm | null = null, menu_id: number) => {
  if (params !== null) {
    data.api_id = params.api_id;
    for (let key in params) {
      if (ruleForm.hasOwnProperty(key)) ruleForm[key] = params[key];
      if (data.oldForm.hasOwnProperty(key)) data.oldForm[key] = params[key];
    }
  } else {
    data.api_id = 0;
    ruleForm.auth_id = auth_id;
  }
  ruleForm.menu_id = menu_id;

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
