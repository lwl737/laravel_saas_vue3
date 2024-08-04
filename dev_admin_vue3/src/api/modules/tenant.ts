import { type Ids, type ResPage } from "@/api/interface/index";
import { Tenant } from "@/api/interface/tenant";
import { PORT1 } from "@/api/config/servicePort";
import http from "@/api";

/**
 * @name 租户模块
 */
export const list = (params: Tenant.ListReq) => {
  return http.get<ResPage<Tenant.List>>(PORT1 + `/saas/tenant/list`, params);
};

export const add = (params: Tenant.Form) => {
  return http.post(PORT1 + `/saas/tenant/add`, params);
};

export const edit = (params: Tenant.EditReq) => {
  return http.put(PORT1 + `/saas/tenant/edit`, params);
};
export const editStatus = (params: Tenant.EditStatusReq) => {
  return http.put(PORT1 + `/saas/tenant/edit_status`, params);
};
export const del = (params: Ids) => {
  return http.delete(PORT1 + `/saas/tenant/del`, params);
};
export const all = () => {
  return http.get<Tenant.All>(PORT1 + `/saas/tenant/all`);
};
