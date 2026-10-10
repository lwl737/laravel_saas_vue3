<?php

declare(strict_types=1);

namespace App\Common\Repository\Saas;

use App\Common\Dao\Mysql\Saas\AdminsDao\Orm as AdminsDao;
use App\Common\Dao\Mysql\Saas\AdminsDao\Enums\UserEnable;
use App\Helpers\Func;
use App\Helpers\Output\Json\Saas;
use App\Common\Models\Mysql\Orm\Saas\Admins;

class AdminsRepository extends BaseRepository
{
    public function __construct(
        protected AdminsDao $dao
    ) {}


    public function checkUserEnable(array $params): Saas|Admins
    {
        $admins_res = $this->dao->checkUserEnable($params);

        if ($admins_res instanceof UserEnable) return match ($admins_res) {
            //账号不存在
            UserEnable::NOT_EXIST => Saas::LOGIN_NOT_EXIST,
            //账号密码修改过
            UserEnable::USER_PASS_ERROR => Saas::LOGIN_USER_PASS_CHANGE,
            //账号被禁用
            UserEnable::STATUS_NOT_ENABLE => Saas::LOGIN_USER_DISABLE,
            default => Saas::LOGIN_TOKEN_ERROR
        };

        return $admins_res;
    }




    public function list(array $params, array|true $organi_link){

        return  [
            "total" => $this->dao->listConfig( $params, true ,$organi_link)->rollCount() ,
            "list" => $this->dao->initialize()->listConfig( $params, false ,$organi_link)->getList($params) ] ;

    }





    public function superAdminList(array $params)
    {

        $dao   =  $this->dao->superAdmin()->searchConfig($params);
        return ["total" => $dao->query()->count(), "list" => $dao->list($params)->query()->get([
            'admins.admins_id',
            'admins.username',
            'admins.nick_name',
            'admins.real_name',
            'admins.phone',
            'admins.status',
            'admins.role_id',
            'admins.portrait',
        ])];
    }


    public function checkUserPass(array $params)
    {
        return $this->dao->checkUserPass($params)
            ->query()
            ->first(['status', 'admins_id', 'role_id', 'username', 'password']);
    }

    public function checkUsername(string $username, int|null $admins_id = null)
    {
        return  $this->dao->checkExist(["username" =>  $username], $admins_id);
    }

    public function normalAdd(array $params){

        $params["portrait"] = isset($params['portrait']) && $params['portrait'] ?  $params['portrait'] : "";

        $this->transaction(function () use ($params) {
            $admins_id =  $this->dao->saveOrgani($this->formatAdminsFrom($params));
            app()->make(OrganiAdminsBindRepository::class)->insertAll(
                array_map(fn ($item)  => [
                    'admins_id' =>  $admins_id,
                    'organi_id' =>  $item,
                ], $params["organi_id"])
            );
        });
    }

    private function formatAdminsFrom(array $params){
         return Func::formExtractData( $params ,["username","password","status","nick_name","real_name","phone","role_id","portrait"] );
    }

    public function normalEdit(int $admins_id , array $params)
    {
        $params["portrait"] = isset($params['portrait']) && $params['portrait'] ?  $params['portrait'] : "";

        $this->transaction(function () use ($params , $admins_id) {
            $res = $this->dao->organiScope()->edit($admins_id , $this->formatAdminsFrom($params));

            if ($res) app()->make(OrganiAdminsBindRepository::class)->changeBind($params["organi_id"], $admins_id);
        });
    }



    public function superAdminAdd(array $params)
    {
        $params['portrait'] =  isset($params['portrait']) && $params['portrait'] ?  $params['portrait'] : "";
        $params['role_id'] =  0;
        $this->dao->add($params);
    }

    public function editsStatus(int|string $primary_val, int $status)
    {
        return $this->dao->editEnumStatus($primary_val,  $status, true);
    }

    public function edit(int $admins_id, array $params , bool $is_query = true)
    {
        return $this->dao->edit($admins_id,   $params, null, $is_query);
    }

    public function editPass(int $admins_id, array $params){
        return $this->dao->edit($admins_id ,  ['password' => $params['password'] ]  , function($query) use ($params){
            $query->where("password"  , $params["ori_password"]);
        } );
    }


    public function del(array $ids)
    {
        $data = $this->dao->getById($ids, null, true)->map(fn($item) => [  'admins_id' => $item->admins_id,"data" => serialize($item->toArray()) ])->toArray();
        if (!empty($data)) {
            $this->transaction(function () use ($ids,  $data) {
                //加入回收站
                app()->make(AdminsDelRepository::class)->insertAll($data);
                $this->dao->del($ids, null, true);
            });
        }
    }
}
