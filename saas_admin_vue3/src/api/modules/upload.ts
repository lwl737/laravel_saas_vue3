import { Ids } from "@/api/interface/index";
import { Upload } from "@/api/interface/upload";
import { PORT1 } from "@/api/config/servicePort";
import http from "@/api";
/**
 * @name 文件上传模块
 */
// * 图片上传
export const uploadDirImg = (params: FormData) => {
  return http.post<Upload.ResFileUrl>(PORT1 + `/file/upload/image`, params);
};

export const dirAdd = (params: Upload.Dir.Form) => {
  return http.post(PORT1 + `/upload/dir/add`, params);
};
export const dirEdit = (params: Upload.Dir.Form, dir_id: string) => {
  return http.put(PORT1 + `/upload/dir/edit`, { ...params, dir_id });
};
export const dirAll = () => {
  return http.get<Upload.Dir.All[]>(PORT1 + `/upload/dir/all`, {}, { loading: false, cancel: false });
};
export const dirDel = (ids: Ids) => {
  return http.delete(PORT1 + `/upload/dir/del`, { ...ids });
};

export const fileFirst = (params: Upload.File.FirstReq) => {
  return http.post<Upload.File.FirstRes>(PORT1 + `/upload/file/first`, params, { loading: false });
};

export const schedule = (params: Upload.File.ScheduleReq) => {
  return http.put(PORT1 + `/upload/file/schedule`, params, { loading: false });
};

export const getConfig = (loading: boolean = false) => {
  return http.get<Upload.File.GetConfig>(PORT1 + `/upload/file/get_config`, {}, { loading });
};

export const fileList = (params: Upload.File.ListReq) => {
  return http.get<Upload.File.FileResPage<Upload.File.ListRes>>(PORT1 + `/upload/file/list`, params, {
    loading: false,
    cancel: false
  });
};

export const fileDel = (params: Upload.File.DelReq) => {
  return http.delete(PORT1 + `/upload/file/del`, params);
};
