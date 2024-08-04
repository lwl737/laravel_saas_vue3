import { ReqPage } from "./index";
// * 文件上传模块
export namespace Upload {
  export interface ResFileUrl {
    url: string;
    full_url: string;
  }
  export namespace Dir {
    export interface Form {
      dir_name: string;
      dir_pid: string;
      dir_sort: number;
    }

    export interface All extends Form {
      dir_id: string;
      dir_link: string;
      children?: All[];
    }
  }

  export namespace File {
    export type FileLists = { file_name: string; file_type: string; size: number };

    export interface DelReq {
      real_del: 1 | 0;
      oids: string[];
    }
    export interface ListReq extends ReqPage {
      dir_link: string;
      keyWord?: string;
      file_type?: string;
    }
    export interface ListRes {
      create_time: number;
      file_name: string;
      file_type: string;
      file_size: number;
      full_url: string;
      schedule: number;
      _id: string;
    }

    export interface FileResPage<T> {
      datalist: T[];
      pageNum: number;
      pageSize: number;
      total: number;
      capacity: number;
      max_capacity: number;
    }

    export interface FileItem {
      create_time: number;
      file_name: string;
      file_size: number;
      full_url: string;
      schedule: number;
      _id: string;
    }

    export interface FirstReq {
      dir_id: string;
      dir_link: string;
      file_size: number;
      file_name: string;
      file_type: string;
    }

    export interface ScheduleReq {
      oid: string;
      schedule: number;
    }

    export interface FirstRes {
      file_url: string;
      oid: string;
    }

    export interface UploadConfig {
      upload_file_type: Array<FileTypes>;
      upload_max_size: number;
      upload_check_file_type: 1 | 0;
      upload_slice_size: number;
      // common_dir_prefix: string;
      // admins_dir_prefix: string;
    }

    export interface DirPrefix {
      common: string;
      admins: string;
    }

    export interface OssConfig {
      timeout: number;
      oss_start: 1 | 0;
      oss_bucket: string;
      accessKeyId: string;
      accessKeySecret: string;
      securityToken: string;
      oss_endpoint: string;
    }

    export interface GetConfig {
      upload: UploadConfig;
      dir_prefix: DirPrefix;
      admins_id: string;
      oss?: OssConfig;
    }
  }
}
