import { Login } from "@/api/interface/index";
import { Upload } from "@/api/interface/upload";

export type LayoutType = "vertical" | "classic" | "transverse" | "columns";

export type AssemblySizeType = "large" | "default" | "small";

export type LanguageType = "zh" | "en" | null;

/* GlobalState */
export interface GlobalState {
  layout: LayoutType;
  assemblySize: AssemblySizeType;
  language: LanguageType;
  maximize: boolean;
  primary: string;
  isDark: boolean;
  isGrey: boolean;
  isWeak: boolean;
  asideInverted: boolean;
  headerInverted: boolean;
  isCollapse: boolean;
  accordion: boolean;
  breadcrumb: boolean;
  breadcrumbIcon: boolean;
  tabs: boolean;
  tabsIcon: boolean;
  footer: boolean;
}

/* UserState */
export interface UserState {
  token: string;
  userInfo: Login.ResCheckLogin["info"] | undefined;
  organi_id: number;
  organi_name: string;
  list_config_organi: Login.ResCheckLogin["list_config_organi"];
}

/* tabsMenuProps */
export interface TabsMenuProps {
  icon: string;
  title: string;
  path: string;
  name: string;
  close: boolean;
  isKeepAlive: boolean;
}

/* TabsState */
export interface TabsState {
  tabsMenuList: TabsMenuProps[];
}

/* AuthState */
export interface AuthState {
  authButtonList: {
    [key: string]: string[];
  };
  routePath: string;
  authMenuList: Menu.MenuOptions[];
}

/* KeepAliveState */
export interface KeepAliveState {
  keepAliveName: string[];
}

export interface Oss {
  config?: {
    policy: string;
    host: string;
    OSSAccessKeyId: string;
    signature: string;
    expire: number;
  };
}

export interface StsState {
  oss: undefined | Upload.File.OssConfig;
  upload: undefined | Upload.File.UploadConfig;
  dir_prefix: undefined | Upload.File.DirPrefix;
  admins_id: undefined | string;
  timeOut: undefined | NodeJS.Timeout;
  capacity: undefined | number;
  max_capacity: undefined | number;
}
