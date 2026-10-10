<template>
  <div>
    <el-drawer
      @closed="closed"
      v-model="data.drawerShow"
      direction="ltr"
      :title="(data.dir_id != '0' ? `编辑` : `添加`) + `目录`"
      :with-header="true"
    >
      <el-form ref="ruleFormRef" :model="ruleForm" label-width="170px">
        <el-form-item label="目录名称">
          <el-input v-model="ruleForm.dir_name" />
        </el-form-item>

        <el-form-item label="排序(从大到小排序)">
          <el-input-number v-model="ruleForm.dir_sort" :min="0" :max="999999" />
        </el-form-item>

        <el-form-item class="save-button">
          <el-button type="primary" @click="submitForm(ruleFormRef)">{{ data.dir_id != "0" ? `编辑` : `添加` }}</el-button>
          <el-button @click="resetForm(ruleFormRef)">重置</el-button>
        </el-form-item>
      </el-form>
    </el-drawer>
  </div>
</template>
<script setup lang="ts" name="PagesFormDrawer">
import { reactive, ref, nextTick } from "vue";
import type { FormInstance, FormRules } from "element-plus";
// import { checkPhoneNumber } from "@/utils/eleValidate";
import { ElMessage } from "element-plus";
import { dirAdd, dirEdit } from "@/api/modules/upload";

/* 表单 */
const ruleFormRef = ref<FormInstance>();

interface RuleForm {
  dir_name: string;
  dir_sort: number;
  dir_pid: string;
  [key: string]: any;
}

const originalForm = (): RuleForm => {
  return {
    dir_name: "",
    dir_sort: 50,
    dir_pid: "0"
  };
};
/* 参数 */
const data = reactive<{ drawerShow: boolean; dir_id: string; oldForm: RuleForm }>({
  drawerShow: false,
  dir_id: "0",
  oldForm: originalForm()
});

/*  */

const ruleForm = reactive<RuleForm>(originalForm());

const rules = reactive<FormRules>({
  dir_name: [
    { required: true, message: "文件夹名称不为空", trigger: "blur" },
    {
      max: 30,
      message: "不能超过30字符",
      trigger: "blur"
    }
  ]
});

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return;
  await formEl.validate(async (valid, fields) => {
    if (valid) {
      if (data.dir_id !== "0") {
        await dirEdit({ ...ruleForm }, data.dir_id);
        ElMessage.success("编辑成功");
      } else {
        await dirAdd({ ...ruleForm });
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

  const original: RuleForm = data.dir_id === "0" ? originalForm() : data.oldForm;
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
const open = (dir_pid: string = "0", dir_id: string = "0", params: RuleForm | null = null) => {
  data.dir_id = dir_id;
  if (params !== null) {
    for (let key in params) {
      if (ruleForm.hasOwnProperty(key)) ruleForm[key] = params[key];
      if (data.oldForm.hasOwnProperty(key)) data.oldForm[key] = params[key];
    }
  } else {
    ruleForm.dir_pid = dir_pid;
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
