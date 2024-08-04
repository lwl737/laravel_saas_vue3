<?php

namespace Database\Seeders\DevMysql;


class MenuRelationTableSeeder extends Base
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('menu_relation')->insert(array (
            0 =>
            array (
                'menu_ancestor_id' => 65,
                'menu_descendant_id' => 66,
                'level' => 0,
            ),
            1 =>
            array (
                'menu_ancestor_id' => 65,
                'menu_descendant_id' => 67,
                'level' => 0,
            ),
            2 =>
            array (
                'menu_ancestor_id' => 65,
                'menu_descendant_id' => 68,
                'level' => 0,
            ),
            3 =>
            array (
                'menu_ancestor_id' => 66,
                'menu_descendant_id' => 66,
                'level' => 1,
            ),
            4 =>
            array (
                'menu_ancestor_id' => 67,
                'menu_descendant_id' => 67,
                'level' => 1,
            ),
            5 =>
            array (
                'menu_ancestor_id' => 68,
                'menu_descendant_id' => 68,
                'level' => 1,
            ),
            6 =>
            array (
                'menu_ancestor_id' => 74,
                'menu_descendant_id' => 74,
                'level' => 0,
            ),
            7 =>
            array (
                'menu_ancestor_id' => 75,
                'menu_descendant_id' => 75,
                'level' => 0,
            ),
            8 =>
            array (
                'menu_ancestor_id' => 75,
                'menu_descendant_id' => 76,
                'level' => 0,
            ),
            9 =>
            array (
                'menu_ancestor_id' => 75,
                'menu_descendant_id' => 77,
                'level' => 0,
            ),
            10 =>
            array (
                'menu_ancestor_id' => 75,
                'menu_descendant_id' => 78,
                'level' => 0,
            ),
            11 =>
            array (
                'menu_ancestor_id' => 75,
                'menu_descendant_id' => 89,
                'level' => 0,
            ),
            12 =>
            array (
                'menu_ancestor_id' => 75,
                'menu_descendant_id' => 90,
                'level' => 0,
            ),
            13 =>
            array (
                'menu_ancestor_id' => 75,
                'menu_descendant_id' => 91,
                'level' => 0,
            ),
            14 =>
            array (
                'menu_ancestor_id' => 76,
                'menu_descendant_id' => 76,
                'level' => 1,
            ),
            15 =>
            array (
                'menu_ancestor_id' => 77,
                'menu_descendant_id' => 77,
                'level' => 1,
            ),
            16 =>
            array (
                'menu_ancestor_id' => 77,
                'menu_descendant_id' => 78,
                'level' => 1,
            ),
            17 =>
            array (
                'menu_ancestor_id' => 77,
                'menu_descendant_id' => 91,
                'level' => 1,
            ),
            18 =>
            array (
                'menu_ancestor_id' => 78,
                'menu_descendant_id' => 78,
                'level' => 2,
            ),
            19 =>
            array (
                'menu_ancestor_id' => 89,
                'menu_descendant_id' => 89,
                'level' => 1,
            ),
            20 =>
            array (
                'menu_ancestor_id' => 90,
                'menu_descendant_id' => 90,
                'level' => 1,
            ),
            21 =>
            array (
                'menu_ancestor_id' => 91,
                'menu_descendant_id' => 91,
                'level' => 2,
            ),
        ));


    }
}
