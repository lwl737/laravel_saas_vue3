import { RouteRecordRaw, RouteLocation } from "vue-router";
import { HOME_URL, LOGIN_URL, HOME_NAME, LOGIN_NAME } from "@/config";

/**
 * staticRouter (静态路由)
 */
export const staticRouter: RouteRecordRaw[] = [
  {
    path: "/:tenant_id([1-9][0-9]+|[1-9])",
    redirect: (to: RouteLocation) =>{
      // console.log(pathReplace(HOME_URL, to),"pathReplace(HOME_URL, to)");
      return   '/' + to.params.tenant_id + HOME_URL  ;
    } 
  },
  {
    path: "/:tenant_id" + LOGIN_URL,
    name: LOGIN_NAME,
    component: () => import("@/views/login/index.vue"),
    meta: {
      title: "登录"
    }
  },
  {
    path: "/layout",
    name: "layout",
    component: () => import("@/layouts/index.vue"),
    // redirect: (to: RouteLocation) => pathReplace(HOME_URL, to),
    children: []
  }
];

/**
 * devRouter (公共页面路由例如 后台首页 素材库那些)
 */
export const commonRouter: Menu.MenuOptions[] = [
  {
    path: HOME_URL,
    name: HOME_NAME,
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
    path: "/tenant404",
    name: "404",
    component: () => import("@/components/ErrorMessage/tenant404.vue"),
    meta: {
      title: "租户不存在"
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
