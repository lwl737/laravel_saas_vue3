import { ReqPage, ResPage } from "./index";
import { type Ids } from "@/api/interface/index";
export namespace SaasSuperAdmin {
  export interface Form {
    username: string;
    password?: string;
    nick_name: string;
    real_name: string;
    phone: string;
    status: 1 | 0;
  }
  export interface EditUserInfo {
    username: string;
    nick_name: string;
    real_name: string;
    phone: string;
    portrait?: string;
  }

  export interface Add extends Form {
    portrait?: string;
    tenant_id: number;
  }
  export interface Edit extends Form {
    portrait?: string;
    admins_id: number;
    tenant_id: number;
  }
  export interface List extends Form {
    portrait: {
      full_url: string;
      value: string;
    };
    admins_id: number;
  }

  export interface PageRes<T> extends ResPage<T> {
    tenant_id: number | null;
  }

  export interface PageReq extends ReqPage {
    nick_name?: string;
    real_name?: string;
    tenant_id?: number;
  }
  export interface Status {
    admins_id: number;
    status: 1 | 0;
    tenant_id: number;
  }

  export interface DelReq extends Ids {
    tenant_id: number;
  }
}
