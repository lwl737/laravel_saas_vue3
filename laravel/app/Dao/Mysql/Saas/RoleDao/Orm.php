<?php

declare(strict_types=1);
namespace App\Dao\Mysql\Saas\RoleDao;
use App\Models\Mysql\Orm\Saas\Role;
use App\Dao\Mysql\Saas\BaseSaas;
use App\Dao\Mysql\Saas\RoleDao\Enums\RoleEnable;
use App\Models\Mysql\Orm\Dev\SaasMenu;
use App\Models\Mysql\Orm\Dev\SaasMenuAuth;

class Orm extends BaseSaas{
    public function __construct()
    {

        $this->query = new Role;
    }



    public function checkRoleEnable(array $params):Role|RoleEnable{

        $res = $this->query->where("role_id", $params["role_id"])->first([
            'status',
            'role_json',
            'role_id',
        ]);

        if(!$res) return RoleEnable::NOT_EXIST;

        if(!$res->isEnable()) return RoleEnable::STATUS_NOT_ENABLE;

        return $res;

    }

    public function listConfig(array $params){
        $this->listOrganiConfig($params);
        if (isset($params['role_name']))  $this->query =  $this->query->where('role_name',$params['role_name']);
        return $this;
    }

    public function add(array $params){

        $this->query->role_name =  $params['role_name'];
        $this->query->role_sort =  $params['role_sort'];
        $this->query->role_describe =  $params['role_describe'];
        $this->query->status =  $params['status'];
        $this->query->role_json = [];
        $this->query->saveOrgani();
    }

    public function del(array $params){
        return $this->query->whereIn('role_id',$params['ids'])->delete();
    }

    public function checkRoleId(array $params){
        $this->query = $this->query->where("role_id",$params["role_id"]);
        return $this;
    }


    public function edit(array $params){
         return $this->query->where('role_id',$params['role_id'])->update([
            "role_name" => $params['role_name'],
            "role_sort" => $params['role_sort'],
            "status" => $params['status'],
            "role_describe" => $params['role_describe'],
         ]);
    }

    public function editStatus(array $params){
        return $this->query->where('role_id',$params['role_id'])->update([
            "status" => $params['status']
         ]);
    }


    public function getRoleData(int $role_id){
        return $this->query->where("role_id",$role_id)->first(["role_json"])?->role_json;
    }

    public function set(array $params){
        return $this->query->where('role_id', $params['role_id'])->update([
            "role_json" => serialize( $params['role_json'] ),
        ]);
    }


    public function treeData(int $role_id){

          $menuModel = (new SaasMenu)->where('auth_id',null);

          $authModel = new SaasMenuAuth;

        if($role_id > 0){

            $auth_id = [];

            $menu_id = [];

            $roleData = $this->getRoleData($role_id);

            if(!$roleData) return false;

            foreach($roleData as $key => $val){
                $menu_id[] = $key ;
                $auth_id = array_merge($auth_id , $val);
            }

            $menuModel = $menuModel->whereIn('menu_id' , $menu_id);

            $authModel = $authModel->whereIn('auth_id' , $auth_id);

        }

        $auth = $authModel
        ->orderBy('auth_sort','desc')
        ->orderBy('auth_id','desc')
        ->get(['auth_id','auth_name','menu_id'])
        ->toArray();

        return $menuModel->orderBy('menu_sort','desc')->orderBy('menu_id','desc')->get(
            [
                'menu_id',
                'title',
                'menu_pid',
            ]
        )->map(function($item) use ($auth){
            $dataAuth = [];
            foreach($auth as $val){
                if($val['menu_id'] === $item->menu_id)  $dataAuth[] = $val;
            }
            $item->auth = $dataAuth;
            return $item;
        })->toArray();

    }

    public  function  all():array{
        return $this->query
        ->orderBy('role_sort','desc')
        ->orderBy('role_id','desc')
        ->get(['role_name', 'role_id'])
        ->toArray();
    }
}
