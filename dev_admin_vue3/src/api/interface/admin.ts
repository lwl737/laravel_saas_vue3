import { number } from "echarts";
import { ReqPage } from "./index";
export namespace Admin {
  export interface Form {
    username: string;
    password?: string;
    nick_name: string;
    real_name: string;
    phone: string;
    organi_id: number[];
    status: 1 | 0;
    role_id: number;
  }
  export interface EditUserInfo {
    username: string;
    nick_name: string;
    real_name: string;
    phone: string;
    portrait?: string;
  }
  export interface EditPass {
    password: string;
    ori_password: string;
  }
  export namespace OperationLog {
    export interface PageReq extends ReqPage {
      nick_name?: string;
      real_name?: string;
      log_id?: string;
      opeartion_time?: string;
      menu_title?: string;
      ip?: string;
      router_path?: string;
    }
    export interface PageRes {
      auth_name: string;
      ip: string;
      log_id: number;
      menu_title: string;
      nick_name: string;
      operation_time: string;
      real_name: string;
      router_path: string;
    }
  }
  export interface Add extends Form {
    portrait?: string;
  }
  export interface Edit extends Form {
    portrait?: string;
    admins_id: number;
  }
  export interface List extends Form {
    portrait: {
      full_url: string;
      value: string;
    };
    role_name: string;
    role_id: number;
    // role_name: string;
    admins_id: number;
  }
  export interface PageReq extends ReqPage {
    role_name?: string;
    role_id?: number;
    nick_name?: string;
    real_name?: string;
  }
  export interface Status {
    admins_id: number;
    status: 1 | 0;
  }

  export namespace Organi {
    export interface Res {
      tree: { organi_name: string; organi_pid: number; organi_id: number; children?: Res["tree"] }[];
    }
  }
  export interface OrganiAllRes {
    tree: {
      organi_name: string;
      organi_pid: number;
      organi_id: number;
      children?: OrganiAllRes["tree"];
    }[];
  }
  export interface RoleAllRes {
    all: {
      role_id: number;
      role_name: string;
    }[];
  }
}
