<?php

declare(strict_types=1);

namespace App\Http\Entrances\Modules;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use App\Helpers\Enums\ErrorCode;
use App\Helpers\Enums\TrueCode;
use App\Helpers\Func;
use App\Helpers\Inject;
use App\Services\Saas\AdminsLoginInfo;
use App\Helpers\Queue\Modules\Rabbitmq;
use App\Http\Interfaces\Entrances;

class Saas extends Base  implements Entrances
{

    public array $sql  = [];  //贮存sql的记录
    public AdminsLoginInfo|null  $adminsLoginInfo = null;  //用户信息
    public array|null  $authApi = null;  //是否加入日志


    public function loadController(string|array $moduleClass, \Closure|null $formatFunc = null, array $funcParams = [])
    {
        $this->adminsLoginInfo = Inject::bindGet(AdminsLoginInfo::class);
        $this->authApi = $this->adminsLoginInfo->getAuthApi();  //添加日志
        if ($this->authApi && $this->authApi['add_log'] == 1) $this->watchDb();  //监听操作日志
        return  parent::handle($moduleClass, $formatFunc, $funcParams,  $this->authApi && $this->authApi['add_log'] == 1 ? $this->addAdminLog(...) : null);
    }

    public final function watchDb()
    {
        // * 监听sql语句(排除查询) 加入日志 *
        DB::listen(fn ($sql) =>   $this->sql[] = ['sql' =>  sprintf(str_replace("?", "%s", $sql->sql), ...array_map(fn ($item) => "'$item'", $sql->bindings)), 'params' => $sql->bindings, 'time' => $sql->time]);
    }

    public final function addAdminLog(BaseController $module)
    {

        if (
            $module->result->getHttpVal() === ErrorCode::SUCCESS->value &&
            $module->result->getTrueCodeName() === TrueCode::SUCCESS->name
        ) {
            Rabbitmq::SAAS_ADMIN_OPERATION->run([
                "sql" => $this->sql,
                "params" => $this->request->input(),
                "tenant_id" => $this->adminsLoginInfo->getTenantId(),
                // "admins_id" => Inject::get(AdminsLoginInfo::class)->getPrimaryKey(),
                "router_path" => $this->authApi['router_path'],
                "api" => $this->request->path(),
                "auth_name" => $this->authApi['auth_name'],
                "sql" => $this->sql,
                "ip" => Func::getRealIp($this->request),
                "menu_id" =>  $this->authApi['menu_id'],
                "insert_admins_id" =>  $this->adminsLoginInfo->getPrimaryKey(),
                "insert_organi_id" => $this->adminsLoginInfo->getLoginOrganiId() <= 0 ? 0 : $this->adminsLoginInfo->getLoginOrganiId(),
                "enum" =>   [
                    'class' =>  $module->result->getHttpEnum()::class,
                    'name' => $module->result->getHttpName(),
                    'trueCodeName' => $module->result->getTrueCodeName(),
                    'code' => $module->result->getHttpVal(),
                ],
                "method" => $this->request->method(),
                "operation_time" => date('Y-m-d H:i:s')
            ]);
        }
    }
}
