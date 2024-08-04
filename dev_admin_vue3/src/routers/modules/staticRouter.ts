import { RouteRecordRaw } from "vue-router";
import { HOME_URL, LOGIN_URL } from "@/config";

/**
 * staticRouter (静态路由)
 */
export const staticRouter: RouteRecordRaw[] = [
  {
    path: "/",
    redirect: HOME_URL
  },
  {
    path: LOGIN_URL,
    name: "login",
    component: () => import("@/views/login/index.vue"),
    meta: {
      title: "登录"
    }
  },
  {
    path: "/layout",
    name: "layout",
    component: () => import("@/layouts/index.vue"),
    // component: () => import("@/layouts/indexAsync.vue"),
    redirect: HOME_URL,
    children: []
  }
];

/**
 * devRouter (公共页面路由例如 后台首页 素材库那些)
 */
export const commonRouter: Menu.MenuOptions[] = [
  {
    path: "/home/index",
    name: "home",
    component: "/home/index",
    meta: {
      icon: "HomeFilled",
      title: "首页",
      isLink: "",
      isHide: false,
      isFull: false,
      isAffix: true,
      isKeepAlive: true
    }
  },
  {
    path: "/dashboard/index",
    name: "dashboard",
    component: "/dashboard/index",
    meta: {
      icon: "Odometer",
      title: "仪表盘",
      isLink: "",
      isHide: false,
      isFull: false,
      isAffix: false,
      isKeepAlive: true
    }
  },
  {
    path: "/material/index",
    name: "Material",
    component: "/material/index",
    meta: {
      icon: "HelpFilled",
      title: "素材库",
      isLink: "",
      isHide: false,
      isFull: false,
      isAffix: false,
      isKeepAlive: true
    }
  }
  // ...initCommonRouter<Menu.MenuOptions>("children", "meta.sort")
];

/**
 * errorRouter (错误页面路由)
 */
export const errorRouter = [
  {
    path: "/403",
    name: "403",
    component: () => import("@/components/ErrorMessage/403.vue"),
    meta: {
      title: "403页面"
    }
  },
  {
    path: "/404",
    name: "404",
    component: () => import("@/components/ErrorMessage/404.vue"),
    meta: {
      title: "404页面"
    }
  },
  {
    path: "/500",
    name: "500",
    component: () => import("@/components/ErrorMessage/500.vue"),
    meta: {
      title: "500页面"
    }
  },
  // Resolve refresh page, route warnings
  {
    path: "/:pathMatch(.*)*",
    component: () => import("@/components/ErrorMessage/404.vue")
  }
];

export const devRouter: Menu.MenuOptions[] = [
  {
    path: "/dev",
    name: "Dev",
    redirect: "/dev/super_admin/index",
    meta: {
      icon: "Setting",
      title: "开发管理",
      isLink: "",
      isHide: false,
      isFull: false,
      isAffix: false,
      isKeepAlive: true
    },
    children: [
      {
        path: "/dev/super_admin/index",
        name: "DevSuperAdminIndex",
        component: "/dev/superAdmin/index",
        meta: {
          icon: "Setting",
          title: "超级管理员",
          isLink: "",
          isHide: false,
          isFull: false,
          isAffix: false,
          isKeepAlive: true
        }
      },
      {
        path: "/dev/operation_log/index",
        name: "DevOperationLogIndex",
        component: "/dev/operationLog/index",
        meta: {
          icon: "Setting",
          title: "操作日志",
          isLink: "",
          isHide: false,
          isFull: false,
          isAffix: false,
          isKeepAlive: true
        }
      },
      {
        path: "/dev/error_log/index",
        name: "DevErrorLogIndex",
        component: "/dev/errorLog/index",
        meta: {
          icon: "Setting",
          title: "错误日志",
          isLink: "",
          isHide: false,
          isFull: false,
          isAffix: false,
          isKeepAlive: true
        }
      },
      {
        path: "/dev/menu/index",
        name: "DevMenuIndex",
        component: "/dev/menu/index",
        meta: {
          icon: "Setting",
          title: "栏目管理",
          isLink: "",
          isHide: false,
          isFull: false,
          isAffix: false,
          isKeepAlive: true
        }
      },
      {
        path: "/dev/settings/index",
        name: "DevSettingIndex",
        component: "/dev/settings/index",
        meta: {
          icon: "Setting",
          title: "设置",
          isLink: "",
          isHide: false,
          isFull: false,
          isAffix: false,
          isKeepAlive: true
        }
      }
    ]
  }
];
