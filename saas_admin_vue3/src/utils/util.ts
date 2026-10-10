import { isArray } from "./is";
import qs from "qs";
import { RouteLocation } from "vue-router";

/**
 * @description 获取localStorage
 * @param {String} key Storage名称
 * @return string
 */
export function localGet(key: string) {
  const value = window.localStorage.getItem(key);
  try {
    return JSON.parse(window.localStorage.getItem(key) as string);
  } catch (error) {
    return value;
  }
}
export function fileUnit(size: number) {
  size = Number(size);
  if (size >= 1024 * 1024 * 1024) return Number((size / 1024 / 1024 / 1024).toFixed(2)) + "GB";
  else if (size >= 1024 * 1024) return Number((size / 1024 / 1024).toFixed(2)) + "MB";
  else return Math.floor(size / 1024) + "KB";
}
/**
 * @description 存储localStorage
 * @param {String} key Storage名称
 * @param {Any} value Storage值
 * @return void
 */
export function localSet(key: string, value: any) {
  window.localStorage.setItem(key, JSON.stringify(value));
}

/**
 * @description 清除localStorage
 * @param {String} key Storage名称
 * @return void
 */
export function localRemove(key: string) {
  window.localStorage.removeItem(key);
}

/**
 * @description 清除所有localStorage
 * @return void
 */
export function localClear() {
  window.localStorage.clear();
}

/**
 * @description 判断数据类型
 * @param {Any} val 需要判断类型的数据
 * @return string
 */
export function isType(val: any) {
  if (val === null) return "null";
  if (typeof val !== "object") return typeof val;
  else return Object.prototype.toString.call(val).slice(8, -1).toLocaleLowerCase();
}

/**
 * @description 生成唯一 uuid
 * @return string
 */
export function generateUUID() {
  if (typeof crypto === "object") {
    if (typeof crypto.randomUUID === "function") {
      return crypto.randomUUID();
    }
    if (typeof crypto.getRandomValues === "function" && typeof Uint8Array === "function") {
      const callback = (c: any) => {
        const num = Number(c);
        return (num ^ (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (num / 4)))).toString(16);
      };
      return "10000000-1000-4000-8000-100000000000".replace(/[018]/g, callback);
    }
  }
  let timestamp = new Date().getTime();
  let performanceNow = (typeof performance !== "undefined" && performance.now && performance.now() * 1000) || 0;
  return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, c => {
    let random = Math.random() * 16;
    if (timestamp > 0) {
      random = (timestamp + random) % 16 | 0;
      timestamp = Math.floor(timestamp / 16);
    } else {
      random = (performanceNow + random) % 16 | 0;
      performanceNow = Math.floor(performanceNow / 16);
    }
    return (c === "x" ? random : (random & 0x3) | 0x8).toString(16);
  });
}

/**
 * 判断两个对象是否相同
 * @param a 要比较的对象一
 * @param b 要比较的对象二
 * @returns 相同返回 true，反之则反
 */
export function isObjectValueEqual(a: { [key: string]: any }, b: { [key: string]: any }) {
  if (!a || !b) return false;
  let aProps = Object.getOwnPropertyNames(a);
  let bProps = Object.getOwnPropertyNames(b);
  if (aProps.length != bProps.length) return false;
  for (let i = 0; i < aProps.length; i++) {
    let propName = aProps[i];
    let propA = a[propName];
    let propB = b[propName];
    if (!b.hasOwnProperty(propName)) return false;
    if (propA instanceof Object) {
      if (!isObjectValueEqual(propA, propB)) return false;
    } else if (propA !== propB) {
      return false;
    }
  }
  return true;
}

/**
 * @description 生成随机数
 * @param {Number} min 最小值
 * @param {Number} max 最大值
 * @return number
 */
export function randomNum(min: number, max: number): number {
  let num = Math.floor(Math.random() * (min - max) + max);
  return num;
}

/**
 * @description 获取当前时间对应的提示语
 * @return string
 */
export function getTimeState() {
  // 获取当前时间
  let timeNow = new Date();
  // 获取当前小时
  let hours = timeNow.getHours();
  // 判断当前时间段
  if (hours >= 6 && hours <= 10) return `早上好 ⛅`;
  if (hours >= 10 && hours <= 14) return `中午好 🌞`;
  if (hours >= 14 && hours <= 18) return `下午好 🌞`;
  if (hours >= 18 && hours <= 24) return `晚上好 🌛`;
  if (hours >= 0 && hours <= 6) return `凌晨好 🌛`;
}

/**
 * @description 获取当前时间YYYMMDD
 * @return string
 */
export function getDateTime(date?: string): string {
  // 获取当前时间
  let y: string, m: string, d: string;
  if (date) {
    y = new Date(date).getFullYear() + "-";
    m = new Date(date).getMonth() + 1 < 10 ? "0" + (new Date(date).getMonth() + 1) + "-" : new Date(date).getMonth() + 1 + "-";
    d = (new Date(date).getDate() < 10 ? "0" + new Date(date).getDate() : new Date(date).getDate()).toString();
  } else {
    y = new Date().getFullYear() + "-";
    m = new Date().getMonth() + 1 < 10 ? "0" + (new Date().getMonth() + 1) + "-" : new Date().getMonth() + 1 + "-";
    d = (new Date().getDate() < 10 ? "0" + new Date().getDate() : new Date().getDate()).toString();
  }

  return y + m + d;
}

/**
 * @description 获取浏览器默认语言
 * @return string
 */
export function getBrowserLang() {
  let browserLang = navigator.language ? navigator.language : navigator.browserLanguage;
  let defaultBrowserLang = "";
  if (browserLang.toLowerCase() === "cn" || browserLang.toLowerCase() === "zh" || browserLang.toLowerCase() === "zh-cn") {
    defaultBrowserLang = "zh";
  } else {
    defaultBrowserLang = "en";
  }
  return defaultBrowserLang;
}

/**
 * @description 递归查询当前路由所对应的路由
 * @param {Array}  menuList				所有菜单列表
 * @param {String} path     			当前访问地址
 * @return array
 */
export function filterCurrentRoute(menuList: Menu.MenuOptions[], path: string) {
  let result = {};
  for (let item of menuList) {
    if (item.path === path) return item;
    if (item.children) {
      const res = filterCurrentRoute(item.children, path);
      if (Object.keys(res).length) result = res;
    }
  }
  return result;
}

/**
 * @description 扁平化数组对象(主要用来处理路由菜单)
 * @param {Array} menuList 所有菜单列表
 * @return array
 */
export function getFlatArr(menuList: Menu.MenuOptions[]) {
  let newMenuList: Menu.MenuOptions[] = JSON.parse(JSON.stringify(menuList));
  return newMenuList.reduce((pre: Menu.MenuOptions[], current: Menu.MenuOptions) => {
    let flatArr = [...pre, current];
    if (current.children) flatArr = [...flatArr, ...getFlatArr(current.children)];
    return flatArr;
  }, []);
}

/**
 * @description 使用递归，过滤需要缓存的路由（暂时没有使用）
 * @param {Array} menuList 所有菜单列表
 * @param {Array} cacheArr 缓存的路由菜单 name ['**','**']
 * @return array
 * */
export function getKeepAliveRouterName(menuList: Menu.MenuOptions[], keepAliveArr: string[] = []) {
  menuList.forEach(item => {
    item.meta.isKeepAlive && item.name && keepAliveArr.push(item.name);
    item.children?.length && getKeepAliveRouterName(item.children, keepAliveArr);
  });
  return keepAliveArr;
}

/**
 * @description 使用递归，过滤出需要渲染在左侧菜单的列表（剔除 isHide == true 的菜单）
 * @param {Array} menuList 所有菜单列表
 * @return array
 * */
export function getShowMenuList(menuList: Menu.MenuOptions[]) {
  let newMenuList: Menu.MenuOptions[] = JSON.parse(JSON.stringify(menuList));
  return newMenuList.filter(item => {
    item.children?.length && (item.children = getShowMenuList(item.children));
    return !item.meta?.isHide;
  });
}

/**
 * @description 使用递归处理路由菜单 path，生成一维数组(第一版本地路由鉴权会用到)
 * @param {Array} menuList 所有菜单列表
 * @param {Array} menuPathArr 菜单地址的一维数组 ['**','**']
 * @return array
 */
export function getMenuListPath(menuList: Menu.MenuOptions[], menuPathArr: string[] = []) {
  menuList.forEach((item: Menu.MenuOptions) => {
    typeof item === "object" && item.path && menuPathArr.push(item.path);
    item.children?.length && getMenuListPath(item.children, menuPathArr);
  });
  return menuPathArr;
}

/**
 * @description 递归找出所有面包屑存储到 pinia/vuex 中
 * @param {Array} menuList 所有菜单列表
 * @param {Object} result 输出的结果
 * @param {Array} parent 父级菜单
 * @returns object
 */
export const getAllBreadcrumbList = (menuList: Menu.MenuOptions[], result: { [key: string]: any } = {}, parent = []) => {
  for (const item of menuList) {
    result[item.path] = [...parent, item];
    if (item.children) getAllBreadcrumbList(item.children, result, result[item.path]);
  }
  return result;
};

/**
 * @description 格式化表格单元格默认值(el-table-column)
 * @param {Number} row 行
 * @param {Number} col 列
 * @param {String} callValue 当前单元格值
 * @return string
 * */
export function defaultFormat(row: number, col: number, callValue: any) {
  // 如果当前值为数组,使用 / 拼接（根据需求自定义）
  if (isArray(callValue)) return callValue.length ? callValue.join(" / ") : "--";
  return callValue ?? "--";
}

/**
 * @description 处理无数据情况
 * @param {String} callValue 需要处理的值
 * @return string
 * */
export function formatValue(callValue: any) {
  // 如果当前值为数组,使用 / 拼接（根据需求自定义）
  if (isArray(callValue)) return callValue.length ? callValue.join(" / ") : "--";
  return callValue ?? "--";
}

/**
 * @description 处理 prop 为多级嵌套的情况(列如: prop:user.name)
 * @param {Object} row 当前行数据
 * @param {String} prop 当前 prop
 * @return any
 * */
export function handleRowAccordingToProp(row: { [key: string]: any }, prop: string) {
  if (!prop.includes(".")) return row[prop] ?? "--";
  prop.split(".").forEach(item => (row = row[item] ?? "--"));
  return row;
}

/**
 * @description 处理 prop，当 prop 为多级嵌套时 ==> 返回最后一级 prop
 * @param {String} prop 当前 prop
 * @return string
 * */
export function handleProp(prop: string) {
  const propArr = prop.split(".");
  if (propArr.length == 1) return prop;
  return propArr[propArr.length - 1];
}

/**
 * @description 根据枚举列表查询当需要的数据（如果指定了 label 和 value 的 key值，会自动识别格式化）
 * @param {String} callValue 当前单元格值
 * @param {Array} enumData 字典列表
 * @param {Array} fieldNames 指定 label && value 的 key 值
 * @param {String} type 过滤类型（目前只有 tag）
 * @return string
 * */
export function filterEnum(
  callValue: any,
  enumData: any[] | undefined,
  fieldNames?: { label: string; value: string },
  type?: string
): string {
  const value = fieldNames?.value ?? "value";
  const label = fieldNames?.label ?? "label";
  let filterData: { [key: string]: any } = {};
  if (Array.isArray(enumData)) filterData = enumData.find((item: any) => item[value] === callValue);
  if (type == "tag") return filterData?.tagType ? filterData.tagType : "";
  return filterData ? filterData[label] : "--";
}

/**
 * @description: 深拷贝
 * @param {any} obj
 * @return {*}
 */
export function copyObj(obj: any = {}) {
  //变量先置空
  let newobj: any = null;
  //判断是否需要继续进行递归
  if (typeof obj == "object" && obj !== null) {
    newobj = obj instanceof Array ? [] : {}; //进行下一层递归克隆
    let i: any;
    for (i in obj) {
      newobj[i] = copyObj(obj[i]);
    } //如果不是对象直接赋值
  } else newobj = obj;
  return newobj;
}

/**
 * file 转Base64 DataURL
 * @param {File} file
 * @returns
 */
export function fileToBase64Async(file: File) {
  return new Promise<string>((resolve, reject) => {
    let reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = (e: any) => {
      resolve(e.target.result);
    };
    reader.onerror = (msg: any) => {
      console.error(msg);
      reject(`文件转换成base64失败`);
    };
  });
}

export function publicUrl(str: string): string {
  return str;
}

export function createForm(form: any, func: (key: string, item: any) => any = (key: string, item: any) => item): FormData {
  let key: keyof any;
  let formData = new FormData();
  for (key in form) if (typeof form[key] !== `undefined`) formData.append(key, func(key, form[key]) as string | File);
  return formData;
}

export function ksort(obj: { [key: string]: any }) {
  let sortObj: { [key: string]: any } = {};
  let keys: string[] = Object.keys(obj);
  keys.sort();
  keys.forEach((key: string) => {
    sortObj[key] = obj[key];
  });
  return sortObj;
}

export function createSign(form: { [key: string]: any }, encryption: (str: string) => string): string {
  form = ksort(form);
  return encryption(qs.stringify(form));
}

export function createTimes(divisor: number = 1000): number {
  return Math.floor(new Date().getTime() / divisor);
}

/**
 * @description:  数组切片
 * @param {string | number} stringTime 事件
 * @param {"ymd" | "ymdhis"} type 年月日 还是 年月日时分秒
 *  @param {string} separator 切割符
 * @return {*}
 */
export function dateChange(stringTime: string | number, type: "ymd" | "ymdhis" = "ymd", separator: string = "-"): string {
  let date: Date = new Date(stringTime);

  let y = date.getFullYear();
  let m: number | string = date.getMonth() + 1;
  m = m < 10 ? "0" + m : m;
  let d: number | string = date.getDate();
  d = d < 10 ? "0" + d : d;

  let dateTime: string = y + separator + m + separator + d;

  if (type === "ymdhis") {
    let h: number | string = date.getHours();
    h = h < 10 ? "0" + h : h;
    let M: number | string = date.getMinutes();
    M = M < 10 ? "0" + M : M;
    let s: number | string = date.getSeconds();
    s = s < 10 ? "0" + s : s;
    dateTime += " " + h + ":" + M + ":" + s;
  }

  return dateTime;
}

export function originalPortrait(): string {
  let prefix = import.meta.env.VITE_API_PROXY_URL ? import.meta.env.VITE_API_PROXY_URL : import.meta.env.VITE_API_URL;
  return (prefix ? prefix : "") + "/web/images/avatar.jpg";
}

export function getAssetsFile(fileName: string) {
  return new URL(`../assets/images/${fileName}`, import.meta.url).href;
}

export function pathReplace(path: string, to: RouteLocation, replace: boolean = false) {
  path = path + "/";
  for (let key in to.params) {
    path = path.replace(new RegExp("/:" + key + "/", "g"), `/${to.params[key]}/`);
  }
  return { path: path.substring(0, path.length - 1), replace };
}

export function treeDataInit<T = any, J extends T = T>(oriData: J[], func: (item: J) => T, childKey: string = "children"): T[] {
  let data: T[] = [];
  oriData.forEach((item: J) => {
    if (item[childKey] && item[childKey].length > 0) item[childKey] = treeDataInit<T, J>(item[childKey], func);
    data.push({ ...func(item) });
  });

  return data;
}
