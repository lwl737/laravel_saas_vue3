<?php

declare(strict_types=1);

namespace App\Common\Repository\Saas;

use App\Common\Dao\Mysql\Saas\RoleDao\Orm as RoleDao;
use App\Dao\Mysql\Saas\RoleDao\Enums\RoleEnable;
// use App\Helpers\Output\Json\Dev;
use App\Helpers\Output\Json\Saas;
use App\Common\Models\Mysql\Orm\Saas\Role;
use App\Common\Repository\Dev\SaasMenuRepository;
use App\Common\Repository\Dev\SaasMenuAuthRepository;

class RoleRepository extends BaseRepository
{

    public function __construct(
        protected RoleDao $dao
    ) {}

    public function checkRoleEnable(array $data, bool $is_middleware = false): Saas|Role
    {
        $role_res =   $this->dao->checkRoleEnable($data);
        if ($role_res instanceof RoleEnable) return match ($role_res) {
            //权限不存在
            RoleEnable::NOT_EXIST =>  $is_middleware ? Saas::LOGIN_ROLE_NOT_EXIST :  Saas::ROLE_NOT_EXIST,
            //权限被禁用
            RoleEnable::STATUS_NOT_ENABLE => $is_middleware ? Saas::LOGIN_ROLE_DISABLE :  Saas::ROLE_DISABLE,

            default =>  $is_middleware ? Saas::LOGIN_ROLE_UNKNOWN_ERROR  : Saas::ROLE_UNKNOWN_ERROR
        };

        return $role_res;
    }

    public function checkRoleById(int $role_id): Saas|Role
    {

        $res = $this->dao->getById($role_id, ['status']);

        if (!$res) return Saas::ROLE_NOT_EXIST;
        //权限已被禁用禁用
        if (!$res->isEnable()) return Saas::ROLE_DISABLE;

        return $res;
    }

    public function all()
    {
        return $this->dao->organiScope()->whereStatusEnable()->all();
    }

    public function list(array $params)
    {
        $dao = $this->dao->listConfig($params);
        return [
            "total" => $dao->query()->count(),
            "list" => $dao->list($params, beforeFunc: fn($query) =>  $query->orderBy('role_sort', 'desc'))->listOrganiGet()
        ];
    }

    public function tree(int $role_id): array
    {

        $auth_ids = null;

        $menu_ids = null;

        if ($role_id > 0) {

            $auth_ids = [];

            $menu_ids = [];

            $roleData = $this->dao->getRoleData($role_id);

            if (!$roleData) return false;

            foreach ($roleData as $key => $val) {
                $menu_ids[] = $key;
                $auth_ids = array_merge($auth_ids, $val);
            }
        }

        $auth = app()->make(SaasMenuAuthRepository::class)->roleAuth(["auth_ids" => $auth_ids]);
        return app()->make(SaasMenuRepository::class)->pages($menu_ids, $auth);
    }


    public function set(int $self_role_id, int $role_id ,array $role_json): Saas|int
    {

        if ($self_role_id > 0) {

            $auth_id = [];

            $menu_id = [];

            $roleData =   $this->dao->getRoleData($self_role_id);

            if (!$roleData) return Saas::ROLE_NOT_EXIST;

            foreach ($roleData as $key => $val) {
                $menu_id[] = $key;
                $auth_id = array_merge($auth_id, $val);
            }

            foreach ($role_json as $key => $val) {
                if (!in_array($key, $menu_id)) {
                    return Saas::AUTH_NOT_EXIST;
                }
                //比对每个栏目 只能设置跟自己一样的权限
                foreach ($val as $vals) {
                    //比对每个权限  只能设置跟自己一样的权限
                    if (!in_array($vals, $auth_id)) {
                        return Saas::AUTH_NOT_EXIST;
                    }
                }
            }
        }

        return $this->dao->editOrgani($role_id, [
            "role_json" => serialize($role_json)
        ]);
    }


    public function editStatus(int $role_id, int $status)
    {
        $this->dao->editOrgani($role_id, ['status' => $status]);
    }
}
