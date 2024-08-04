<?php

declare(strict_types=1);
namespace App\Models\Redis\DB;
enum DB0:string
{
  case  PUBLIC_PEM  =  'PUBLIC_PEM';                    //JWT登录公钥文本
  case  PRIVATE_PEM  = 'PRIVATE_PEM';                  //JWT登录私钥文本
  case  MONGODB_INDEX  = 'MONGODB_INDEX_';                  //JWT登录私钥文本
  case  STS_TOKEN  = 'STS_TOKEN_'  ;   //oss TOKEN


}
