
import {OperationLog} from "@/api/interface/operationLog";
import http from "@/api";
import { PORT1 } from "@/api/config/servicePort";

export const list = (params: OperationLog.ListReq) => {
	return http.get<OperationLog.ListRes>(PORT1 + `/operation_log/list`, params);
};
