<?php

declare(strict_types=1);

namespace App\Http\Entrances\Modules;

use Illuminate\Support\Facades\DB;
use App\Helpers\Enums\ErrorCode;
use App\Helpers\Enums\TrueCode;
use App\Helpers\Func;
use App\Http\Services\Admin\LoginInfo\Dev\ServiceProvider as AdminsLoginInfo;
use App\Helpers\Queue\Modules\Rabbitmq;
use App\Http\Interfaces\Entrances;
use App\Http\Systems\CustomRouter;
class Admin  implements Entrances
{

    public array $sql  = [];  //贮存sql的记录
    public AdminsLoginInfo|null  $adminsLoginInfo = null;  //用户信息
    public array|null  $authApi = null;  //是否加入日志


    public function before(){
        $this->adminsLoginInfo = app(AdminsLoginInfo::class);
        $this->authApi = $this->adminsLoginInfo->getAuthApi();  //添加日志
        /** 监听sql语句(排除查询) 加入日志 **/
        if ($this->authApi && $this->authApi['add_log'] == 1) DB::listen(fn ($sql) =>   $this->sql[] = ['sql' =>  sprintf(str_replace("?", "%s", $sql->sql), ...array_map(fn ($item) => "'$item'", $sql->bindings)), 'params' => $sql->bindings, 'time' => $sql->time]);

    }


    public function after(CustomRouter $customRouter){
        if (
            $customRouter->getResult()->getHttpVal() === ErrorCode::SUCCESS->value &&
            $customRouter->getResult()->getTrueCodeName() === TrueCode::SUCCESS->name &&
            $this->authApi && $this->authApi['add_log'] == 1
        ) {
            Rabbitmq::DEV_ADMIN_OPERATION->run([
                "sql" => $this->sql,
                "params" => $customRouter->getParams(),
                "router_path" => $this->authApi['router_path'],
                "api" => $customRouter->getRequest()->path(),
                "auth_name" => $this->authApi['auth_name'],
                "sql" => $this->sql,
                "ip" => Func::getRealIp($customRouter->getRequest()),
                "menu_id" =>  $this->authApi['menu_id'],
                "insert_admins_id" =>  $this->adminsLoginInfo->getPrimaryKey(),
                "insert_organi_id" => $this->adminsLoginInfo->getLoginOrganiId() <= 0 ? 0 : $this->adminsLoginInfo->getLoginOrganiId(),
                "enum" =>   [
                    'class' =>  $customRouter->getResult()->getHttpEnum()::class,
                    'name' => $customRouter->getResult()->getHttpName(),
                    'trueCodeName' => $customRouter->getResult()->getTrueCodeName(),
                    'code' => $customRouter->getResult()->getHttpVal(),
                ],
                "method" => $customRouter->getRequest()->method(),
                "operation_time" => date('Y-m-d H:i:s')
            ]);
        }
    }
}
