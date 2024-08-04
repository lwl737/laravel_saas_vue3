<?php

namespace Database\Seeders\DevMysql;



class SaasMenuRelationTableSeeder extends Base
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('saas_menu_relation')->insert(array (
            0 =>
            array (
                'menu_ancestor_id' => 65,
                'menu_descendant_id' => 65,
                'level' => 0,
            ),
            1 =>
            array (
                'menu_ancestor_id' => 65,
                'menu_descendant_id' => 66,
                'level' => 0,
            ),
            2 =>
            array (
                'menu_ancestor_id' => 65,
                'menu_descendant_id' => 67,
                'level' => 0,
            ),
            3 =>
            array (
                'menu_ancestor_id' => 65,
                'menu_descendant_id' => 68,
                'level' => 0,
            ),
            4 =>
            array (
                'menu_ancestor_id' => 66,
                'menu_descendant_id' => 66,
                'level' => 1,
            ),
            5 =>
            array (
                'menu_ancestor_id' => 67,
                'menu_descendant_id' => 67,
                'level' => 1,
            ),
            6 =>
            array (
                'menu_ancestor_id' => 68,
                'menu_descendant_id' => 68,
                'level' => 1,
            ),
            7 =>
            array (
                'menu_ancestor_id' => 74,
                'menu_descendant_id' => 74,
                'level' => 0,
            ),
        ));

    }
}
