<?php

declare(strict_types=1);
namespace App\Dao\Mysql\Dev\AdminsOperationLogDao;
use App\Models\Mysql\Orm\Dev\AdminsOperationLog;
use App\Dao\Mysql\Dev\BaseDev;
class Orm extends BaseDev{
    public function __construct()
    {
        $this->query = new AdminsOperationLog;
    }

    public function listConfig(array $params){

        if(isset($params['log_id']) &&  $params['log_id'] !== null)  $this->query = $this->query->where('admins_operation_log.log_id',(int)$params['log_id']) ;

        if(isset($params['menu_title']) &&  $params['menu_title'] !== null)  $this->query = $this->query->where('admins_operation_log.menu_title','like',(string)$params['menu_title'] . "%") ;

        if(isset($params['router_path']) &&  $params['router_path'] !== null)  $this->query = $this->query->where('admins_operation_log.router_path', 'like', (string)$params['router_path']."%") ;

        if(isset($params['api']) &&  $params['api'] !== null)  $this->query = $this->query->where('admins_operation_log.api',"like",(string)$params['api'] . "%") ;

        if(isset($params['operation_time']) &&  $params['operation_time'] !== null)  $this->query = $this->query->whereDate('admins_operation_log.operation_time',(string)$params['operation_time']) ;

        if(isset($params['auth_name']) &&  $params['auth_name'] !== null) $this->query = $this->query->where('admins_operation_log.auth_name' , "like" ,(string)$params['auth_name']."%") ;

        if(isset($params['ip']) &&  $params['ip'] !== null)  $this->query = $this->query->where("admins_operation_log.ip","like",(string)$params['ip']."%") ;

        if(isset($params['nickName']) &&  $params['nickName'] !== null)  $this->query = $this->query->where( "admins.nick_name" , "like" , (string)$params['nickName']."%") ;

        if(isset($params['realName']) &&  $params['realName'] !== null)  $this->query = $this->query->where("admins.nick_name" , "like" , (string)$params['realName'] . "%") ;

        return $this;
    }
}
