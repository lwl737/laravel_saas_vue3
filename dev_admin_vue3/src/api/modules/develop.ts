import { type Develop } from "@/api/interface/develop";
import { PORT1 } from "@/api/config/servicePort";
import http from "@/api";
import { type ReqPage, type Ids,  type ResPage } from "@/api/interface/index";
import { ResultEnum } from "@/enums/httpEnum";
import md5 from "md5";

/**
 * @name 开发管理模块
 */
// * 错误日志
export const errorLogList = (params: Develop.ErrorLog.PageReq) => {
  return http.get<ResPage<Develop.ErrorLog.PageRes>>(PORT1 + `/develop/error_log/list`, params);
};
// * 操作日志
export const operationLogList = (params: Develop.OperationLog.PageReq) => {
  return http.get<ResPage<Develop.OperationLog.PageRes>>(PORT1 + `/develop/operation_log/list`, params);
};
// * 错误日志
export const errorLogDate = () => {
  return http.get<Develop.ErrorDate.Data>(PORT1 + `/develop/error_log/date`);
};

export const settingsGet = (params: Develop.Settings.GetItemReq) => {
  return http.get<Develop.Settings.GetItemRes>(PORT1 + `/develop/settings/get_item`, params);
};

export const settingSet = (params: Develop.Settings.SetItem) => {
  return http.put(PORT1 + `/develop/settings/set_item`, params);
};

export const clearCache = () => {
  return http.get(PORT1 + `/develop/settings/clear_cache`);
};

export const menuAdd = (params: Develop.Menu.Form) => {
  return http.post(PORT1 + `/develop/menu/add`, params);
};
// * 栏目列表
export const menuAll = () => {
  return http.get<Develop.Menu.All>(PORT1 + `/develop/menu/all`);
};
// * 栏目删除
export const menuDel = (params: Develop.Menu.Del) => {
  return http.delete(PORT1 + `/develop/menu/del`, params);
};
// * 栏目编辑
export const menuEdit = (params: Develop.Menu.Form, menu_id: number) => {
  return http.put(PORT1 + `/develop/menu/edit`, { ...params, menu_id });
};

// * 添加栏目权限
export const menuAuthAdd = (params: Develop.Menu.Auth.Form) => {
  return http.post(PORT1 + `/develop/menu_auth/add`, params);
};

// * 编辑栏目权限
export const menuAuthEdit = (params: Develop.Menu.Auth.Form, auth_id: number) => {
  return http.put(PORT1 + `/develop/menu_auth/edit`, { ...params, auth_id });
};

// * 栏目权限列表
export const menuAuthList = async (params: Develop.Menu.Auth.PageReq) => {
  if (params.menu_id !== 0) {
    return http.get<ResPage<Develop.Menu.Auth.List>>(PORT1 + `/develop/menu_auth/list`, params);
  } else {
    return {
      code: ResultEnum.SUCCESS,
      msg: "查询成功",
      data: {
        datalist: [],
        pageNum: 1,
        pageSize: 10,
        total: 0
      }
    };
  }
};

// * 栏目权限删除
export const menuAuthDel = (params: Develop.Menu.Auth.Del) => {
  return http.delete(PORT1 + `/develop/menu_auth/del`, params);
};

// * 栏目按钮权限
export const menuAuthButtonsAdd = (params: Develop.Menu.Auth.Buttons.Form) => {
  return http.post(PORT1 + `/develop/menu_auth_buttons/add`, params);
};

// * 栏目按钮权限
export const menuAuthButtonsDel = (params: Develop.Menu.Auth.Buttons.Del) => {
  return http.delete(PORT1 + `/develop/menu_auth_buttons/del`, params);
};

// * 栏目按钮权限
export const menuAuthButtonsEdit = (params: Develop.Menu.Auth.Buttons.Form, buttons_id: number) => {
  return http.put(PORT1 + `/develop/menu_auth_buttons/edit`, { ...params, buttons_id });
};

// * 栏目api权限
export const menuAuthApiAdd = (params: Develop.Menu.Auth.Api.Add) => {
  return http.post(PORT1 + `/develop/menu_auth_api/add`, params);
};

// * 栏目api权限
export const menuAuthApiDel = (params: Develop.Menu.Auth.Api.Del) => {
  return http.delete(PORT1 + `/develop/menu_auth_api/del`, params);
};

// * 栏目api权限
export const menuAuthApiEdit = (params: Develop.Menu.Auth.Api.Edit, api_id: number) => {
  return http.put(PORT1 + `/develop/menu_auth_api/edit`, { ...params, api_id });
};
// * 栏目api权限
export const menuAuthApiAddLog = (params: Develop.Menu.Auth.Api.AddLog, api_id: number) => {
  return http.put(PORT1 + `/develop/menu_auth_api/add_log`, { ...params, api_id });
};

// * 栏目api权限
export const menuAuthPagesAdd = (params: Develop.Menu.Auth.Pages.AddReq) => {
  return http.post(PORT1 + `/develop/menu_auth_pages/add`, params);
};

// * 栏目api权限
export const menuAuthPagesDel = (params: Develop.Menu.Auth.Pages.Del) => {
  return http.delete(PORT1 + `/develop/menu_auth_pages/del`, params);
};

// * 栏目api权限
export const menuApiAdd = (params: Develop.Menu.Api.Form) => {
  return http.post(PORT1 + `/develop/menu_api/add`, params);
};
export const menuApiEdit = (params: Develop.Menu.Api.Edit) => {
  return http.put(PORT1 + `/develop/menu_api/edit`, params);
};

export const menuApiAddLog = (params: Develop.Menu.Api.AddLog) => {
  return http.put(PORT1 + `/develop/menu_api/add_log`, params);
};

export const menuApiDel = (params: { api_id: number }) => {
  return http.delete(PORT1 + `/develop/menu_api/del`, params);
};

// * 栏目api权限
export const menuApiList = (params: ReqPage) => {
  return http.get<ResPage<Develop.Menu.Api.List>>(PORT1 + `/develop/menu_api/list`, params);
};

// * 栏目api权限
export const menuAuthPagesEdit = (params: Develop.Menu.Auth.Pages.EditReq) => {
  return http.put(PORT1 + `/develop/menu_auth_pages/edit`, params);
};

export const superAdminAdd = (params: Develop.SuperAdmin.Add) => {
  return http.post(PORT1 + `/develop/super_admin/add`, { ...params, password: md5(params.password as string) });
};

export const superAdminEdit = (params: Develop.SuperAdmin.Edit) => {
  return http.put(
    PORT1 + `/develop/super_admin/edit`,
    Object.assign(params, params.password ? { password: md5(params.password as string) } : {})
  );
};
export const superAdminList = (params: Develop.SuperAdmin.PageReq) => {
  return http.get<ResPage<Develop.SuperAdmin.List>>(PORT1 + `/develop/super_admin/list`, params, { cancel: false });
};
export const superAdminStatus = (params: Develop.SuperAdmin.Status) => {
  return http.put(PORT1 + `/develop/super_admin/edit_status`, params);
};

export const superAdminDel = (params: Ids) => {
  return http.delete(PORT1 + `/develop/super_admin/del`, params);
};
