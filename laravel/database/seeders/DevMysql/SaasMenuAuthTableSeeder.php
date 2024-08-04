<?php

namespace Database\Seeders\DevMysql;



class SaasMenuAuthTableSeeder extends Base
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('saas_menu_auth')->insert(array (
            0 =>
            array (
                'auth_id' => 19,
                'auth_name' => '添加',
                'menu_id' => 67,
                'auth_sort' => 50,
                'created_time' => '2023-04-20 18:16:36',
                'updated_time' => '2023-04-20 18:16:36',
                'common' => 0,
            ),
            1 =>
            array (
                'auth_id' => 20,
                'auth_name' => '编辑',
                'menu_id' => 67,
                'auth_sort' => 50,
                'created_time' => '2023-04-20 18:16:41',
                'updated_time' => '2023-04-20 18:16:41',
                'common' => 0,
            ),
            2 =>
            array (
                'auth_id' => 21,
                'auth_name' => '删除',
                'menu_id' => 67,
                'auth_sort' => 50,
                'created_time' => '2023-04-20 18:16:46',
                'updated_time' => '2023-04-20 18:16:46',
                'common' => 0,
            ),
            3 =>
            array (
                'auth_id' => 23,
                'auth_name' => '设置权限',
                'menu_id' => 67,
                'auth_sort' => 50,
                'created_time' => '2023-04-20 18:17:46',
                'updated_time' => '2023-04-20 18:17:46',
                'common' => 0,
            ),
            4 =>
            array (
                'auth_id' => 24,
                'auth_name' => '添加',
                'menu_id' => 66,
                'auth_sort' => 50,
                'created_time' => '2023-04-20 18:18:35',
                'updated_time' => '2023-04-20 18:18:35',
                'common' => 0,
            ),
            5 =>
            array (
                'auth_id' => 25,
                'auth_name' => '编辑',
                'menu_id' => 66,
                'auth_sort' => 50,
                'created_time' => '2023-04-20 18:18:41',
                'updated_time' => '2023-04-20 18:18:41',
                'common' => 0,
            ),
            6 =>
            array (
                'auth_id' => 26,
                'auth_name' => '删除',
                'menu_id' => 66,
                'auth_sort' => 50,
                'created_time' => '2023-04-20 18:18:46',
                'updated_time' => '2023-06-27 15:24:41',
                'common' => 0,
            ),
            7 =>
            array (
                'auth_id' => 28,
                'auth_name' => '添加',
                'menu_id' => 74,
                'auth_sort' => 52,
                'created_time' => '2024-07-17 21:15:38',
                'updated_time' => '2024-07-17 21:26:27',
                'common' => 0,
            ),
            8 =>
            array (
                'auth_id' => 29,
                'auth_name' => '编辑',
                'menu_id' => 74,
                'auth_sort' => 51,
                'created_time' => '2024-07-17 21:15:43',
                'updated_time' => '2024-07-17 21:26:30',
                'common' => 0,
            ),
            9 =>
            array (
                'auth_id' => 30,
                'auth_name' => '删除',
                'menu_id' => 74,
                'auth_sort' => 50,
                'created_time' => '2024-07-17 21:15:47',
                'updated_time' => '2024-07-17 21:15:47',
                'common' => 0,
            ),
        ));


    }
}
