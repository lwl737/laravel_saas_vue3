<?php

declare(strict_types=1);

namespace App\Utils\Permissions;

use App\Models\Mysql\Orm\BaseOrm;

class CheckApi
{

    /**
     * Undocumented function
     *
     * @param BaseOrm $roleModel            权限orm
     * @param BaseOrm $menuModel            栏目orm
     * @param BaseOrm $menuAuthModel        栏目权限orm
     **/
    public function __construct(
        public readonly BaseOrm  $roleModel,
        public readonly BaseOrm  $menuModel,
        public readonly BaseOrm  $menuAuthModel,
        public readonly BaseOrm  $menuAuthApiModel,
    ) {
    }






    private  function getMenuApi(string $api, string $path)
    {
        return $this->menuAuthApiModel->join($this->menuModel->tableName, $this->menuModel->tableName . '.menu_id', '=', $this->menuAuthApiModel->tableName . '.menu_id')
            ->leftJoin($this->menuAuthModel->tableName, $this->menuAuthModel->tableName . '.auth_id', '=', $this->menuAuthApiModel->tableName . '.auth_id')
            ->where($this->menuModel->tableName . '.path', $path)
            ->where($this->menuAuthApiModel->tableName . '.api', $api)
            ->get([
                $this->menuAuthApiModel->tableName . '.api_id',
                $this->menuAuthApiModel->tableName . '.add_log',
                $this->menuAuthModel->tableName . '.auth_id',
                $this->menuModel->tableName . '.title',
                $this->menuModel->tableName . '.menu_id',
                $this->menuAuthModel->tableName . '.auth_name'
            ])->toArray();
    }

    public  function checkMenuApi(int $role_id, string $api, string $path): null|array
    {

        $menuApiArr =  $this->getMenuApi($api, $path);

        if (empty($menuApiArr)) return null;  //没有加入权限

        if ($role_id <= 0) {
            //超级管理员和开发者或者验证成功
            $result = [
                'add_log' => $menuApiArr[0]['add_log'],
                'auth_name' => $menuApiArr[0]['auth_name'],
                'menu_title' => $menuApiArr[0]['title'],
                'menu_id' => $menuApiArr[0]['menu_id'],
                'check' => true,
            ];
        } else {
            $role_json =  $this->roleModel->where('role_id', $role_id)->first(['role_json'])->role_json;

            /* NULL 为栏目公共接口 */
            foreach ($menuApiArr as $menuApi) {
                $result = [
                    'add_log' => $menuApi['add_log'],
                    'auth_name' => $menuApi['auth_name'],
                    'menu_title' => $menuApi['title'],
                    'menu_id' => $menuApi['menu_id'],
                    'check' => false
                ];
                if (isset($role_json[$menuApi['menu_id']]) && ($menuApi['auth_id'] === null  || in_array($menuApi['auth_id'], $role_json[$menuApi['menu_id']]))) {
                    $result['check'] = true;
                    break;
                }
            }
        }

        return $result;
    }
}
