import { SaasOperationLog } from "@/api/interface/saasOperationLog";
import http from "@/api";
import { PORT1 } from "@/api/config/servicePort";
import { type ResPage } from "@/api/interface/index";

export const list = (params: SaasOperationLog.ListReq) => {
  return http.get<ResPage<SaasOperationLog.ListRes>>(PORT1 + `/saas/operation_log/list`, params);
};
