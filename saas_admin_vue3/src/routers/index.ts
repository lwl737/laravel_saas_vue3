import { createRouter, createWebHashHistory, createWebHistory, type RouteLocationNormalized } from "vue-router";
import { useUserStore } from "@/stores/modules/user";
import { useAuthStore } from "@/stores/modules/auth";
import { LOGIN_URL, ROUTER_WHITE_LIST, LOGIN_NAME } from "@/config";
import { initDynamicRouter } from "@/routers/modules/dynamicRouter";
import { staticRouter, errorRouter } from "@/routers/modules/staticRouter";
import NProgress from "@/config/nprogress";
import { checkLoginApi } from "@/api/modules/login";
import { ElNotification } from "element-plus";
import { getTimeState } from "@/utils/util";
// import { pathReplace } from "@/utils/util";
import { HOME_URL } from "../config/index";
const mode = import.meta.env.VITE_ROUTER_MODE;

const routerMode = {
  hash: () => createWebHashHistory(),
  history: () => createWebHistory()
};

/**
 * @description 📚 路由参数配置简介
 * @param path ==> 路由菜单访问路径
 * @param name ==> 路由 name (对应页面组件 name, 可用作 KeepAlive 缓存标识 && 按钮权限筛选)
 * @param redirect ==> 路由重定向地址
 * @param component ==> 视图文件路径
 * @param meta ==> 路由菜单元信息
 * @param meta.icon ==> 菜单和面包屑对应的图标
 * @param meta.title ==> 路由标题 (用作 document.title || 菜单的名称)
 * @param meta.activeMenu ==> 当前路由为详情页时，需要高亮的菜单
 * @param meta.isLink ==> 路由外链时填写的访问地址
 * @param meta.isHide ==> 是否在菜单中隐藏 (通常列表详情页需要隐藏)
 * @param meta.isFull ==> 菜单是否全屏 (示例：数据大屏页面)
 * @param meta.isAffix ==> 菜单是否固定在标签页中 (首页通常是固定项)
 * @param meta.isKeepAlive ==> 当前路由是否缓存
 * */
const router = createRouter({
  history: routerMode[mode](),
  routes: [...staticRouter, ...errorRouter],
  strict: false,
  scrollBehavior: () => ({ left: 0, top: 0 })
});

/**
 * @description 路由拦截 beforeEach
 * */
router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore();
  const authStore = useAuthStore();

  // 1.NProgress 开始
  NProgress.start();

  // 2.判断访问页面是否在路由白名单地址(静态路由)中，如果存在直接放行
  if (ROUTER_WHITE_LIST.includes(to.path)) return next();

  if (to.path === "/" && userStore.tenantIdGet) {
    //去回上次租户
    return next(userStore.tenantIdReplacePath(HOME_URL, true));
  }

  let tenant_id = pathGetTenantId(to);

  let errorTenantId = !tenant_id || !/(^[1-9][0-9]+$)|(^[1-9]$)/.test(tenant_id);

  if (!errorTenantId) {
    //切换了租户的网站 就重新登录
    if (Number(tenant_id) !== userStore.tenantIdGet) userStore.loginOut();
    userStore.setTenantId(Number(tenant_id));
  }else{
     return next({ path: "/tenant404" });
  }
  // 3.动态设置标题
  const title = import.meta.env.VITE_GLOB_APP_TITLE;
  document.title = to.meta.title ? `${to.meta.title} - ${title}` : title;
  // 4.判断是访问登陆页，有 Token 就在当前页面，没有 Token 重置路由到登陆页
  if (to.name === LOGIN_NAME) {
    if (userStore.tokenGet) return next(userStore.tenantIdReplacePath(HOME_URL, true));
    resetRouter(); //重置路由
    return next();
    // 5.判断是否有 Token，没有重定向到 login 页面 有 token 就请求api获取菜单 按钮权限 用户信息
  } else if (!userStore.tokenGet) return next(userStore.tenantIdReplacePath(LOGIN_URL, true));
  else if (!authStore.authMenuListGet.length || !userStore.userInfo) {
    let res = await checkLoginApi(); //检查token是否正常 获取用户信息
    userStore.setUserInfo(res.data.info); //设置用户信息
    userStore.setOrganiName(res.data.organi_name); //设置部门信息
    authStore.setAuthButtonList(res.data.buttons); //设置用户信息
    authStore.setAuthMenuList(res.data.menu, userStore.tenantIdGet); //设置栏目权限
    userStore.setListConfigOrgani(res.data.list_config_organi); //设置列表可显示的部门条件
    initDynamicRouter();
    ElNotification({
      title: getTimeState(),
      message: res.data.info.nick_name + ",欢迎回来",
      type: "success",
      duration: 3000
    });
    return next({ ...to, replace: true });
  }

  // 6.存储 routerPath 做按钮权限筛选
  authStore.setRoutePath(to.path as string);

  // 7.正常访问页面
  next();
});

/**
 * @description 重置路由
 * */
export const resetRouter = () => {
  const authStore = useAuthStore();
  authStore.flatMenuListGet.forEach(route => {
    const { name } = route;
    if (name && router.hasRoute(name)) router.removeRoute(name);
  });
};

/**
 * @description 路由跳转错误
 * */
router.onError(error => {
  NProgress.done();
  console.warn("路由错误", error.message);
});

/**
 * @description 路由跳转结束
 * */
router.afterEach(() => {
  NProgress.done();
});

function pathGetTenantId(to: RouteLocationNormalized): string {
  let pathSplit = to.path.split("/");
  if (pathSplit.length > 2) {
    return pathSplit[1];
  } else return "";
}

export default router;
