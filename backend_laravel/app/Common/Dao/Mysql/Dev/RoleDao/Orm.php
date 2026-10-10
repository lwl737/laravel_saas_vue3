<?php

declare(strict_types=1);

namespace App\Common\Dao\Mysql\Dev\RoleDao;

use App\Common\Models\Mysql\Orm\Dev\Role;
use App\Common\Dao\Mysql\Dev\BaseDev;
use App\Common\Dao\Mysql\Dev\RoleDao\Enums\RoleEnable;
use App\Common\Models\Mysql\Orm\Dev\Menu;
use App\Common\Models\Mysql\Orm\Dev\MenuAuth;

class Orm extends BaseDev
{


    protected function getModel(): string
    {
        return Role::class;
    }



    public function checkRoleEnable(array $params): Role|RoleEnable
    {

        $res = $this->getById($params["role_id"], [
            'status',
            'role_json',
            'role_id',
        ]);

        if (!$res) return RoleEnable::NOT_EXIST;

        if (!$res->isEnable()) return RoleEnable::STATUS_NOT_ENABLE;

        return $res;
    }

    public function listConfig(array $params)
    {
        $this->query = $this->listOrganiConfig($params)->query()->when(array_key_exists('role_name', $params), function ($query) use ($params) {
            $query->where('role_name', 'like', $params['role_name'] . '%');
        });
        return $this;
    }

    public function add(array $params)
    {

        $this->query->role_name =  $params['role_name'];
        $this->query->role_sort =  $params['role_sort'];
        $this->query->role_describe =  $params['role_describe'];
        $this->query->status =  $params['status'];
        $this->query->role_json = [];
        $this->query->saveOrgani();
    }

    // public function del(array $params){
    //     return $this->query->whereIn('role_id',$params['ids'])->delete();
    // }

    // public function checkRoleId(array $params){
    //     $this->query = $this->query->where("role_id",$params["role_id"]);
    //     return $this;
    // }


    // public function edit(array $params){
    //      return $this->query->where('role_id',$params['role_id'])->update([
    //         "role_name" => $params['role_name'],
    //         "role_sort" => $params['role_sort'],
    //         "status" => $params['status'],
    //         "role_describe" => $params['role_describe'],
    //      ]);
    // }

    public function editStatus(array $params)
    {
        return $this->query->where('role_id', $params['role_id'])->update([
            "status" => $params['status']
        ]);
    }


    public function getRoleData(int $role_id)
    {
        return $this->query->where("role_id", $role_id)->first(["role_json"])?->role_json;
    }

    public function set(array $params)
    {;
        return  $this->organiScope()->query()->where('role_id', $params['role_id'])->update([
            "role_json" => serialize($params['role_json']),
        ]);
    }

    public function all(): array
    {
        return $this->query
            ->orderBy('role_sort', 'desc')
            ->orderBy('role_id', 'desc')
            ->get(['role_name', 'role_id'])
            ->toArray();
    }
}
