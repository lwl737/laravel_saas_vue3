<template>
  <el-dialog @closed="close" v-model="dialogVisible" title="选择部门" width="500px" draggable>
    <el-form ref="ruleFormRef" label-width="70px">
      <el-form-item label="部门">
        <el-tree-select
          v-model="organi_id"
          :data="tree"
          :loading="loading"
          node-key="organi_id"
          default-expand-all
          :props="{ label: 'organi_name', children: 'children' }"
          check-strictly
          :render-after-expand="false"
          show-checkbox
          check-on-click-node
        >
          <!-- <template #default="{ data: { organi_name } }">
            {{ organi_name }}
          </template> -->
        </el-tree-select>
      </el-form-item>
    </el-form>
    <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="() => confirm()">确认</el-button>
      </span>
    </template>
  </el-dialog>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
import type { FormInstance } from "element-plus";
import { organi } from "@/api/modules/admin";
import { Admin } from "@/api/interface/admin";
import { useUserStore } from "@/stores/modules/user";
const dialogVisible = ref(false);
// openDialog
const openDialog = () => {
  dialogVisible.value = true;
};

const userStore = useUserStore();

const ruleFormRef = ref<FormInstance>();

const loading = ref(false);

watch(
  () => dialogVisible.value,
  newVal => {
    if (newVal) {
      loading.value = true;
      organi()
        .then(res => {
          tree.value = res.data.tree;
        })
        .finally(() => {
          loading.value = false;
        });
      first.value = false;
    }
  }
);

const tree = ref<Admin.Organi.Res["tree"]>([]);
const first = ref(true);

const organi_id = ref(Number(userStore.organiIdGet));

const close = () => {
  organi_id.value = userStore.organiIdGet;
};

const confirm = () => {
  userStore.setOrganiId(organi_id.value);
  dialogVisible.value = false;
  location.reload();
};

defineExpose({ openDialog });
</script>
