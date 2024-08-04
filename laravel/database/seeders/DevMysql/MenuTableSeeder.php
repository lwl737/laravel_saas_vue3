<?php

namespace Database\Seeders\DevMysql;



class MenuTableSeeder extends Base
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('menu')->insert(array (
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
                'updated_time' => '2024-08-03 05:21:31',
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
                'updated_time' => '2024-08-03 21:27:41',
                'auth_id' => NULL,
            ),
            2 =>
            array (
                'menu_id' => 67,
                'menu_pid' => 65,
                'path' => '/account/role/index',
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
                'updated_time' => '2024-08-03 21:28:02',
                'auth_id' => NULL,
            ),
            3 =>
            array (
                'menu_id' => 68,
                'menu_pid' => 65,
                'path' => '/account/operation_log/index',
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
                'updated_time' => '2024-08-03 21:28:47',
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
                'updated_time' => '2024-07-26 07:19:46',
                'auth_id' => NULL,
            ),
            5 =>
            array (
                'menu_id' => 75,
                'menu_pid' => NULL,
                'path' => '/saas',
                'name' => '',
                'redirect' => '/saas/tenant/index',
                'component' => '',
                'menu_sort' => 50,
                'icon' => 'OfficeBuilding',
                'title' => 'saas管理',
                'isLink' => '',
                'isHide' => 0,
                'isFull' => 0,
                'isAffix' => 0,
                'isKeepAlive' => 1,
                'created_time' => '2024-07-27 23:30:28',
                'updated_time' => '2024-07-28 19:14:51',
                'auth_id' => NULL,
            ),
            6 =>
            array (
                'menu_id' => 76,
                'menu_pid' => 75,
                'path' => '/saas/tenant/index',
                'name' => 'SaasTenantIndex',
                'redirect' => '',
                'component' => '/saas/tenant/index',
                'menu_sort' => 50,
                'icon' => 'School',
                'title' => '租户管理',
                'isLink' => '',
                'isHide' => 0,
                'isFull' => 0,
                'isAffix' => 0,
                'isKeepAlive' => 1,
                'created_time' => '2024-07-27 23:31:45',
                'updated_time' => '2024-07-27 23:31:45',
                'auth_id' => NULL,
            ),
            7 =>
            array (
                'menu_id' => 77,
                'menu_pid' => 75,
                'path' => '/saas/dev',
                'name' => '',
                'redirect' => '/saas/dev/menu',
                'component' => '',
                'menu_sort' => 50,
                'icon' => 'Setting',
                'title' => '开发管理',
                'isLink' => '',
                'isHide' => 0,
                'isFull' => 0,
                'isAffix' => 0,
                'isKeepAlive' => 1,
                'created_time' => '2024-07-28 19:20:43',
                'updated_time' => '2024-07-28 19:23:04',
                'auth_id' => NULL,
            ),
            8 =>
            array (
                'menu_id' => 78,
                'menu_pid' => 77,
                'path' => '/saas/dev/menu/index',
                'name' => 'SaasDevMenuIndex',
                'redirect' => '',
                'component' => '/saas/dev/menu/index',
                'menu_sort' => 50,
                'icon' => 'Setting',
                'title' => '栏目管理',
                'isLink' => '',
                'isHide' => 0,
                'isFull' => 0,
                'isAffix' => 0,
                'isKeepAlive' => 1,
                'created_time' => '2024-07-28 19:22:48',
                'updated_time' => '2024-07-28 19:23:13',
                'auth_id' => NULL,
            ),
            9 =>
            array (
                'menu_id' => 89,
                'menu_pid' => 75,
                'path' => '/saas/super_admin/index',
                'name' => 'SaasSuperAdminIndex',
                'redirect' => '',
                'component' => '/saas/superAdmin/index',
                'menu_sort' => 49,
                'icon' => 'Avatar',
                'title' => '超级管理员',
                'isLink' => '',
                'isHide' => 0,
                'isFull' => 0,
                'isAffix' => 0,
                'isKeepAlive' => 1,
                'created_time' => '2024-07-29 20:36:38',
                'updated_time' => '2024-07-30 01:02:21',
                'auth_id' => NULL,
            ),
            10 =>
            array (
                'menu_id' => 90,
                'menu_pid' => 75,
                'path' => '/saas/operation_log/index',
                'name' => 'SaasOperationLogIndex',
                'redirect' => '',
                'component' => '/saas/operationLog/index',
                'menu_sort' => 48,
                'icon' => 'Setting',
                'title' => '操作日志',
                'isLink' => '',
                'isHide' => 0,
                'isFull' => 0,
                'isAffix' => 0,
                'isKeepAlive' => 1,
                'created_time' => '2024-08-04 02:11:33',
                'updated_time' => '2024-08-04 02:12:06',
                'auth_id' => NULL,
            ),
            11 =>
            array (
                'menu_id' => 91,
                'menu_pid' => 77,
                'path' => '/saas/dev/operation_log/index',
                'name' => 'SaasDevOperationLogIndex',
                'redirect' => '',
                'component' => '/saas/dev/operationLog/index',
                'menu_sort' => 50,
                'icon' => 'Setting',
                'title' => '操作日志',
                'isLink' => '',
                'isHide' => 0,
                'isFull' => 0,
                'isAffix' => 0,
                'isKeepAlive' => 1,
                'created_time' => '2024-08-04 03:29:18',
                'updated_time' => '2024-08-04 03:29:18',
                'auth_id' => NULL,
            ),
        ));


    }
}