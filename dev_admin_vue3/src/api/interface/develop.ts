import { ReqPage } from "./index";
// * 开发管理模块
export namespace Develop {
  export namespace Settings {
    export interface SetItem {
      upload_slice_size: number;
      upload_max_size: number;
      library_max_capacity: number;
      upload_file_type: Array<FileTypes>;
      upload_check_file_type: 1 | 0;
      oss_start: 1 | 0;
      oss_access_key_id: string;
      oss_access_key_secret: string;
      oss_endpoint: string;
      oss_bucket: string;
      oss_role_arr: string;
      prefix_url: string;
    }
    export type GetItemReq = { field: Array<keyof SetItem> };
    export type GetItemRes = SetItem;
  }

  export namespace ErrorLog {
    export interface PageReq extends ReqPage {
      ymd: string;
    }
    export interface PageRes {
      line: number;
      file: string;
      error: string;
      params: { [key: string]: any };
      enum: null | {
        class: string;
        name: string;
        trueCodeName: string;
        code: string;
      };
      full_url: string;
      ip: string;
      method: string;
      create_time: string;
    }
  }
  export interface CheckDev {
    username: string;
    password: string;
  }
  export namespace OperationLog {
    export interface PageReq extends ReqPage {
      operation_time?: string;
      nickName?: string;
      realName?: string;
      log_id?: string;
      menu_title?: string;
      ip?: string;
      api?: string;
      router_path?: string;
    }
    export interface PageRes extends OrganiListRes {
      admins_id: number;
      api: string;
      auth_name: string;
      ip: string;
      log_id: number;
      menu_title: string;
      nickName: string;
      operation_time: string;
      realName: string;
      router_path: string;
      sql: Array<{ params: Array<string | number>; sql: string; time: number }>;
    }
  }

  export namespace ErrorDate {
    export interface Data {
      date: Array<string>;
    }
  }

  export namespace Menu {
    export interface Form extends Menus.ApiData {
      menu_pid: number;
      menu_sort: number;
    }

    export interface All extends Form {
      menu_id: number;
    }

    export interface Del {
      menu_id: number;
    }
    export namespace Api {
      export interface Form {
        api: string;
        menu_id: number;
        add_log: 1 | 0;
        api_name: string;
      }
      export interface Edit extends Form {
        api_id: number;
      }
      export interface List extends Form {
        api_id: number;
      }
      export interface AddLog {
        api_id: number;
        add_log: 0 | 1;
      }
    }
    export namespace Auth {
      export interface Form {
        auth_name: string;
        menu_id: number;
        auth_sort: number;
      }
      export interface List {
        auth_id: number;
        auth_name: string;
        menu_id: number;
        auth_sort: number;
        api: Array<Api.List>;
        buttons: Array<ButtonsList>;
        pages: Array<PagesList>;
      }

      export interface ButtonsList extends Buttons.Form {
        buttons_id: number;
      }
      export interface PagesList extends Pages.Form {
        menu_id: number;
        auth_id: number;
        menu_pid: number;
      }

      export interface PageReq extends ReqPage {
        menu_id: number;
      }
      export interface Del {
        auth_id: number;
      }

      export namespace Buttons {
        export interface Form {
          buttons: string;
          auth_id: number;
        }

        export interface Del {
          buttons_id: number;
        }
      }

      export namespace Api {
        export interface Form {
          auth_id: number;
          menu_id: number;
          add_log: 1 | 0;
        }
        export interface Add extends Form {
          api: string;
        }
        export interface Edit extends Form {
          api: string;
        }
        export interface AddLog {
          add_log: 1 | 0;
        }

        export interface List extends Form {
          api: {
            full_url: string;
            value: string;
          };
          api_id: number;
        }

        export interface Del {
          api_id: number;
        }
      }

      export namespace Pages {
        export interface EditReq extends Form {
          menu_id: number;
        }

        export interface AddReq extends Form {
          menu_pid: number;
          auth_id: number;
        }

        export interface Form {
          // auth_id: number;
          // menu_pid:number;
          path: string;
          component: string;
          name: string;
          title: string;
          icon: string;
          redirect: string;
          isLink: string;
          isFull: 1 | 0;
          isAffix: 1 | 0;
          isKeepAlive: 1 | 0;
        }

        export interface Del {
          menu_id: number;
        }
      }
    }
  }

  export namespace SuperAdmin {
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
      admins_id: number;
    }

    export interface PageReq extends ReqPage {
      nick_name?: string;
      real_name?: string;
    }
    export interface Status {
      admins_id: number;
      status: 1 | 0;
    }
  }
}
