import { defineStore } from "pinia";
import { AuthState } from "@/stores/interface";
import { getAuthButtonListApi } from "@/api/modules/login";
import { getFlatMenuList, getShowMenuList, getAllBreadcrumbList } from "@/utils";
import { commonRouter } from "@/routers/modules/staticRouter";
import { treeDataInit, copyObj } from "@/utils/util";
export const useAuthStore = defineStore({
  id: "geeker-auth",
  state: (): AuthState => ({
    // 按钮权限列表
    authButtonList: {},
    // 菜单权限列表
    authMenuList: [],
    // 当前页面的 router name，用来做按钮权限筛选
    routePath: ""
  }),
  getters: {
    // 按钮权限列表
    authButtonListGet: state => state.authButtonList,
    // 菜单权限列表 ==> 这里的菜单没有经过任何处理
    authMenuListGet: state => state.authMenuList,
    // 菜单权限列表 ==> 左侧菜单栏渲染，需要剔除 isHide == true
    showMenuListGet: state => getShowMenuList(state.authMenuList),
    // 菜单权限列表 ==> 扁平化之后的一维数组菜单，主要用来添加动态路由
    flatMenuListGet: state => getFlatMenuList(state.authMenuList),
    // 递归处理后的所有面包屑导航列表
    breadcrumbListGet: state => getAllBreadcrumbList(state.authMenuList),
    routePathGet: state => state.routePath
  },
  actions: {
    // Get AuthButtonList
    async getAuthButtonList() {
      // const { data } = await getAuthButtonListApi();
      return this.authButtonList;
    },
    // Get AuthMenuList
    async getAuthMenuList() {
      return this.authMenuList;
    },

    setAuthButtonList(buttons: Menus.Buttons) {
      this.authButtonList = buttons;
    },

    setAuthMenuList(menu: Menus.MenuOptions[], tenant_id: number) {
      this.authMenuList = treeDataInit([...copyObj(commonRouter), ...menu], item => {
        item.path = item.path === "/" ? `/${tenant_id}` : `/${tenant_id}${item.path}`;
        if (item.redirect) item.redirect = item.redirect === "/" ? `/${tenant_id}` : `/${tenant_id}${item.redirect}`;
        return item;
      });
    },
    setRoutePath(path: string) {
      // console.log(state.routePath.split("/"));

      let pathSplit = path.split("/");

      if (pathSplit.length >= 2 && /(^[1-9][0-9]+$)|(^[1-9]$)/.test(pathSplit[1])) {
        pathSplit.splice(1, 1);
        path = pathSplit.join("/");
      }

      this.routePath = path;
    },

    checkAuth(value: string[] | string) {
      const currentPageRoles = this.authButtonListGet[this.routePath] ?? [];
      if (value instanceof Array && value.length) return value.every(item => currentPageRoles.includes(item));
      else if (typeof value === "string") return currentPageRoles.includes(value);
    }
  }
});
