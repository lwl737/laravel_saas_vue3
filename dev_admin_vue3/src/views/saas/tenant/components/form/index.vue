<template>
  <el-drawer
    @closed="closed"
    size="30%"
    v-model="data.drawerShow"
    direction="ltr"
    :title="(data.tenant_id !== 0 ? `编辑` : `添加`) + `租户`"
    :with-header="true"
  >
    <el-form ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="80px">
      <el-form-item label="租户名称" prop="tenant_name">
        <el-input v-model="ruleForm.tenant_name" />
      </el-form-item>

      <el-form-item label="排序" prop="tenant_sort">
        <el-input-number v-model="ruleForm.tenant_sort" :min="0" :max="999999" />
      </el-form-item>

      <el-form-item label="状态" prop="status">
        <el-switch
          v-model="ruleForm.status"
          :active-value="1"
          :inactive-value="0"
          :active-text="`启用`"
          :inactive-text="`禁用`"
        />
      </el-form-item>

      <el-form-item class="save-button">
        <el-button type="primary" @click="() => submitForm(ruleFormRef)">{{ data.tenant_id !== 0 ? `编辑` : `添加` }}</el-button>
        <el-button @click="() => resetForm()">重置</el-button>
      </el-form-item>
    </el-form>
  </el-drawer>
</template>
<script setup lang="ts" name="PagesFormDrawer">
import { reactive, ref } from "vue";
import type { FormInstance, FormRules, FormItemRule } from "element-plus";

import { ElMessage } from "element-plus";
import { add, edit } from "@/api/modules/tenant";
import { Tenant } from "@/api/interface/tenant";

/* 表单 */
const ruleFormRef = ref<FormInstance>();
interface RuleForm {
  tenant_name: string;
  status: 1 | 0;
  tenant_sort: number;
  [key: string]: any;
}
const originalForm = (): RuleForm => {
  return {
    tenant_name: "",
    status: 1,
    tenant_sort: 50
  };
};
/* 参数 */
const data = reactive<{
  drawerShow: boolean;
  tenant_id: number;
  oldForm: RuleForm;
}>({
  drawerShow: false,
  tenant_id: 0,
  oldForm: originalForm()
});
/*  */

const ruleForm = reactive<RuleForm>(originalForm());

const rules = reactive<FormRules>({
  tenant_name: [
    { required: true, message: "用户名称不为空", trigger: "blur" },
    {
      max: 50,
      message: "不能超过50字符",
      trigger: "blur"
    }
  ]
});

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return;
  await formEl.validate(async (valid, fields) => {
    if (valid) {
      if (data.tenant_id !== 0) {
        let form = ruleForm;
        await edit({ ...form, tenant_id: data.tenant_id });
        ElMessage.success("编辑成功");
      } else {
        await add(ruleForm);
        ElMessage.success("添加成功");
      }
      data.drawerShow = false;
      emit("close");
    } else {
      console.log("error submit!", fields);
    }
  });
};

const resetForm = () => {
  const original: RuleForm = data.tenant_id === 0 ? originalForm() : data.oldForm;
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
const open = (params: Tenant.List | undefined = undefined) => {
  ruleFormRef.value?.clearValidate();
  if (params !== undefined) {
    data.tenant_id = params.tenant_id;
    let key: keyof RuleForm;
    for (key in params) {
      if (ruleForm.hasOwnProperty(key)) ruleForm[key] = params[key];
      if (data.oldForm.hasOwnProperty(key)) data.oldForm[key] = params[key];
    }
  } else {
    data.tenant_id = 0; //添加
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
@import "./index.scss";
.save-button {
  margin-top: 50px;
}
</style>
