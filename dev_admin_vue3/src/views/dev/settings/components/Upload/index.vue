<template>
  <div class="">
    <el-form ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="180px">
      <el-form-item label="网站前缀URL" prop="prefix_url">
        <el-input v-model="ruleForm.prefix_url" />
      </el-form-item>
      <el-form-item label="切片大小（kb）" prop="upload_slice_size">
        <el-input-number v-model="ruleForm.upload_slice_size" :min="100" :max="1024" />
      </el-form-item>
      <el-form-item label="最大文件大小（kb）" prop="upload_max_size">
        <el-input-number v-model="ruleForm.upload_max_size" :min="0" :max="2048000" />
        <span class="mb"> {{ (ruleForm.upload_max_size / 1024).toFixed(2) }}MB</span>
      </el-form-item>
      <el-form-item label="素材库最大容量（kb）" prop="library_max_capacity">
        <el-input-number v-model="ruleForm.library_max_capacity" :min="1" />
        <span class="mb"> {{ (ruleForm.library_max_capacity / 1024).toFixed(2) }}MB</span>
      </el-form-item>
      <el-form-item label="开启文件类型限制" prop="upload_check_file_type">
        <el-switch v-model="ruleForm.upload_check_file_type" class="ml-2" :active-value="1" :inactive-value="0" />
      </el-form-item>
      <el-form-item label="文件类型限制" prop="upload_file_type">
        <el-table :data="ruleForm.upload_file_type" style="width: 100%">
          <el-table-column label="#" width="80">
            <template #default="scope">
              {{ scope.$index + 1 }}
            </template>
          </el-table-column>
          <el-table-column label="类型" width="180">
            <template #default="scope">
              <el-input v-model="ruleForm.upload_file_type[scope.$index].type"></el-input>
            </template>
          </el-table-column>
          <el-table-column label="名称" width="180">
            <template #default="scope">
              <el-input v-model="ruleForm.upload_file_type[scope.$index].name"></el-input>
            </template>
          </el-table-column>
          <el-table-column label="唯一标识" width="180">
            <template #default="scope">
              <el-input v-model="ruleForm.upload_file_type[scope.$index].only"></el-input>
            </template>
          </el-table-column>
          <el-table-column label="操作">
            <template #default="scope">
              <el-button size="small" type="danger" @click="handleDelete(scope.$index)">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-form-item>
      <el-form-item>
        <div class="btn">
          <el-button type="primary" :icon="CirclePlus" size="small" @click="addRow">添加</el-button>
        </div>
      </el-form-item>

      <el-form-item label="是否开启OSS" prop="oss_start">
        <el-switch v-model="ruleForm.oss_start" class="ml-2" :active-value="1" :inactive-value="0" />
      </el-form-item>

      <el-form-item label="oss_access_key_id" prop="oss_access_key_id">
        <el-input v-model="ruleForm.oss_access_key_id" />
      </el-form-item>

      <el-form-item label="oss_access_key_secret" prop="oss_access_key_secret">
        <el-input v-model="ruleForm.oss_access_key_secret" />
      </el-form-item>

      <el-form-item label="endpoint" prop="oss_endpoint">
        <el-input v-model="ruleForm.oss_endpoint" />
        <span class="ml-[10px]">guangzhou.aliyuncs.com</span>
      </el-form-item>

      <el-form-item label="bucket" prop="oss_bucket">
        <el-input v-model="ruleForm.oss_bucket" />
      </el-form-item>

      <el-form-item label="roleArn" prop="oss_role_arr">
        <el-input v-model="ruleForm.oss_role_arr" />
      </el-form-item>

      <el-form-item class="save-button">
        <el-button type="primary" @click="submitForm(ruleFormRef)">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>
<script setup lang="ts" name="">
import { ref, reactive } from "vue";
import type { FormInstance, FormRules } from "element-plus";
import { CirclePlus } from "@element-plus/icons-vue";

const ruleFormRef = ref<FormInstance>();

interface RuleForm {
  upload_slice_size: number;
  upload_max_size: number;
  library_max_capacity: number;
  upload_check_file_type: 1 | 0;
  upload_file_type: FileTypes[];
  oss_start: 1 | 0;
  oss_access_key_id: string;
  oss_access_key_secret: string;
  oss_endpoint: string;
  oss_bucket: string;
  oss_role_arr: string;
  prefix_url: string;
  [key: string]: any;
}

const originalForm = (): RuleForm => {
  return {
    upload_slice_size: 100,
    upload_max_size: 204800,
    library_max_capacity: 1024 * 1024, //1gb
    upload_check_file_type: 1,
    upload_file_type: [],
    oss_start: 0,
    oss_access_key_id: "",
    oss_access_key_secret: "",
    oss_endpoint: "",
    oss_bucket: "",
    oss_role_arr: "",
    prefix_url: ""
  };
};

const handleDelete = (index: number) => {
  ruleForm.upload_file_type.splice(index, 1);
};
const addRow = () => {
  ruleForm.upload_file_type.push({ type: "", name: "", only: "" });
};

const ruleForm = reactive<RuleForm>(originalForm());

const rules = reactive<FormRules>({
  upload_slice_size: [{ required: true, message: "切片大小不为空", trigger: "blur" }],
  prefix_url: [{ max: 255, message: "不能超过255个字符", trigger: "blur" }],
  upload_max_size: [{ required: true, message: "最大上传文件大小不为空", trigger: "blur" }],
  oss_access_key_id: [{ type: "string", max: 128, message: "oss_access_key_id不超过128个字符", trigger: "blur" }],
  oss_access_key_secret: [{ type: "string", max: 128, message: "oss_access_key_secret不超过128个字符", trigger: "blur" }],
  oss_endpoint: [{ type: "string", max: 255, message: "oss_endpoint不超过255个字符", trigger: "blur" }],
  oss_bucket: [{ type: "string", max: 255, message: "oss_bucket不超过255个字符", trigger: "blur" }],
  oss_role_arr: [{ type: "string", max: 255, message: "oss_role_arr不超过255个字符", trigger: "blur" }],
  upload_file_type: [
    {
      validator: (rule: any, value: FileTypes[], callback: any) => {
        value.forEach((item: FileTypes, index: number) => {
          if (!item.type.trim()) return callback(new Error(`下标${index + 1}类型不为空`));
          if (!item.name.trim()) return callback(new Error(`下标${index + 1}名称不为空`));
          if (!item.only.trim()) return callback(new Error(`下标${index + 1}唯一标识不为空`));
        });
        return callback();
      },
      trigger: "blur"
    }
  ]
});

/**
 * 事件回调
 */
const emits = defineEmits<{
  (event: "submitForm", ruleForm: RuleForm): void;
}>();

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return;
  await formEl.validate(async (valid, fields) => {
    if (valid) {
      emits("submitForm", ruleForm);
    } else {
      console.log("error submit!", fields);
    }
  });
};

const initData = (params: RuleForm) => {
  let key: keyof RuleForm;
  for (key in params) ruleForm[key] = params[key];
};

defineExpose({ initData, ruleForm });
</script>
<style scoped lang="scss">
@import "./index.scss";
</style>
