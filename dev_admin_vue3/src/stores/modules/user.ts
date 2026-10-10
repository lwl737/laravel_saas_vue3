import { defineStore } from "pinia";
import { UserState } from "@/stores/interface";
import piniaPersistConfig from "@/stores/helper/persist";
import { Login } from "@/api/interface/index";
import { publicUrl } from "@/utils/util";
export const useUserStore = defineStore({
  id: "geeker-user",
  state: (): UserState => ({
    token: "",
    userInfo: undefined,
    defaull_portrait: publicUrl('/avatar.gif'), //默认头像
    organi_id: -1,
    organi_name: "",
    list_config_organi: []
  }),
  getters: {
    userInfoGet: state => state.userInfo,
    defaullPortraitGet: state => state.defaull_portrait,
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
      this.list_config_organi = [];
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
