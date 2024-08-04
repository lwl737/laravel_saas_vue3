<template>
  <el-drawer
    @closed="closed"
    size="30%"
    v-model="data.drawerShow"
    direction="ltr"
    :title="(data.admins_id !== 0 ? `编辑` : `添加`) + `用户`"
    :with-header="true"
  >
    <el-form ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="80px">
      <el-form-item label="用户名" prop="username">
        <el-input v-model="ruleForm.username" />
      </el-form-item>

      <el-form-item label="密码" prop="password" class="elFormItem">
        <el-input
          v-model="ruleForm.password"
          type="password"
          class="input"
          v-show="data.admins_id === 0 || (data.admins_id !== 0 && data.restPass)"
        />
        <el-button
          :class="[data.restPass ? 'button' : '']"
          v-show="data.admins_id !== 0"
          type="primary"
          @click="() => restPass(ruleFormRef)"
        >
          {{ !data.restPass ? `重置密码` : `取消重置` }}
        </el-button>
      </el-form-item>

      <el-form-item label="所属权限" prop="role_id">
        <!-- {{ data.roleSelect }} -->
        <el-select v-model="ruleForm.role_id" filterable placeholder="请选择所属权限" :loading="data.rolrSelectLoad">
          <el-option v-for="item in data.roleSelect" :key="item.role_id" :label="item.role_name" :value="item.role_id" />
        </el-select>
      </el-form-item>
      <el-form-item label="所属部门" prop="organi_id">
        <!-- <el-select v-model="ruleForm.role_id" filterable placeholder="请选择所属权限" :loading="data.rolrSelectLoad">
          <el-option v-for="item in data.roleSelect" :key="item.role_id" :label="item.role_name" :value="item.role_id" />
        </el-select> -->
        <el-tree-select
          v-model="ruleForm.organi_id"
          :loading="data.organiSelectLoad"
          :data="data.organiSelect"
          node-key="organi_id"
          :props="{ value: 'organi_id', label: 'organi_name', children: 'children' }"
          multiple
          :render-after-expand="false"
          default-expand-all
          show-checkbox
          check-strictly
          check-on-click-node
        />
      </el-form-item>
      <el-form-item label="真实姓名" prop="real_name">
        <el-input v-model="ruleForm.real_name" />
      </el-form-item>
      <el-form-item label="手机号码" prop="phone">
        <el-input v-model="ruleForm.phone" />
      </el-form-item>
      <el-form-item label="昵称" prop="nick_name">
        <el-input v-model="ruleForm.nick_name" />
      </el-form-item>
      <el-form-item label="头像" prop="portrait">
        <!-- <Upload v-model:value="ruleForm.portrait" v-model:show-value="data.portraitValue" clearable /> -->
        <MaterialPop :fixation-val="[stStore.getImgConfig()]" clearable v-model="ruleForm.portrait" />
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
        <el-button type="primary" @click="() => submitForm(ruleFormRef)">{{ data.admins_id !== 0 ? `编辑` : `添加` }}</el-button>
        <el-button @click="() => resetForm()">重置</el-button>
      </el-form-item>
    </el-form>
  </el-drawer>
</template>
<script setup lang="ts" name="PagesFormDrawer">
import { reactive, ref } from "vue";
import type { FormInstance, FormRules, FormItemRule } from "element-plus";

import { ElMessage } from "element-plus";
import { add, edit, organiAll, roleAll } from "@/api/modules/admin";
import { Admin } from "@/api/interface/admin";
import { checkPhoneNumber } from "@/utils/eleValidate";
import { StStore } from "@/stores/modules/sts";
import MaterialPop from "@/components/MaterialPop/SingleImage/index.vue";

/* 表单 */
const ruleFormRef = ref<FormInstance>();
interface RuleForm<ROLE_ID = number | undefined, ADMINS_ID = undefined | number> {
  username: string;
  password?: string;
  admins_id: ADMINS_ID;
  nick_name: string;
  status: 1 | 0;
  role_id: ROLE_ID;
  portrait: string | undefined;
  real_name: string;
  phone: string;
  organi_id: number[];
  [key: string]: any;
}
const originalForm = (): RuleForm => {
  return {
    username: "",
    password: "",
    nick_name: "",
    status: 1,
    organi_id: [],
    phone: "",
    portrait: undefined,
    role_id: undefined,
    admins_id: undefined,
    real_name: ""
  };
};
/* 参数 */
const data = reactive<{
  drawerShow: boolean;
  admins_id: number;
  oldForm: RuleForm;
  restPass: boolean;
  roleSelect: Admin.RoleAllRes["all"];
  organiSelect: Admin.OrganiAllRes["tree"];
  rolrSelectLoad: boolean;
  organiSelectLoad: boolean;
  color: string;
  portraitValue: string;
}>({
  drawerShow: false,
  restPass: false,
  portraitValue: "",
  admins_id: 0,
  oldForm: originalForm(),
  roleSelect: [],
  organiSelect: [],
  rolrSelectLoad: false,
  organiSelectLoad: false,
  color: document.documentElement.style.getPropertyValue("--el-color-primary") //全局皮肤颜色
});
/*  */

const ruleForm = reactive<RuleForm>(originalForm());

const stStore = StStore();

const passRule: Array<FormItemRule> = [
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
];
const rules = reactive<FormRules>({
  password: passRule,
  username: [
    { required: true, message: "用户名称不为空", trigger: "blur" },
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
  nick_name: [
    { required: true, message: "昵称不为空", trigger: "blur" },
    {
      max: 20,
      message: "不能超过20字符",
      trigger: "blur"
    }
  ],
  organi_id: [{ required: true, message: "所属部门不为空", trigger: "blur" }],
  real_name: [
    { required: true, message: "真实姓名不为空", trigger: "blur" },
    {
      max: 20,
      message: "不能超过20字符",
      trigger: "blur"
    }
  ],
  role_id: [{ required: true, message: "权限不为空", trigger: "blur" }],
  phone: [
    { required: true, message: "电话号码", trigger: "blur" },
    { required: true, validator: checkPhoneNumber, trigger: "blur" }
  ]
});

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return;
  await formEl.validate(async (valid, fields) => {
    if (valid) {
      if (data.admins_id !== 0) {
        let form = ruleForm;
        if (!data.restPass) delete form["password"];
        await edit({ ...form, admins_id: data.admins_id } as RuleForm<number, number>);
        ElMessage.success("编辑成功");
      } else {
        await add(ruleForm as RuleForm<number>);
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
  const original: RuleForm = data.admins_id === 0 ? originalForm() : data.oldForm;
  for (let key in original) if (ruleForm.hasOwnProperty(key)) ruleForm[key] = original[key];
};

const loadAll = async () => {
  data.rolrSelectLoad = true;
  let res = await roleAll();
  data.roleSelect = res.data.all;
  data.rolrSelectLoad = false;
};

const loadOrgani = async () => {
  data.organiSelectLoad = true;
  let res = await organiAll();
  data.organiSelect = res.data.tree;
  data.organiSelectLoad = false;
};

/**
 * 关闭弹窗清空表单
 */
const closed = () => {
  const original: RuleForm = originalForm();
  for (let key in original) if (ruleForm.hasOwnProperty(key)) ruleForm[key] = original[key];
  rules.password = passRule;
};

/*  */

/**
 * 对外暴露子组建方法
 */
const open = (params: RuleForm<number, number> | undefined = undefined) => {
  ruleFormRef.value?.clearValidate();
  if (params !== undefined) {
    data.admins_id = params.admins_id;
    let key: keyof RuleForm;
    for (key in params) {
      if (ruleForm.hasOwnProperty(key)) ruleForm[key] = params[key];
      if (data.oldForm.hasOwnProperty(key)) data.oldForm[key] = params[key];
    }
    data.restPass = false;
    delete rules.password;
  } else {
    data.admins_id = 0; //添加
    data.restPass = true;
  }
  // data.portraitValue = portraitValue;
  loadAll();
  loadOrgani();
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

const restPass = async (formEl: FormInstance | undefined) => {
  if (!formEl) return;
  data.restPass = !data.restPass;
  if (data.restPass) {
    rules.password = passRule;
    ruleForm.password = "";
  } else {
    delete rules.password;
  }
  formEl.clearValidate(["password"]);
};
</script>
<style scoped lang="scss">
@import "./index.scss";
.save-button {
  margin-top: 50px;
}
</style>
