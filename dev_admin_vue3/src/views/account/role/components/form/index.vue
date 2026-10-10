<template>
  <div>
    <el-drawer
      @closed="closed"
      v-model="data.drawerShow"
      direction="ltr"
      :title="(data.role_id !== 0 ? `编辑` : `添加`) + `权限`"
      :with-header="true"
    >
      <el-form ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="140px">
        <el-form-item label="权限名称" prop="role_name">
          <el-input v-model="ruleForm.role_name" />
        </el-form-item>

        <el-form-item label="排序(从大到小)" prop="role_sort">
          <el-input-number v-model="ruleForm.role_sort" :min="0" :max="999999" />
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

        <el-form-item label="权限描述" prop="role_describe">
          <el-input v-model="ruleForm.role_describe" type="textarea" :autosize="{ minRows: 2, maxRows: 4 }" />
        </el-form-item>

        <el-form-item class="save-button">
          <el-button type="primary" @click="() => submitForm(ruleFormRef)">{{ data.role_id !== 0 ? `编辑` : `添加` }}</el-button>
          <el-button class="reset-button" @click="() => resetForm(data.role_id === 0 ? originalForm() : data.oldForm)">
            重置
          </el-button>
        </el-form-item>
      </el-form>
    </el-drawer>
  </div>
</template>
<script setup lang="ts" name="PagesFormDrawer">
import { reactive, ref } from "vue";
import type { FormInstance, FormRules } from "element-plus";
import { ElMessage } from "element-plus";
import { add, edit } from "@/api/modules/role";

/* 表单 */
const ruleFormRef = ref<FormInstance>();

interface RuleForm {
  role_name: string;
  role_sort: number;
  role_describe: string;
  status:1|0;
  [key: string]: any;
}

const originalForm = (): RuleForm => {
  return {
    role_name: "",
    role_sort: 50,
    status:1,
    role_describe: ""
  };
};
/* 参数 */
const data = reactive<{
  drawerShow: boolean;
  role_id: number;
  oldForm: RuleForm;
}>({
  drawerShow: false,
  role_id: 0,
  oldForm: originalForm()
});

/*  */

const ruleForm = reactive<RuleForm>(originalForm());

const rules = reactive<FormRules>({
  role_name: [
    { required: true, message: "权限名称不为空", trigger: "blur" },
    {
      max: 255,
      message: "不能超过255字符",
      trigger: "blur"
    }
  ],
  role_describe: [
    {
      max: 512,
      message: "权限描述不超过512字符",
      trigger: "blur"
    }
  ]
});

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return;
  await formEl.validate(async (valid, fields) => {
    if (valid) {
      if (data.role_id !== 0) {
        await edit(ruleForm, data.role_id);
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

const resetForm = (original: RuleForm) => {
  let key: keyof RuleForm;
  for (key in original) ruleForm[key] = original[key];
};
/**
 * 关闭弹窗清空表单
 */
const closed = () => {
  resetForm(originalForm());
};

/*  */

/**
 * 对外暴露子组建方法
 */
const open = (params: RuleForm | undefined = undefined) => {
  data.role_id = 0; //添加
  if (params !== undefined) {
    data.role_id = params.role_id;
    let key: keyof RuleForm;
    for (key in params) {
      if (ruleForm.hasOwnProperty(key)) ruleForm[key] = params[key];
      if (data.oldForm.hasOwnProperty(key)) data.oldForm[key] = params[key];
    }
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
</style>
