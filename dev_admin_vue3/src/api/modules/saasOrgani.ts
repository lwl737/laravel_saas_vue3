import { Organi } from "@/api/interface/saasOrgani";
import { PORT1 } from "@/api/config/servicePort";
import http from "@/api";

/**
 * @name 部门管理
 */

export const option = (obj:Organi.OptionReq) => {
  return http.get<Organi.Option>(PORT1 + `/saas/organi/option`,obj,{loading:false});
};
