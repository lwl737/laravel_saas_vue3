import { type Ids } from "@/api/interface/index";
import { type Role } from "@/api/interface/role";
import { PORT1 } from "@/api/config/servicePort";
import http from "@/api";

/**
 * @name 自定义权限管理模块
 */
// * 树形数据
export const treeData = () => {
  return http.get<Array<Role.TreeData>>(PORT1 + `/role/tree_data`, {}, { headers: { noLoading: true } });
};

// * 列表
export const list = (params: Role.PageReq) => {
  return http.get<Role.List>(PORT1 + `/role/list`, params);
};
// * 编辑
export const edit = (params: Role.Form, role_id: number) => {
  return http.put<Array<any>>(PORT1 + `/role/edit`, { ...params, role_id });
};
// * 编辑
export const set = (params: Role.RoleJson, role_id: number) => {
  return http.put<Array<any>>(PORT1 + `/role/set`, { ...params, role_id });
};

// * 编辑
export const add = (params: Role.Form) => {
  return http.post(PORT1 + `/role/add`, params);
};
// * 删除
export const del = (params: Ids) => {
  return http.delete(PORT1 + `/role/del`, params);
};

export const editStatus = (params: Role.EditStatusReq) => {
  return http.put(PORT1 + `/role/edit_status`, params);
};
