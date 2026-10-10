/* Menu */
declare namespace Menu {
  interface MenuOptions {
    path: string;
    name: string;
    component?: string | (() => Promise<unknown>);
    redirect?: string;
    meta: MetaProps;
    children?: MenuOptions[];
  }
  interface MetaProps {
    icon: string;
    title: string;
    activeMenu?: string;
    isLink?: string;
    isHide: boolean;
    isFull: boolean;
    isAffix: boolean;
    isKeepAlive: boolean;
    sort?: number;
  }
}
declare type FileTypes = {
  type: string;
  name: string;
  only: string;
};

declare interface OrganiListReq {
  insert_nick_name: string;
  insert_organi_id: number[];
  insert_organi_name: string;
}
declare interface OrganiListRes {
  insert_nick_name: string;
  insert_organi_name: string;
}
declare type InsertFnType = (url: string, alt: string, href: string) => void;
// * Menu
declare namespace Menus {
  interface Basic {
    path: string;
    name: string;
    title: string;
    icon: string;
    redirect: string;
    isLink: string;
    isHide: 1 | 0;
    isFull: 1 | 0;
    isAffix: 1 | 0;
    isKeepAlive: 1 | 0;
  }
  interface ApiData extends Basic {
    component: string;
  }
  interface Dev extends ApiData {
    children?: Array<Static>;
  }
  interface Result extends Basic {
    component?: string | (() => Promise<any>);
    children?: Array<Result>;
  }

  interface MenuOptions {
    path: string;
    name: string;
    component?: string | (() => Promise<any>);
    redirect?: string;
    meta: MetaProps;
    children?: MenuOptions[];
  }
  interface MetaProps {
    icon: string;
    title: string;
    activeMenu?: string;
    isLink?: string;
    isHide: boolean;
    isFull: boolean;
    isAffix: boolean;
    isKeepAlive: boolean;
  }
}
/* FileType */
declare namespace File {
  type ImageMimeType =
    | "image/apng"
    | "image/bmp"
    | "image/gif"
    | "image/jpeg"
    | "image/pjpeg"
    | "image/png"
    | "image/svg+xml"
    | "image/tiff"
    | "image/webp"
    | "image/x-icon";

  type ExcelMimeType = "application/vnd.ms-excel" | "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
}

/* Vite */
declare type Recordable<T = any> = Record<string, T>;

declare interface ViteEnv {
  VITE_USER_NODE_ENV: "development" | "production" | "test";
  VITE_GLOB_APP_TITLE: string;
  VITE_PORT: number;
  VITE_OPEN: boolean;
  VITE_REPORT: boolean;
  VITE_ROUTER_MODE: "hash" | "history";
  VITE_BUILD_COMPRESS: "gzip" | "brotli" | "gzip,brotli" | "none";
  VITE_BUILD_COMPRESS_DELETE_ORIGIN_FILE: boolean;
  VITE_DROP_CONSOLE: boolean;
  VITE_PWA: boolean;
  VITE_PUBLIC_PATH: string;
  VITE_API_URL: string;
  VITE_PROXY: [string, string][];
}

interface ImportMetaEnv extends ViteEnv {
  __: unknown;
}

/* __APP_INFO__ */
declare const __APP_INFO__: {
  pkg: {
    name: string;
    version: string;
    dependencies: Recordable<string>;
    devDependencies: Recordable<string>;
  };
  lastBuildTime: string;
};

declare namespace Menus {
  interface Basic {
    path: string;
    name: string;
    title: string;
    icon: string;
    redirect: string;
    isLink: string;
    isHide: 1 | 0;
    isFull: 1 | 0;
    isAffix: 1 | 0;
    isKeepAlive: 1 | 0;
  }
  interface ApiData extends Basic {
    component: string;
  }
  interface Dev extends ApiData {
    children?: Array<Static>;
  }
  interface Result extends Basic {
    component?: string | (() => Promise<any>);
    children?: Array<Result>;
  }

  type Buttons = { [key: stirng]: string[] };

  interface MenuOptions {
    path: string;
    name: string;
    component?: string | (() => Promise<any>);
    redirect?: string;
    meta: MetaProps;
    children?: MenuOptions[];
  }
  interface MetaProps {
    icon: string;
    title: string;
    activeMenu?: string;
    isLink?: string;
    isHide: boolean;
    isFull: boolean;
    isAffix: boolean;
    isKeepAlive: boolean;
  }
}
