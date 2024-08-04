import { ReqPage } from "./index";

export namespace OperationLog {
  export interface ListReq extends ReqPage {
    nickName?: string;
    realName?: string;
    log_id?: string;
    operation_time?: string;
    menu_title?: string;
    ip?: string;
    router_path?: string;
  }
  export interface ListRes {
    auth_name: string;
    ip: string;
    log_id: number;
    menu_title: string;
    nickName: string;
    operation_time: string;
    realName: string;
    router_path: string;
  }
}
