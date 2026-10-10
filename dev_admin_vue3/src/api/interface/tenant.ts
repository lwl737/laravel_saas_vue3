import { ReqPage } from "./index";
/** 权限管理 */
export namespace Tenant {
  export interface ListReq extends ReqPage {
    tenant_name?: string;
  }

  export interface Form {
    tenant_name: string;
    status: 1 | 0;
    tenant_sort: number;
  }

  export interface EditReq extends Form {
    tenant_id: number;
  }

  export interface EditStatusReq {
    tenant_id: number;
    status: 1 | 0;
  }

  export interface List extends Form {
    creating: 1 | 0;
    tenant_id: number;
    created_time: string;
    tenant_url: string;
  }

  export interface All {
    all: Array<{
      tenant_id: number;
      tenant_name: string;
    }>;
  }
}
