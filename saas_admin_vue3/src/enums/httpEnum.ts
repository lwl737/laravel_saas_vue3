/**
 * @description：请求配置
 */
export enum ResultEnum {
  SUCCESS = 0,
  ERROR = 500,
  OVERDUE = -1,
  TIMEOUT = 30000,
  TYPE = "success"
}

/**
 * @description：请求方法
 */
export enum RequestEnum {
  GET = "GET",
  POST = "POST",
  PATCH = "PATCH",
  PUT = "PUT",
  DELETE = "DELETE"
}

export enum ResHeader {
  TOKEN = "saas-access-token"
}

export enum ReqHeader {
  TOKEN = "saas-access-token",
  ORIGIN_ID = "organi-id",
  TENANT_ID = "tenant-id",
  ROUTER_PATH = "saas-router-path"
}

/**
 * @description：常用的 contentTyp 类型
 */
export enum ContentTypeEnum {
  // json
  JSON = "application/json;charset=UTF-8",
  // text
  TEXT = "text/plain;charset=UTF-8",
  // form-data 一般配合qs
  FORM_URLENCODED = "application/x-www-form-urlencoded;charset=UTF-8",
  // form-data 上传
  FORM_DATA = "multipart/form-data;charset=UTF-8"
}
