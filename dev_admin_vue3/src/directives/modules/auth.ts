/**
 * v-auth
 * 按钮权限指令
 */
import { useAuthStore } from "@/stores/modules/auth";
import type { Directive, DirectiveBinding } from "vue";

const auth: Directive = {
  mounted(el: HTMLElement, binding: DirectiveBinding) {
    const { value } = binding;
    const authStore = useAuthStore();
    const currentPageRoles = authStore.authButtonListGet[authStore.routePath] ?? [];

    let hasPermission = value instanceof Array && value.length ? value.every(item => currentPageRoles.includes(item)) : currentPageRoles.includes(value);
    
    !hasPermission && el.remove()

  }
};


function checkEleTag(el: HTMLElement): string {
  let classNameArr = el.className.split(' ');
  if (classNameArr.indexOf('el-switch') >= 0) return 'switch';
  else return 'default';
}

export default auth;
