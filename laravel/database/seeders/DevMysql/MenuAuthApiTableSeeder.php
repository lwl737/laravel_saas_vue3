<?php

namespace Database\Seeders\DevMysql;

class MenuAuthApiTableSeeder extends Base
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('menu_auth_api')->insert(array (
            0 =>
            array (
                'api_id' => 3,
                'api' => 'admin/del',
                'menu_id' => 66,
                'add_log' => 1,
                'auth_id' => 26,
                'created_time' => '2023-04-22 00:51:33',
                'updated_time' => '2023-04-25 10:55:36',
                'api_name' => '',
            ),
            1 =>
            array (
                'api_id' => 9,
                'api' => 'role/set',
                'menu_id' => 67,
                'add_log' => 1,
                'auth_id' => 23,
                'created_time' => '2023-04-23 23:35:53',
                'updated_time' => '2023-04-24 00:07:29',
                'api_name' => '',
            ),
            2 =>
            array (
                'api_id' => 16,
                'api' => 'role/tree_data',
                'menu_id' => 67,
                'add_log' => 0,
                'auth_id' => 23,
                'created_time' => '2023-04-24 02:39:53',
                'updated_time' => '2024-07-19 22:35:21',
                'api_name' => '',
            ),
            3 =>
            array (
                'api_id' => 17,
                'api' => 'role/del',
                'menu_id' => 67,
                'add_log' => 1,
                'auth_id' => 21,
                'created_time' => '2023-04-24 02:40:22',
                'updated_time' => '2023-04-24 02:40:22',
                'api_name' => '',
            ),
            4 =>
            array (
                'api_id' => 18,
                'api' => 'role/edit',
                'menu_id' => 67,
                'add_log' => 1,
                'auth_id' => 20,
                'created_time' => '2023-04-24 02:40:44',
                'updated_time' => '2023-04-24 02:40:44',
                'api_name' => '',
            ),
            5 =>
            array (
                'api_id' => 19,
                'api' => 'role/add',
                'menu_id' => 67,
                'add_log' => 1,
                'auth_id' => 19,
                'created_time' => '2023-04-24 02:40:52',
                'updated_time' => '2023-04-24 02:40:52',
                'api_name' => '',
            ),
            6 =>
            array (
                'api_id' => 21,
                'api' => 'admin/edit',
                'menu_id' => 66,
                'add_log' => 1,
                'auth_id' => 25,
                'created_time' => '2023-04-25 10:55:54',
                'updated_time' => '2023-04-25 10:55:54',
                'api_name' => '',
            ),
            7 =>
            array (
                'api_id' => 22,
                'api' => 'admin/add',
                'menu_id' => 66,
                'add_log' => 1,
                'auth_id' => 24,
                'created_time' => '2023-04-25 10:56:52',
                'updated_time' => '2023-04-25 10:56:52',
                'api_name' => '',
            ),
            8 =>
            array (
                'api_id' => 23,
                'api' => 'admin/edit_status',
                'menu_id' => 66,
                'add_log' => 1,
                'auth_id' => 25,
                'created_time' => '2023-04-25 10:58:49',
                'updated_time' => '2024-07-25 18:32:06',
                'api_name' => '',
            ),
            9 =>
            array (
                'api_id' => 38,
                'api' => 'role/list',
                'menu_id' => 67,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2023-04-27 21:54:15',
                'updated_time' => '2023-04-27 23:10:33',
                'api_name' => '权限列表',
            ),
            10 =>
            array (
                'api_id' => 39,
                'api' => 'admin/role_all',
                'menu_id' => 66,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2023-04-28 15:16:33',
                'updated_time' => '2024-07-25 18:31:21',
                'api_name' => '权限列表',
            ),
            11 =>
            array (
                'api_id' => 40,
                'api' => 'admin/list',
                'menu_id' => 66,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2023-04-28 15:16:44',
                'updated_time' => '2023-04-28 15:16:44',
                'api_name' => '列表',
            ),
            12 =>
            array (
                'api_id' => 41,
                'api' => 'operation_log/list',
                'menu_id' => 68,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2023-04-28 19:58:00',
                'updated_time' => '2024-07-19 23:04:41',
                'api_name' => '列表',
            ),
            13 =>
            array (
                'api_id' => 49,
                'api' => 'organi/all',
                'menu_id' => 74,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2024-07-17 21:15:21',
                'updated_time' => '2024-07-17 21:15:21',
                'api_name' => '列表',
            ),
            14 =>
            array (
                'api_id' => 50,
                'api' => 'organi/edit',
                'menu_id' => 74,
                'add_log' => 1,
                'auth_id' => 29,
                'created_time' => '2024-07-17 21:41:29',
                'updated_time' => '2024-07-17 21:41:29',
                'api_name' => '',
            ),
            15 =>
            array (
                'api_id' => 51,
                'api' => 'organi/add',
                'menu_id' => 74,
                'add_log' => 1,
                'auth_id' => 28,
                'created_time' => '2024-07-17 21:41:37',
                'updated_time' => '2024-07-17 21:41:37',
                'api_name' => '',
            ),
            16 =>
            array (
                'api_id' => 52,
                'api' => 'organi/del',
                'menu_id' => 74,
                'add_log' => 1,
                'auth_id' => 30,
                'created_time' => '2024-07-17 21:41:42',
                'updated_time' => '2024-07-17 21:41:42',
                'api_name' => '',
            ),
            17 =>
            array (
                'api_id' => 53,
                'api' => 'admin/organi_all',
                'menu_id' => 66,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2024-07-25 18:31:37',
                'updated_time' => '2024-07-25 18:31:37',
                'api_name' => '组织树',
            ),
            18 =>
            array (
                'api_id' => 54,
                'api' => 'admin/list',
                'menu_id' => 74,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2024-07-25 18:46:28',
                'updated_time' => '2024-07-25 18:46:28',
                'api_name' => '管理员列表',
            ),
            19 =>
            array (
                'api_id' => 55,
                'api' => 'saas/develop/saas_menu/all',
                'menu_id' => 78,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2024-07-28 23:15:18',
                'updated_time' => '2024-07-28 23:16:22',
                'api_name' => '所有栏目',
            ),
            20 =>
            array (
                'api_id' => 56,
                'api' => 'saas/develop/saas_menu_api/list',
                'menu_id' => 78,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2024-07-28 23:16:14',
                'updated_time' => '2024-07-28 23:16:14',
                'api_name' => '栏目api',
            ),
            21 =>
            array (
                'api_id' => 57,
                'api' => 'saas/develop/saas_menu_auth/list',
                'menu_id' => 78,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2024-07-28 23:16:50',
                'updated_time' => '2024-07-29 02:33:07',
                'api_name' => '权限列表',
            ),
            22 =>
            array (
                'api_id' => 58,
                'api' => 'saas/develop/saas_menu/add',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 31,
                'created_time' => '2024-07-28 23:17:46',
                'updated_time' => '2024-07-28 23:17:46',
                'api_name' => '',
            ),
            23 =>
            array (
                'api_id' => 59,
                'api' => 'saas/develop/saas_menu/edit',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 32,
                'created_time' => '2024-07-28 23:18:22',
                'updated_time' => '2024-07-28 23:18:22',
                'api_name' => '',
            ),
            24 =>
            array (
                'api_id' => 60,
                'api' => 'aas/develop/saas_menu/del',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 33,
                'created_time' => '2024-07-28 23:18:58',
                'updated_time' => '2024-07-28 23:18:58',
                'api_name' => '',
            ),
            25 =>
            array (
                'api_id' => 61,
                'api' => 'saas/develop/saas_menu_api/add',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 31,
                'created_time' => '2024-07-28 23:27:35',
                'updated_time' => '2024-07-28 23:27:35',
                'api_name' => '',
            ),
            26 =>
            array (
                'api_id' => 62,
                'api' => 'saas/develop/saas_menu_api/add_log',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 32,
                'created_time' => '2024-07-28 23:28:08',
                'updated_time' => '2024-07-28 23:28:08',
                'api_name' => '',
            ),
            27 =>
            array (
                'api_id' => 63,
                'api' => 'saas/develop/saas_menu_api/edit',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 32,
                'created_time' => '2024-07-28 23:28:25',
                'updated_time' => '2024-07-28 23:28:25',
                'api_name' => '',
            ),
            28 =>
            array (
                'api_id' => 64,
                'api' => 'saas/develop/saas_menu_api/del',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 33,
                'created_time' => '2024-07-28 23:28:41',
                'updated_time' => '2024-07-28 23:28:41',
                'api_name' => '',
            ),
            29 =>
            array (
                'api_id' => 65,
                'api' => 'saas/develop/saas_menu_auth/add',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 31,
                'created_time' => '2024-07-28 23:29:00',
                'updated_time' => '2024-07-28 23:29:00',
                'api_name' => '',
            ),
            30 =>
            array (
                'api_id' => 66,
                'api' => 'saas/develop/saas_menu_auth/edit',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 32,
                'created_time' => '2024-07-28 23:31:20',
                'updated_time' => '2024-07-28 23:31:20',
                'api_name' => '',
            ),
            31 =>
            array (
                'api_id' => 67,
                'api' => 'saas/develop/saas_menu_auth_pages/add',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 31,
                'created_time' => '2024-07-28 23:40:07',
                'updated_time' => '2024-07-28 23:40:07',
                'api_name' => '',
            ),
            32 =>
            array (
                'api_id' => 68,
                'api' => 'saas/develop/saas_menu_auth_buttons/add',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 31,
                'created_time' => '2024-07-28 23:40:52',
                'updated_time' => '2024-07-28 23:40:52',
                'api_name' => '',
            ),
            33 =>
            array (
                'api_id' => 69,
                'api' => 'saas/develop/saas_menu_auth_api/add',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 31,
                'created_time' => '2024-07-28 23:42:11',
                'updated_time' => '2024-07-28 23:42:11',
                'api_name' => '',
            ),
            34 =>
            array (
                'api_id' => 70,
                'api' => 'saas/develop/saas_menu_auth_pages/edit',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 32,
                'created_time' => '2024-07-28 23:43:12',
                'updated_time' => '2024-07-28 23:43:12',
                'api_name' => '',
            ),
            35 =>
            array (
                'api_id' => 71,
                'api' => 'saas/develop/saas_menu_auth_buttons/edit',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 32,
                'created_time' => '2024-07-28 23:43:25',
                'updated_time' => '2024-07-28 23:43:25',
                'api_name' => '',
            ),
            36 =>
            array (
                'api_id' => 72,
                'api' => 'saas/develop/saas_menu_auth_api/edit',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 32,
                'created_time' => '2024-07-28 23:43:42',
                'updated_time' => '2024-07-28 23:43:42',
                'api_name' => '',
            ),
            37 =>
            array (
                'api_id' => 73,
                'api' => 'saas/develop/saas_menu_auth/del',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 33,
                'created_time' => '2024-07-28 23:44:33',
                'updated_time' => '2024-07-28 23:44:33',
                'api_name' => '',
            ),
            38 =>
            array (
                'api_id' => 74,
                'api' => 'saas/develop/saas_menu_auth_buttons/del',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 33,
                'created_time' => '2024-07-28 23:45:07',
                'updated_time' => '2024-07-28 23:45:07',
                'api_name' => '',
            ),
            39 =>
            array (
                'api_id' => 75,
                'api' => 'saas/develop/saas_menu_auth_api/del',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 33,
                'created_time' => '2024-07-28 23:45:20',
                'updated_time' => '2024-07-28 23:45:20',
                'api_name' => '',
            ),
            40 =>
            array (
                'api_id' => 76,
                'api' => 'saas/develop/saas_menu_auth_pages/del',
                'menu_id' => 78,
                'add_log' => 1,
                'auth_id' => 33,
                'created_time' => '2024-07-28 23:45:34',
                'updated_time' => '2024-07-28 23:45:34',
                'api_name' => '',
            ),
            41 =>
            array (
                'api_id' => 77,
                'api' => 'saas/tenant/list',
                'menu_id' => 76,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2024-07-29 20:26:10',
                'updated_time' => '2024-07-29 20:26:10',
                'api_name' => '列表',
            ),
            42 =>
            array (
                'api_id' => 78,
                'api' => 'saas/tenant/add',
                'menu_id' => 76,
                'add_log' => 1,
                'auth_id' => 34,
                'created_time' => '2024-07-29 20:26:32',
                'updated_time' => '2024-07-29 20:26:32',
                'api_name' => '',
            ),
            43 =>
            array (
                'api_id' => 79,
                'api' => 'saas/tenant/edit',
                'menu_id' => 76,
                'add_log' => 1,
                'auth_id' => 35,
                'created_time' => '2024-07-29 20:26:48',
                'updated_time' => '2024-07-29 20:26:48',
                'api_name' => '',
            ),
            44 =>
            array (
                'api_id' => 80,
                'api' => 'saas/tenant/editStatus',
                'menu_id' => 76,
                'add_log' => 1,
                'auth_id' => 35,
                'created_time' => '2024-07-29 20:26:54',
                'updated_time' => '2024-07-29 20:26:54',
                'api_name' => '',
            ),
            45 =>
            array (
                'api_id' => 81,
                'api' => 'saas/tenant/del',
                'menu_id' => 76,
                'add_log' => 1,
                'auth_id' => 36,
                'created_time' => '2024-07-29 20:27:12',
                'updated_time' => '2024-07-29 20:27:12',
                'api_name' => '',
            ),
            46 =>
            array (
                'api_id' => 82,
                'api' => 'saas/tenant/all',
                'menu_id' => 89,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2024-08-03 21:34:11',
                'updated_time' => '2024-08-03 21:34:11',
                'api_name' => '租户列表',
            ),
            47 =>
            array (
                'api_id' => 83,
                'api' => 'saas/super_admin/lis',
                'menu_id' => 89,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2024-08-03 21:34:42',
                'updated_time' => '2024-08-03 21:34:42',
                'api_name' => '列表',
            ),
            48 =>
            array (
                'api_id' => 84,
                'api' => 'saas/super_admin/add',
                'menu_id' => 89,
                'add_log' => 1,
                'auth_id' => 37,
                'created_time' => '2024-08-03 21:37:07',
                'updated_time' => '2024-08-03 21:37:07',
                'api_name' => '',
            ),
            49 =>
            array (
                'api_id' => 85,
                'api' => 'saas/super_admin/edit',
                'menu_id' => 89,
                'add_log' => 1,
                'auth_id' => 38,
                'created_time' => '2024-08-03 21:37:12',
                'updated_time' => '2024-08-03 21:37:12',
                'api_name' => '',
            ),
            50 =>
            array (
                'api_id' => 86,
                'api' => 'saas/super_admin/del',
                'menu_id' => 89,
                'add_log' => 1,
                'auth_id' => 39,
                'created_time' => '2024-08-03 21:37:30',
                'updated_time' => '2024-08-03 21:37:30',
                'api_name' => '',
            ),
            51 =>
            array (
                'api_id' => 87,
                'api' => 'saas/super_admin/edit_status',
                'menu_id' => 89,
                'add_log' => 1,
                'auth_id' => 38,
                'created_time' => '2024-08-03 21:37:47',
                'updated_time' => '2024-08-03 21:37:47',
                'api_name' => '',
            ),
            52 =>
            array (
                'api_id' => 88,
                'api' => 'saas/tenant/all',
                'menu_id' => 90,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2024-08-04 03:41:20',
                'updated_time' => '2024-08-04 03:41:20',
                'api_name' => '所有租户',
            ),
            53 =>
            array (
                'api_id' => 89,
                'api' => 'saas/operation_log/list',
                'menu_id' => 90,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2024-08-04 03:41:58',
                'updated_time' => '2024-08-04 03:43:03',
                'api_name' => '列表',
            ),
            54 =>
            array (
                'api_id' => 90,
                'api' => 'saas/tenant/all',
                'menu_id' => 91,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2024-08-04 03:42:33',
                'updated_time' => '2024-08-04 03:42:33',
                'api_name' => '所有租户',
            ),
            55 =>
            array (
                'api_id' => 91,
                'api' => 'saas/develop/operation_log/list',
                'menu_id' => 91,
                'add_log' => 0,
                'auth_id' => NULL,
                'created_time' => '2024-08-04 03:42:56',
                'updated_time' => '2024-08-04 03:42:56',
                'api_name' => '列表',
            ),
        ));

    }
}
