<?php

namespace Database\Seeders\DevMysql;



class SaasMenuAuthApiTableSeeder extends Base
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('saas_menu_auth_api')->insert(array (
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
        ));


    }
}
