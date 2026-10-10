import { ReqPage } from "./index";

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
