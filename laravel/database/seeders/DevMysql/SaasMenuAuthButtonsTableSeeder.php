<?php

namespace Database\Seeders\DevMysql;



class SaasMenuAuthButtonsTableSeeder extends Base
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('saas_menu_auth_buttons')->insert(array (
            0 =>
            array (
                'buttons_id' => 9,
                'buttons' => 'delete',
                'auth_id' => 21,
                'created_time' => '2023-04-20 18:17:15',
                'updated_time' => '2023-04-20 18:17:15',
            ),
            1 =>
            array (
                'buttons_id' => 10,
                'buttons' => 'edit',
                'auth_id' => 20,
                'created_time' => '2023-04-20 18:17:32',
                'updated_time' => '2023-04-20 18:17:32',
            ),
            2 =>
            array (
                'buttons_id' => 11,
                'buttons' => 'setAuth',
                'auth_id' => 23,
                'created_time' => '2023-04-20 18:17:54',
                'updated_time' => '2023-04-20 18:17:54',
            ),
            3 =>
            array (
                'buttons_id' => 12,
                'buttons' => 'add',
                'auth_id' => 19,
                'created_time' => '2023-04-20 18:18:02',
                'updated_time' => '2023-04-20 18:18:02',
            ),
            4 =>
            array (
                'buttons_id' => 13,
                'buttons' => 'delete',
                'auth_id' => 26,
                'created_time' => '2023-04-20 18:22:03',
                'updated_time' => '2023-04-22 03:15:22',
            ),
            5 =>
            array (
                'buttons_id' => 14,
                'buttons' => 'edit',
                'auth_id' => 25,
                'created_time' => '2023-04-20 18:22:09',
                'updated_time' => '2023-04-20 18:22:09',
            ),
            6 =>
            array (
                'buttons_id' => 15,
                'buttons' => 'add',
                'auth_id' => 24,
                'created_time' => '2023-04-20 18:22:16',
                'updated_time' => '2023-04-20 18:22:16',
            ),
            7 =>
            array (
                'buttons_id' => 17,
                'buttons' => 'delete',
                'auth_id' => 30,
                'created_time' => '2024-07-17 21:15:56',
                'updated_time' => '2024-07-17 21:37:41',
            ),
            8 =>
            array (
                'buttons_id' => 18,
                'buttons' => 'edit',
                'auth_id' => 29,
                'created_time' => '2024-07-17 21:16:58',
                'updated_time' => '2024-07-17 21:16:58',
            ),
            9 =>
            array (
                'buttons_id' => 19,
                'buttons' => 'add',
                'auth_id' => 28,
                'created_time' => '2024-07-17 21:17:06',
                'updated_time' => '2024-07-17 21:17:06',
            ),
        ));


    }
}
