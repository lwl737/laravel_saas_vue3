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
    list_config_organi: []
  }),
  getters: {
    userInfoGet: state => state.userInfo,
    organiIdGet: state => state.organi_id,
    tokenGet: state => state.token,
    organiNameGet: state => state.organi_name,
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
    }
  },
  persist: piniaPersistConfig("dev-user", ["token", "organi_id"])
});
