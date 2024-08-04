import { type Ids , type ResPage} from "@/api/interface/index";
import { type Admin } from "@/api/interface/admin";
import { PORT1 } from "@/api/config/servicePort";
import http from "@/api";
import md5 from "md5";

/**
 * @name 用户管理模块
 */

export const add = (params: Admin.Add) => {
  return http.post(PORT1 + `/admin/add`, { ...params, password: md5(params.password as string) });
};

export const edit = (params: Admin.Edit) => {
  return http.put(
    PORT1 + `/admin/edit`,
    Object.assign(params, params.password ? { password: md5(params.password as string) } : {})
  );
};

export const editUserInfo = (params: Admin.EditUserInfo) => {
  return http.put(PORT1 + `/admin/edit_user_info`, params);
};
export const editPass = (params: Admin.EditPass) => {
  return http.put(PORT1 + `/admin/edit_pass`, params);
};

export const list = (params: Admin.PageReq) => {
  return http.get<ResPage<Admin.List>>(PORT1 + `/admin/list`, params, { cancel: false });
};
export const status = (params: Admin.Status) => {
  return http.put(PORT1 + `/admin/edit_status`, params);
};

export const del = (params: Ids) => {
  return http.delete(PORT1 + `/admin/del`, params);
};

export const organi = () => {
  return http.get<Admin.Organi.Res>(PORT1 + `/admin/organi`);
};
export const organiAll = () => {
  return http.get<Admin.OrganiAllRes>(PORT1 + `/admin/organi_all`);
};
export const roleAll = () => {
  return http.get<Admin.RoleAllRes>(PORT1 + `/admin/role_all`);
};
