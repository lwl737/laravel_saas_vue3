import { ColumnProps } from "@/components/ProTable/interface";
import { useUserStore } from "@/stores/modules/user";
// import { computed , ref  , onMounted} from "vue";
const userStore = useUserStore();
// const enums = ref( userStore.listConfigOrganiGet);
// onMounted(()=>{
//   enums.value = userStore.listConfigOrganiGet;
// });
export const useOrganiColumns = (): ColumnProps[] => {
  return [
    {
      prop: "insert_organi_id",
      label: "所属部门(选项)",
      isShow: false,
      fieldNames: { label: "organi_name", value: "organi_id" },
      enum: userStore.listConfigOrganiGet,
      search: {
        el: "tree-select",
        props: {
          multiple: true,
          defaultExpandAll: true,
          checkStrictly: true,
          renderAfterexpand: false,
          showCheckbox: true,
          checkOnClickNode: true
        }
      }
    },
    {
      prop: "insert_organi_name",
      label: "所属部门",
      width: 150,
      search: { el: "input" },
      render: scope => {
        return <pre style="width: 100%;overflow: auto;white-space: pre;">{scope.row.insert_organi_name}</pre>;
      }
    },

    {
      prop: "insert_nick_name",
      label: "添加人",
      width: 150,
      search: { el: "input" }
    }
  ];
}  
