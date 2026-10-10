import { Login } from "@/api/interface/index";
import { PORT1 } from "@/api/config/servicePort";
import authMenuList from "@/assets/json/authMenuList.json";
import authButtonList from "@/assets/json/authButtonList.json";
import http from "@/api";

/**
 * @name 登录模块
 */
// 用户登录
export const loginApi = (params: Login.ReqLoginForm) => {
  return http.get<Login.ResLogin>(PORT1 + `/admin/login`, params, { loading: false }); // 正常 post json 请求  ==>  application/json
};

export const checkLoginApi = () => {
  return http.get<Login.ResCheckLogin>(PORT1 + `/admin/check_login`, {}, { loading: false }); // 正常 post json 请求  ==>  application/json
};
