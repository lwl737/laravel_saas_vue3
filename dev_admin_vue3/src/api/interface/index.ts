// 请求响应参数（不包含data）
export interface Result {
  errCode: number;
  msg: string;
}

// 请求响应参数（包含data）
export interface ResultData<T = any> extends Result {
  data: T;
}

// 分页响应参数
export interface ResPage<T> {
  list: T[];
  pageNum: number;
  pageSize: number;
  total: number;
}

// 分页请求参数
export interface ReqPage {
  pageNum: number;
  pageSize: number;
}

// 文件上传模块
export namespace Upload {
  export interface ResFileUrl {
    fileUrl: string;
  }
}

// 登录模块
export namespace Login {
  export interface ReqLoginForm {
    username: string;
    password: string;
  }
  export interface ResLogin {
    organi_id: number;
  }
  export interface ResCheckLogin {
    info: {
      nick_name: string;
      portrait: { full_url: string; value: string };
      username: string;
      real_name: string;
      phone: string;
    };
    list_config_organi: {
      organi_name: string;
      organi_pid: number;
      organi_id: number;
      children?: ResCheckLogin["list_config_organi"];
    }[];
    organi_name: string;
    role_id: number;
    buttons: Menus.Buttons;
    menu: Menus.MenuOptions[];
  }
  export interface ResAuthButtons {
    [key: string]: string[];
  }
}

export interface Ids {
  ids: Array<number>;
}
// 用户管理模块
export namespace User {
  export interface ReqUserParams extends ReqPage {
    username: string;
    gender: number;
    idCard: string;
    email: string;
    address: string;
    createTime: string[];
    status: number;
  }
  export interface ResUserList {
    id: string;
    username: string;
    gender: number;
    user: { detail: { age: number } };
    idCard: string;
    email: string;
    address: string;
    createTime: string;
    status: number;
    avatar: string;
    photo: any[];
    children?: ResUserList[];
  }
  export interface ResStatus {
    userLabel: string;
    userValue: number;
  }
  export interface ResGender {
    genderLabel: string;
    genderValue: number;
  }
  export interface ResDepartment {
    id: string;
    name: string;
    children?: ResDepartment[];
  }
  export interface ResRole {
    id: string;
    name: string;
    children?: ResDepartment[];
  }
}
