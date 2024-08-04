import { ReqPage } from "./index";
/** 权限管理 */
export namespace Role {
  export interface TreeData {
    menu_id: number;
    title: string;
    menu_pid: number;
    auth: Array<{
      auth_id: number;
      auth_name: string;
      menu_id: number;
    }>;
    children: TreeData[];
  }
  export type PageReq = ReqPage;

  export interface Form {
    role_name: string;
    role_sort: number;
    role_describe: string;
    status: 1 | 0;
  }

  export interface RoleJson {
    role_json: { [key: string]: Array<number> };
  }

  export interface EditStatusReq {
    role_id: number;
    status: 1 | 0;
  }

  export interface List extends Form, RoleJson, OrganiListRes {
    role_id: number;
  }
}
