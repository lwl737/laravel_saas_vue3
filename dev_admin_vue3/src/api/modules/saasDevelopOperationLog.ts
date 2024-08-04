import { SaasDevelopOperationLog } from "@/api/interface/saasDevelopOperationLog";
import http from "@/api";
import { PORT1 } from "@/api/config/servicePort";
import { type ResPage } from "@/api/interface/index";

export const list = (params: SaasDevelopOperationLog.ListReq) => {
  return http.get<ResPage<SaasDevelopOperationLog.ListRes>>(PORT1 + `/saas/develop/operation_log/list`, params);
};
