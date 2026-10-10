import { ReqPage } from "./index";

export namespace SaasDevelopOperationLog {
  export interface ListReq extends ReqPage {
    nickName?: string;
    realName?: string;
    log_id?: string;
    operation_time?: string;
    menu_title?: string;
    ip?: string;
    insert_organi_name?: string;
    router_path?: string;
    tenant_id: number;
  }
  export interface ListRes {
    admins_id: number;
    api: string;
    auth_name: string;
    ip: string;
    log_id: number;
    menu_title: string;
    nickName: string;
    operation_time: string;
    realName: string;
    router_path: string;
    sql: Array<{ params: Array<string | number>; sql: string; time: number }>;
  }
}
