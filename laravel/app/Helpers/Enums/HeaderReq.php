<?php

declare(strict_types=1);
namespace App\Helpers\Enums;
//请求头
enum HeaderReq: string
{
  case  DEV_LOGIN_TOKEN = "dev-access-token";                       //后台登录token
  case  SAAS_LOGIN_TOKEN = "saas-access-token";                       //后台登录token
  case  DEV_ROUTER_PATH = "dev-router-path";                        //后台当前页面路径
  case  SAAS_ROUTER_PATH = "saas-router-path";                        //后台当前页面路径
  case  ORGANI_ID = "organi-id";                                    //组织id
  case  REFERER = 'referer';                                        //referer
  case  SIGN = "sign";                                              //签名
  case  REAL_IP = 'x-real-ip';                                      //真实ip
  case  TENANT_ID = 'tenant-id';                                      //真实ip
}

