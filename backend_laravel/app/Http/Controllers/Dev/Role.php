<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev;

use App\Dao\Mysql\Dev\RoleDao\Orm as RoleDao;
use App\Helpers\Output\Json\Dev;
use App\Helpers\Output\Json\Success;
use App\Helpers\Func;
use App\Common\Repository\Dev\RoleRepository;

class Role extends BaseController
{

    /**  树数据 */
    public function treeData(
        RoleRepository $roleRepository
    ) {

        $role_id = $this->getAdminsLoginInfo()->getInfo(['role_id'])['role_id'];

        $result = $roleRepository->tree($role_id);

        if (!$result) return $this->outputEnum(Dev::ROLE_NOT_EXIST);

        return $this->output(Func::createTree($result, 'menu_id', 'menu_pid', 'children', function ($item) {
            if ($item['menu_pid']  === null) $item['menu_pid'] =  0;
            return $item;
        }), Success::SEL_SUCCESS);
    }

    /**  列表 */
    public function list(
        RoleRepository $roleRepository
    ) {
        ["total" => $total, "list" => $list] = $roleRepository->list($this->params);
        return $this->outputList(
            total: $total,
            list: $list
        );
    }

    /**  添加 */
    public function add(RoleRepository $roleRepository)
    {
        $roleRepository->saveOrgani(array_merge($this->params, ['role_json' => []]));
        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    /**  删除 */
    public function del(RoleRepository $roleRepository)
    {
        $roleRepository->delOrgani($this->params['ids']);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    /**  修改 */
    public function edit(int $role_id, RoleRepository $roleRepository)
    {
        $roleRepository->editOrgani($role_id, $this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }

    /**  设置权限 **/
    public function set(int $role_id, RoleRepository $roleRepository)
    {
        $self_role_id = $this->getAdminsLoginInfo()->getInfo(['role_id'])['role_id'];
        $res = $roleRepository->set($self_role_id, $role_id, $this->params["role_json"]);
        if ($res instanceof Dev) return $this->outputEnum($res);
        return $this->outputEnum(Success::SET_SUCCESS);
    }


    public function editStatus(int $role_id, RoleRepository $roleRepository)
    {
        $roleRepository->editStatus($role_id, $this->params["status"]);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }

    public function option(
        RoleRepository $roleRepository
    ) {
        return $this->output([
            'all' => $roleRepository->all()
        ], Success::SEL_SUCCESS);
    }
}
