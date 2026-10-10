import { defineStore } from "pinia";
import { UserState } from "@/stores/interface";
import piniaPersistConfig from "@/stores/helper/persist";
import { Login } from "@/api/interface/index";
export const useUserStore = defineStore({
  id: "geeker-user",
  state: (): UserState => ({
    token: "",
    userInfo: undefined,
    organi_id: -1,
    organi_name: "",
    list_config_organi: [],
    tenant_id: 0
  }),
  getters: {
    userInfoGet: state => state.userInfo,
    organiIdGet: state => state.organi_id,
    tokenGet: state => state.token,
    organiNameGet: state => state.organi_name,
    tenantIdGet: state => state.tenant_id,
    listConfigOrganiGet: state => state.list_config_organi
  },
  actions: {
    // Set Token
    setToken(token: string) {
      this.token = token;
    },
    setOrganiId(organi_id: number) {
      this.organi_id = organi_id;
    },
    setTenantId(tenant_id: number) {
      this.tenant_id = tenant_id;
    },
    loginOut() {
      this.token = "";
      this.userInfo = undefined;
    },
    // Set setUserInfo
    setUserInfo(userInfo: UserState["userInfo"] | undefined) {
      this.userInfo = userInfo;
    },
    setOrganiName(organi_name: string) {
      this.organi_name = organi_name;
    },
    setListConfigOrgani(list_config_organi: Login.ResCheckLogin["list_config_organi"]) {
      this.list_config_organi = list_config_organi;
    },
    tenantIdReplacePath(path: string, replace: boolean = false) {
      return { path: `/${this.tenant_id}${path}`, replace };
    }
  },
  persist: piniaPersistConfig("saas-user", ["token", "organi_id", "tenant_id"])
});
