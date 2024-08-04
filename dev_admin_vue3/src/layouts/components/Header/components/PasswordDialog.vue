<template>
  <el-dialog @closed="close" v-model="dialogVisible" title="修改密码" width="500px" draggable>
    <el-form ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="100px">
      <el-form-item label="原密码" prop="ori_password" class="relative">
        <el-input :type="showPass" v-model="ruleForm.ori_password"> </el-input>

        <div class="absolute right-[10px] thumb">
          <el-icon @click="showPass = 'text'" v-show="showPass === 'password'">
            <View />
          </el-icon>
          <el-icon @click="showPass = 'password'" v-show="showPass === 'text'">
            <Hide />
          </el-icon>
        </div>
      </el-form-item>
      <el-form-item label="新密码" prop="password">
        <el-input type="password" v-model="ruleForm.password" />
      </el-form-item>
      <el-form-item label="确认新密码" prop="confirm_password">
        <el-input type="password" v-model="ruleForm.confirm_password" />
      </el-form-item>
    </el-form>
    <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="() => confirm(ruleFormRef)">确认</el-button>
      </span>
    </template>
  </el-dialog>
</template>

<script setup lang="ts">
import { ref, reactive } from "vue";
import { editPass } from "@/api/modules/admin";
import { View, Hide } from "@element-plus/icons-vue";
import md5 from "md5";
import type { FormInstance, FormRules } from "element-plus";
import { ElMessage } from "element-plus";

const dialogVisible = ref(false);
// openDialog
const openDialog = () => {
  dialogVisible.value = true;
};

const showPass = ref<"password" | "text">("password");

const ruleFormRef = ref<FormInstance>();

const checkConfirm = (rule: any, value: any, callback: any) => {
  if (ruleForm.value.confirm_password !== ruleForm.value.password) {
    return callback(new Error("两次输入的密码不一致"));
  } else {
    callback();
  }
};

const confirm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return;

  try {
    await formEl.validate(async valid => {
      if (valid) {
        await editPass({
          password: md5(ruleForm.value.password),
          ori_password: md5(ruleForm.value.ori_password)
        });

        ElMessage.success("编辑成功");
        dialogVisible.value = false;
      }
    });
  } catch {}
};

const close = () => {
  showPass.value = "password";
  ruleForm.value = original();
};

const rules = reactive<FormRules>({
  password: [
    { required: true, message: "密码不为空", trigger: "blur" },
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
  ori_password: [
    { required: true, message: "原密码不为空", trigger: "blur" },
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
  confirm_password: [
    { required: true, message: "密码不为空", trigger: "blur" },
    {
      min: 5,
      message: "不能小于5字符",
      trigger: "blur"
    },
    {
      max: 20,
      message: "不能超过20字符",
      trigger: "blur"
    },
    { validator: checkConfirm, trigger: "blur" }
  ]
});

const original = () => {
  return { password: "", ori_password: "", confirm_password: "" };
};

const ruleForm = ref(original());

defineExpose({ openDialog });
</script>
