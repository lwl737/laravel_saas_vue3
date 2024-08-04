<?php

declare(strict_types=1);

namespace App\Http\Controllers\Saas;

use App\Dao\Mysql\Saas\RoleDao\Orm as RoleDao;
use App\Helpers\Output\Json\Dev;
use App\Helpers\Output\Json\Success;
use App\Helpers\Func;

class Role extends BaseController
{

    /**  树数据 */
    public function treeData()
    {

        $role_id = $this->getAdminsLoginInfo()->getInfo(['role_id'])['role_id'];

        $result = RoleDao::static()->treeData($role_id);

        if (!$result) return $this->outputEnum(Dev::ROLE_NOT_EXIST);

        return $this->output(Func::createTree($result, 'menu_id', 'menu_pid', 'children', function ($item) {
            if ($item['menu_pid']  === null) $item['menu_pid'] =  0;
            return $item;
        }), Success::SEL_SUCCESS);
    }

    /**  列表 */
    public function list()
    {

        $model = RoleDao::static()->listConfig($this->params);

        return $this->outputList(
            total: $model->query()->count(),
            list: $model->list($this->params, beforeFunc: fn ($query) =>  $query->orderBy('role_sort', 'desc'))->listOrganiGet()
        );
    }

    /**  添加 */
    public function add()
    {
        RoleDao::static()->add($this->params);
        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    /**  删除 */
    public function del()
    {
        RoleDao::static()->organiScope()->del($this->params);

        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    /**  修改 */
    public function edit()
    {

        RoleDao::static()->organiScope()->edit($this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }

    /**  设置权限 */
    public function set()
    {

        $role_id = $this->getAdminsLoginInfo()->getInfo(['role_id'])['role_id'];



        if ($role_id > 0) {

            $auth_id = [];

            $menu_id = [];

            $roleData =   RoleDao::static()->getRoleData($role_id);

            if (!$roleData) return $this->outputEnum(Dev::ROLE_NOT_EXIST);

            foreach ($roleData as $key => $val) {
                $menu_id[] = $key;
                $auth_id = array_merge($auth_id, $val);
            }

            foreach ($this->params["role_json"] as $key => $val) {
                if (!in_array($key, $menu_id)) {
                    return $this->outputEnum(Dev::AUTH_NOT_EXIST);
                }
                //比对每个栏目 只能设置跟自己一样的权限
                foreach ($val as $vals) {
                    //比对每个权限  只能设置跟自己一样的权限
                    if (!in_array($vals, $auth_id)) {
                        return $this->outputEnum(Dev::AUTH_NOT_EXIST);
                    }
                }
            }
        }


        RoleDao::static()->organiScope()->set($this->params);

        return $this->outputEnum(Success::SET_SUCCESS);
    }

    /**  所有 */
    // public function all()
    // {
    //     return  $this->output(
    //         ['list' =>   RoleDao::static()->orderBy(beforeFunc: fn ($query) => $query->orderBy('role_sort', 'desc'))->query()->get(['role_name', 'role_id'])->toArray()],
    //         Success::SEL_SUCCESS
    //     );
    // }

    public function editStatus(){
        RoleDao::static()->organiScope()->editStatus($this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
