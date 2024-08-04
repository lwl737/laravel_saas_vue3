<?php

namespace Database\Seeders\DevMysql;

class SaasMenuTableSeeder extends Base
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('saas_menu')->insert(array (
            0 =>
            array (
                'menu_id' => 65,
                'menu_pid' => NULL,
                'path' => '/user',
                'name' => '',
                'redirect' => '/account/admin/index',
                'component' => '',
                'menu_sort' => 50,
                'icon' => 'User',
                'title' => '管理员',
                'isLink' => '',
                'isHide' => 0,
                'isFull' => 0,
                'isAffix' => 0,
                'isKeepAlive' => 1,
                'created_time' => '2023-04-06 19:52:40',
                'updated_time' => '2024-08-03 16:59:34',
                'auth_id' => NULL,
            ),
            1 =>
            array (
                'menu_id' => 66,
                'menu_pid' => 65,
                'path' => '/account/admin/index',
                'name' => 'AccountAdminIndex',
                'redirect' => '',
                'component' => '/account/admin/index',
                'menu_sort' => 50,
                'icon' => 'Avatar',
                'title' => '账号管理',
                'isLink' => '',
                'isHide' => 0,
                'isFull' => 0,
                'isAffix' => 0,
                'isKeepAlive' => 1,
                'created_time' => '2023-04-06 20:14:56',
                'updated_time' => '2024-08-03 21:29:24',
                'auth_id' => NULL,
            ),
            2 =>
            array (
                'menu_id' => 67,
                'menu_pid' => 65,
                'path' => '/account/role',
                'name' => 'AccountRoleIndex',
                'redirect' => '',
                'component' => '/account/role/index',
                'menu_sort' => 49,
                'icon' => 'Lock',
                'title' => '权限管理',
                'isLink' => '',
                'isHide' => 0,
                'isFull' => 0,
                'isAffix' => 0,
                'isKeepAlive' => 1,
                'created_time' => '2023-04-10 18:59:35',
                'updated_time' => '2024-08-03 21:29:32',
                'auth_id' => NULL,
            ),
            3 =>
            array (
                'menu_id' => 68,
                'menu_pid' => 65,
                'path' => '/account/operation',
                'name' => 'AccountOperationLogIndex',
                'redirect' => '',
                'component' => '/account/operationLog/index',
                'menu_sort' => 48,
                'icon' => 'Notebook',
                'title' => '操作日志',
                'isLink' => '',
                'isHide' => 0,
                'isFull' => 0,
                'isAffix' => 0,
                'isKeepAlive' => 1,
                'created_time' => '2023-04-28 18:33:07',
                'updated_time' => '2024-08-03 21:29:47',
                'auth_id' => NULL,
            ),
            4 =>
            array (
                'menu_id' => 74,
                'menu_pid' => NULL,
                'path' => '/organi/index',
                'name' => 'OrganiIndex',
                'redirect' => '',
                'component' => '/organi/index',
                'menu_sort' => 50,
                'icon' => 'Share',
                'title' => '部门管理',
                'isLink' => '',
                'isHide' => 0,
                'isFull' => 0,
                'isAffix' => 0,
                'isKeepAlive' => 1,
                'created_time' => '2024-07-15 16:27:39',
                'updated_time' => '2024-07-29 02:47:02',
                'auth_id' => NULL,
            ),
        ));


    }
}
