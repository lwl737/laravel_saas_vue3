import { OperationLog } from "@/api/interface/operationLog";
import http from "@/api";
import { PORT1 } from "@/api/config/servicePort";
import { type ResPage } from "@/api/interface/index";

export const list = (params: OperationLog.ListReq) => {
  return http.get<ResPage<OperationLog.ListRes>>(PORT1 + `/operation_log/list`, params);
};
