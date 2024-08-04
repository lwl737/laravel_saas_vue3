import { defineStore } from "pinia";
import { StsState } from "@/stores/interface";
import { Upload } from "@/api/interface/upload";
import { getConfig } from "@/api/modules/upload";
// Upload.File.OssConfig

type SuccessFunc = ((oss_config: Upload.File.OssConfig | undefined) => void) | undefined;
// AuthStore
export const StStore = defineStore({
  id: "Sts",
  state: (): StsState => ({
    oss: undefined,
    upload: undefined,
    dir_prefix: undefined,
    timeOut: undefined,
    admins_id: undefined,
    capacity: undefined,
    max_capacity: undefined
  }),
  getters: {
    // 按钮权限列表
    uploadConfigGet: state => state.upload,
    ossConfigGet: state => state.oss,
    dirPrefixGet: state => state.dir_prefix,
    capacityGet: state => state.capacity,
    maxCapacityGet: state => state.max_capacity
  },
  actions: {
    setCapacity(capacity: number, max_capacity: number) {
      this.capacity = capacity;
      this.max_capacity = max_capacity;
    },

    async loadConfig(successFunc: SuccessFunc = undefined) {
      if (this.timeOut) clearTimeout(this.timeOut);
      await this.reqConfig(true, successFunc);
    },

    async reqConfig(loading: boolean = true, successFunc: SuccessFunc = undefined) {
      const res = await getConfig(loading);
      this.oss = res.data.oss;
      this.upload = res.data.upload;
      this.dir_prefix = res.data.dir_prefix;
      this.admins_id = res.data.admins_id;
      if (successFunc) successFunc(this.oss);
      if (res.data.oss?.timeout) this.resetTimeOut(successFunc);
      else clearTimeout(this.timeOut);
    },

    async resetTimeOut(successFunc: SuccessFunc = undefined) {
      if (!this.oss?.timeout) return;

      if (Math.floor(new Date().getTime() / 1000) >= this.oss.timeout) await this.reqConfig(false, successFunc);
      else {
        this.timeOut = setTimeout(() => {
          this.resetTimeOut(successFunc);
        }, 1000);
      }
    },

    getImgConfig() {
      // 读取配置里面的图片配置
      return this.uploadConfigGet?.upload_file_type[0].type ? this.uploadConfigGet?.upload_file_type[0].type : "";
    },

    getVideoConfig() {
      // 读取配置里面的视频配置
      return this.uploadConfigGet?.upload_file_type[1].type ? this.uploadConfigGet?.upload_file_type[1].type : "";
    }
  }
});
