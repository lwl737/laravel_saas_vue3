<?php

declare(strict_types=1);

namespace App\Common\Dao\Mysql\Saas\AdminsDao;

use App\Common\Models\Mysql\Orm\Saas\Admins;
use App\Common\Models\Mysql\Orm\Saas\OrganiAdminsBind;
use App\Common\Dao\Mysql\Saas\BaseSaas;
use App\Common\Dao\Mysql\Saas\AdminsDao\Enums\UserEnable;
use Illuminate\Support\Facades\DB;

class Orm extends BaseSaas
{


    protected function getModel(): string
    {
        return Admins::class;
    }


    public function checkUserPass(array $params)
    {
        $this->query = $this->query->where("username", $params["username"])->where("password", $params["password"]);
        return $this;
    }

    public function checkUserEnable(array $params): Admins|UserEnable
    {

        $res = $this->query->where("admins_id", $params["admins_id"])->first([
            'status',
            'admins_id',
            'password',
            'role_id',
            'portrait',
            'nick_name',
            'username',
            // "*"
        ]);

        if (!$res) return UserEnable::NOT_EXIST;

        if ($res->username !== $params["username"] || $res->password !== $params["password"]) return UserEnable::USER_PASS_ERROR;

        if (!$res->isEnable()) return UserEnable::STATUS_NOT_ENABLE;

        return $res;
    }

    public function editInfo(array $params, int $admins_id)
    {
        return  $this->query->where('admins_id', $admins_id)->update(
            [
                'username' =>  $params['username'],
                'portrait'  =>  $params['portrait'] ?? "",
                'nick_name'  =>  $params['nick_name'],
                'phone'  =>  $params['phone'],
                'real_name'  =>  $params['real_name']
            ]
        );
    }

    public function rollCount()
    {
        $total = (clone $this->query)->groupBy(DB::raw("admins.admins_id WITH ROLLUP"))
            ->orderBy('total', 'desc')
            ->limit(1)
            ->first([DB::raw(" COUNT( DISTINCT `admins`.`admins_id` ) as `total` ")])?->total;
        return   $total ?  $total :  0;
    }


    public function editPass(array $params, int $admins_id)
    {


        return $this->query->where('admins_id', $admins_id)->where('password', $params['ori_password'])->update(['password' => $params['password']]);
    }

    public function add(array $params)
    {
        $this->query->username = $params['username'];
        $this->query->password = $params['password'];
        $this->query->status = $params['status'];
        $this->query->nick_name = $params['nick_name'];
        $this->query->real_name = $params['real_name'];
        $this->query->phone = $params['phone'];
        $this->query->role_id = $params['role_id'];
        $this->query->portrait = isset($params['portrait']) && $params['portrait'] ?  $params['portrait'] : "";
        DB::transaction(function () use ($params) {
            $this->query->saveOrgani();
            if (isset($params["organi_id"])) {
                (new OrganiAdminsBind)->insertAll(
                    array_map(fn($item)  => [
                        'admins_id' =>  $this->query->admins_id,
                        'organi_id' =>  $item,
                    ], $params["organi_id"])
                );
            }
        });
    }

    public function listConfig(array $params, bool $is_count = false, array|true $organi_link = true)
    {


        $this->query = $this->query()
            ->join("organi_admins_bind", 'organi_admins_bind.admins_id', '=', 'admins.admins_id')
            ->join("organi", 'organi.organi_id', '=', 'organi_admins_bind.organi_id')
            ->leftJoin("admins as insert_admins", "insert_admins.admins_id", "=", $this->model->tableName . "." . $this->model->getConst("INSERT_ADMINS_ID_KEY"))
            ->join("role",  'admins.role_id', 'role.role_id');

        // $params["insert_organi_id"] =  $params["insert_organi_id"] ?? [];
        // if( is_array( $organi_link )  )   $params["insert_organi_id"] = array_merge( $params["insert_organi_id"] , )

        $this->query = $this->query()->where('admins.role_id', '>', 0)  //不显示超级管理员和开发者
            ->when(array_key_exists("insert_nick_name", $params), function ($query) use ($params) {
                $query->where("insert_admins.nick_name", "like", $params["insert_nick_name"] . "%");
            })
            ->when(array_key_exists('insert_organi_name', $params), function ($query) use ($params, $is_count) {
                if (preg_match("/\s/", $params["insert_organi_name"])) {
                    $query->whereRaw(" 1 = 2");
                } else if ($is_count) {
                    $query->where("organi.organi_name", "like", "%" . $params["insert_organi_name"] . "%");
                } else {
                    $query->having("insert_organi_name", "like", "%" . $params["insert_organi_name"] . "%");
                }
            })
            ->when(is_array($organi_link), function ($query) use ($organi_link) {
                // if($is_count){
                $query->whereIn("organi_admins_bind.organi_id", $organi_link);
                // }else if(!empty($organi_link)){
                //     $query->havingRaw($this->dbRawfindInSet($organi_link , "organi_id") );
                // }
            })->when(array_key_exists("insert_organi_id", $params), function ($query) use ($params, $is_count) {
                if ($is_count) {
                    $query->whereIn("organi_admins_bind.organi_id", $params["insert_organi_id"]);
                } else if (!empty($params["insert_organi_id"])) {
                    $query->havingRaw($this->dbRawfindInSet($params["insert_organi_id"], "organi_id"));
                }
            });

        return $this->searchConfig($params);
    }


    public function searchConfig(array $params)
    {
        $this->query = $this->query()->when(isset($params['role_id']), function ($query) use ($params) {
            $query->where('admins.role_id', $params['role_id']);
        })->when(isset($params['organi_id']), function ($query) use ($params) {
            $query->whereIn('organi_admins_bind.organi_id', $params['organi_id']);
        })->when(isset($params['nick_name']), function ($query) use ($params) {
            $query->where('admins.nick_name', 'like', $params['nick_name'] . "%");
        })->when(isset($params['real_name']), function ($query) use ($params) {
            $query->where('admins.real_name', 'like', $params['real_name'] . "%");
        })->when(isset($params['phone']), function ($query) use ($params) {
            $query->where('admins.phone', 'like', $params['phone'] . "%");
        })->when(isset($params['username']), function ($query) use ($params) {
            $query->where('admins.username', 'like', $params['username'] . "%");
        });
        return $this;
    }

    public function getList(array $params)
    {
        $this->query = $this->query->groupBy('admins.admins_id');
        return $this->list($params, beforeFunc: fn($query) =>  $query->orderBy('role_sort', 'desc'))->get([
            'admins.admins_id',
            'admins.username',
            'admins.nick_name',
            'admins.real_name',
            'admins.phone',
            'admins.status',
            'admins.role_id',
            'admins.portrait',
            'insert_admins.nick_name as insert_nick_name',
            DB::raw('MAX(`role`.`role_name`) as role_name'),
            DB::raw("GROUP_CONCAT(organi.organi_name SEPARATOR ' ') as insert_organi_name"),
            DB::raw("GROUP_CONCAT(organi.organi_id) as organi_id"),
        ])->map(function ($item) {
            $organi_id = [];

            foreach (explode(",", $item->organi_id) as $val) {
                if ($item !== null &&  $item !== "") $organi_id[] = (int)$val;
            }

            $item->insert_organi_name = implode("\n", explode(" ", $item->insert_organi_name));

            $item->organi_id = $organi_id;
            return $item;
        });
    }


    public function editStatus(array $params)
    {
        return $this->query->where('admins_id',  $params['admins_id'])->update([
            $this->model->getStatusKey() => $params['status']
        ]);
    }

    // public function edit(array $params)
    // {
    //     return  $this->query->where('admins_id',  $params['admins_id'])->update(
    //         array_merge([
    //             "username" => $params['username'],

    //             "status" => $params['status'],
    //             "nick_name" => $params['nick_name'],
    //             "real_name" => $params['real_name'],
    //             "phone" => $params['phone'],
    //             "role_id" => $params['role_id'],
    //             "portrait" => isset($params['portrait']) && $params['portrait'] ?  $params['portrait'] : ""
    //         ],  isset($params['password'])  ? ["password" => $params['password']] : [])
    //     );
    // }

    // public function del(array $params)
    // {

    //     $data = $this->query->whereIn('admins_id',  $params['ids'])->get()->map(fn($item) => ["data" => serialize($item->toArray()) , 'admins_id' => $item->admins_id ])->toArray();
    //     $bool = false;
    //     DB::transaction(function () use ($params ,  $data  , &$bool) {
    //         //加入回收站
    //         if(!empty($data)) (new AdminsDel)->insertAll($data);
    //         $bool = $this->query->whereIn('admins_id',  $params['ids'])->delete();
    //     });
    //     return $bool;
    // }

    public function superAdmin()
    {
        $this->query = $this->query->where('role_id', 0);
        return $this;
    }


    // public function superAdminListConfig(array $params)
    // {
    // $this->superAdmin();

    // if (isset($params['nick_name'])) $this->query = $this->query->where('admins.nick_name', 'like', $params['nick_name'] . "%");
    // if (isset($params['real_name'])) $this->query = $this->query->where('admins.real_name', 'like', $params['real_name'] . "%");
    // if (isset($params['phone'])) $this->query = $this->query->where('admins.phone', 'like', $params['phone'] . "%");
    // if (isset($params['username'])) $this->query = $this->query->where('admins.username', 'like', $params['username'] . "%");

    // return $this;
    // }


    // public function superAdminAdd(array $params)
    // {
    //     $this->query->username = $params['username'];
    //     $this->query->password = $params['password'];
    //     $this->query->status = $params['status'];
    //     $this->query->nick_name = $params['nick_name'];
    //     $this->query->real_name = $params['real_name'];
    //     $this->query->phone = $params['phone'];
    //     $this->query->role_id = 0;
    //     $this->query->portrait = isset($params['portrait']) && $params['portrait'] ?  $params['portrait'] : "";
    //     $this->query->save();
    // }


    // public function superAdminEdit(array $params)
    // {
    //     $this->superAdmin();
    //     return $this->query->where('admins_id',  $params['admins_id'])->update(
    //         array_merge([
    //             "username" => $params['username'],
    //             "status" => $params['status'],
    //             "nick_name" => $params['nick_name'],
    //             "real_name" => $params['real_name'],
    //             "phone" => $params['phone'],
    //             "portrait" => isset($params['portrait']) && $params['portrait'] ?  $params['portrait'] : ""
    //         ],  isset($params['password'])  ? ["password" => $params['password']] : [])
    //     );
    // }

}
