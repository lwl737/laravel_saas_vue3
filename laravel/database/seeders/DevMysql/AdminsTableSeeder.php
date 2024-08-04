<?php
namespace Database\Seeders\DevMysql;
class AdminsTableSeeder extends Base
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('admins')->insert(array (
            0 =>
            array (
                'admins_id' => 1,
                'username' => '1PuEkrdDHMffngvyMllU',
                'password' => 'a96bb4d13d18f44e891e918cfb3358f1',
                'nick_name' => '开发者',
                'real_name' => '开发者',
                'phone' => '17325764718',
                'portrait' => 'https://lwl-study.oss-cn-guangzhou.aliyuncs.com/dev_admins_file/2/0/e2bd916a-c90d-4e65-b6bf-817b645e731a.png',
                'status' => 1,
                'role_id' => -1,
                'insert_admins_id' => 0,
                'insert_organi_id' => 0,
                'created_time' => '2023-05-04 03:08:24',
                'updated_time' => '2024-07-15 03:48:30',
                'dev_pwd' => 'OwcfsooZhYONq4miHVEb',
            ),
        ));

    }
}
