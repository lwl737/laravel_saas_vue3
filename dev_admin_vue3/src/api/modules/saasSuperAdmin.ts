import { type SaasSuperAdmin } from "@/api/interface/saasSuperAdmin";
import { PORT1 } from "@/api/config/servicePort";
import http from "@/api";
import md5 from "md5";
export const superAdminAdd = (params: SaasSuperAdmin.Add) => {
  return http.post(PORT1 + `/saas/super_admin/add`, { ...params, password: md5(params.password as string) });
};

export const superAdminEdit = (params: SaasSuperAdmin.Edit) => {
  return http.put(
    PORT1 + `/saas/super_admin/edit`,
    Object.assign(params, params.password ? { password: md5(params.password as string) } : {})
  );
};
export const superAdminList = (params: SaasSuperAdmin.PageReq) => {
  return http.get<SaasSuperAdmin.PageRes<SaasSuperAdmin.List>>(PORT1 + `/saas/super_admin/list`, params, { cancel: false });
};
export const superAdminStatus = (params: SaasSuperAdmin.Status) => {
  return http.put(PORT1 + `/saas/super_admin/edit_status`, params);
};

export const superAdminDel = (params: SaasSuperAdmin.DelReq) => {
  return http.delete(PORT1 + `/saas/super_admin/del`, params);
};
