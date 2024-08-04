<template>
  <div class="">
    <el-drawer
      @closed="closed"
      v-model="data.drawerShow"
      direction="ltr"
      :title="(data.organi_id !== 0 ? `编辑` : `添加`) + `部门`"
      :with-header="true"
    >
      <el-form ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="170px">
        <el-form-item label="部门名称" prop="organi_name">
          <el-input v-model="ruleForm.organi_name" />
        </el-form-item>

        <el-form-item label="排序(从大到小排序)" prop="organi_sort">
          <el-input-number v-model="ruleForm.organi_sort" :min="0" :max="999999" />
        </el-form-item>

        <el-form-item class="save-button">
          <el-button type="primary" @click="submitForm(ruleFormRef)">{{ data.organi_id !== 0 ? `编辑` : `添加` }}</el-button>
          <el-button @click="resetForm(ruleFormRef)">重置</el-button>
        </el-form-item>
      </el-form>
    </el-drawer>
  </div>
</template>

<script setup lang="tsx">
import { reactive, ref } from "vue";
import type { FormInstance, FormRules } from "element-plus";
import { ElMessage } from "element-plus";
import { add, edit } from "@/api/modules/organi";

/* 表单 */
const ruleFormRef = ref<FormInstance>();

interface RuleForm {
  organi_name: string;
  organi_sort: number;
  organi_pid: number;
}

const originalForm = (): RuleForm => {
  return {
    organi_name: "",
    organi_sort: 50,
    organi_pid: 0
  };
};
/* 参数 */
const data = reactive<{
  drawerShow: boolean;
  show_hospital: boolean;
  oldForm: RuleForm;
  organi_id: number;
  hospitalLoading: boolean;
}>({
  drawerShow: false,
  show_hospital: false,
  organi_id: 0,
  hospitalLoading: false,
  oldForm: originalForm()
});
/*  */

const ruleForm = reactive<RuleForm>(originalForm());

const rules = reactive<FormRules>({
  organi_name: [
    { required: true, message: "部门名称不为空", trigger: "blur" },
    {
      max: 255,
      message: "不能超过255字符",
      trigger: "blur"
    }
  ],
  organi_sort: [{ required: true, message: "部门排序不为空", trigger: "blur" }]
});

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return;
  await formEl.validate(async (valid, fields) => {
    if (valid) {
      if (data.organi_id !== 0) {
        await edit({ ...ruleForm, organi_id: data.organi_id });
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

const resetForm = (formEl: FormInstance | undefined) => {
  if (!formEl) return;

  const original: RuleForm = data.organi_id === 0 ? originalForm() : data.oldForm;
  for (let key in original) if (ruleForm.hasOwnProperty(key)) ruleForm[key] = original[key];
};

/**
 * 关闭弹窗清空表单
 */
const closed = () => {
  const original: RuleForm = originalForm();
  for (let key in original) if (ruleForm.hasOwnProperty(key)) ruleForm[key] = original[key];
  ruleFormRef.value?.clearValidate();
};

/*  */

/**
 * 对外暴露子组建方法
 */
const open = (organi_pid: number = 0, organi_id: number = 0, params: RuleForm | null = null) => {
  data.organi_id = organi_id;
  data.drawerShow = true;
  if (params !== null) {
    for (let key in params) {
      if (ruleForm.hasOwnProperty(key)) ruleForm[key] = params[key];
      if (data.oldForm.hasOwnProperty(key)) data.oldForm[key] = params[key];
    }
  } else {
    ruleForm.organi_pid = organi_pid;
  }
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
</style>
