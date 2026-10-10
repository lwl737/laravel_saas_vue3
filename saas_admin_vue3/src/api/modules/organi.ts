import { Organi } from "@/api/interface/organi";
import { PORT1 } from "@/api/config/servicePort";
import http from "@/api";

/**
 * @name 部门管理
 */
// 用户登录
export const all = () => {
  return http.get<Organi.OrganiAllRes>(PORT1 + `/organi/all`);
};

export const add = (params: Organi.AddReq) => {
  return http.post(PORT1 + `/organi/add`, params);
};

export const edit = (params: Organi.EditReq) => {
  return http.put(PORT1 + `/organi/edit/${params.organi_id}`, params);
};
export const del = (params: Organi.DelReq) => {
  return http.delete(PORT1 + `/organi/del`, params);
};
export const option = () => {
  return http.get<Organi.Option>(PORT1 + `/organi/option`,{},{loading:false});
};

export const homeOption = () => {
  return http.get<Organi.HomeOption>(PORT1 + `/organi/home_option`,{},{loading:false});
};
