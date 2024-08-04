import { type Develop } from "@/api/interface/develop";
import { PORT1 } from "@/api/config/servicePort";
import http from "@/api";
import { type ReqPage } from "@/api/interface/index";
import { ResultEnum } from "@/enums/httpEnum";
export const menuAdd = (params: Develop.Menu.Form) => {
  return http.post(PORT1 + `/saas/develop/saas_menu/add`, params);
};
// * 栏目列表
export const menuAll = () => {
  return http.get<Develop.Menu.All>(PORT1 + `/saas/develop/saas_menu/all`);
};
// * 栏目删除
export const menuDel = (params: Develop.Menu.Del) => {
  return http.delete(PORT1 + `/saas/develop/saas_menu/del`, params);
};
// * 栏目编辑
export const menuEdit = (params: Develop.Menu.Form, menu_id: number) => {
  return http.put(PORT1 + `/saas/develop/saas_menu/edit`, { ...params, menu_id });
};

// * 添加栏目权限
export const menuAuthAdd = (params: Develop.Menu.Auth.Form) => {
  return http.post(PORT1 + `/saas/develop/saas_menu_auth/add`, params);
};

// * 编辑栏目权限
export const menuAuthEdit = (params: Develop.Menu.Auth.Form, auth_id: number) => {
  return http.put(PORT1 + `/saas/develop/saas_menu_auth/edit`, { ...params, auth_id });
};

// * 栏目权限列表
export const menuAuthList = async (params: Develop.Menu.Auth.PageReq) => {
  if (params.menu_id !== 0) {
    return http.get<Develop.Menu.Auth.List>(PORT1 + `/saas/develop/saas_menu_auth/list`, params);
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
  return http.delete(PORT1 + `/saas/develop/saas_menu_auth/del`, params);
};

// * 栏目按钮权限
export const menuAuthButtonsAdd = (params: Develop.Menu.Auth.Buttons.Form) => {
  return http.post(PORT1 + `/saas/develop/saas_menu_auth_buttons/add`, params);
};

// * 栏目按钮权限
export const menuAuthButtonsDel = (params: Develop.Menu.Auth.Buttons.Del) => {
  return http.delete(PORT1 + `/saas/develop/saas_menu_auth_buttons/del`, params);
};

// * 栏目按钮权限
export const menuAuthButtonsEdit = (params: Develop.Menu.Auth.Buttons.Form, buttons_id: number) => {
  return http.put(PORT1 + `/saas/develop/saas_menu_auth_buttons/edit`, { ...params, buttons_id });
};

// * 栏目api权限
export const menuAuthApiAdd = (params: Develop.Menu.Auth.Api.Add) => {
  return http.post(PORT1 + `/saas/develop/saas_menu_auth_api/add`, params);
};

// * 栏目api权限
export const menuAuthApiDel = (params: Develop.Menu.Auth.Api.Del) => {
  return http.delete(PORT1 + `/saas/develop/saas_menu_auth_api/del`, params);
};

// * 栏目api权限
export const menuAuthApiEdit = (params: Develop.Menu.Auth.Api.Edit, api_id: number) => {
  return http.put(PORT1 + `/saas/develop/saas_menu_auth_api/edit`, { ...params, api_id });
};
// * 栏目api权限
export const menuAuthApiAddLog = (params: Develop.Menu.Auth.Api.AddLog, api_id: number) => {
  return http.put(PORT1 + `/saas/develop/saas_menu_auth_api/add_log`, { ...params, api_id });
};

// * 栏目api权限
export const menuAuthPagesAdd = (params: Develop.Menu.Auth.Pages.AddReq) => {
  return http.post(PORT1 + `/saas/develop/saas_menu_auth_pages/add`, params);
};

// * 栏目api权限
export const menuAuthPagesDel = (params: Develop.Menu.Auth.Pages.Del) => {
  return http.delete(PORT1 + `/saas/develop/saas_menu_auth_pages/del`, params);
};

// * 栏目api权限
export const menuApiAdd = (params: Develop.Menu.Api.Form) => {
  return http.post(PORT1 + `/saas/develop/saas_menu_api/add`, params);
};
export const menuApiEdit = (params: Develop.Menu.Api.Edit) => {
  return http.put(PORT1 + `/saas/develop/saas_menu_api/edit`, params);
};

export const menuApiAddLog = (params: Develop.Menu.Api.AddLog) => {
  return http.put(PORT1 + `/saas/develop/saas_menu_api/add_log`, params);
};

export const menuApiDel = (params: { api_id: number }) => {
  return http.delete(PORT1 + `/saas/develop/saas_menu_api/del`, params);
};

// * 栏目api权限
export const menuApiList = (params: ReqPage) => {
  return http.get<Develop.Menu.Api.List>(PORT1 + `/saas/develop/saas_menu_api/list`, params);
};

// * 栏目api权限
export const menuAuthPagesEdit = (params: Develop.Menu.Auth.Pages.EditReq) => {
  return http.put(PORT1 + `/saas/develop/saas_menu_auth_pages/edit`, params);
};
