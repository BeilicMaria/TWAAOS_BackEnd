<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(
            array('role' => "admin"),
            array('role' => "student"),
            array('role' => "analyst"),
            array('role' => "validator"),
        );
        for ($i = 0; $i < count($roles); $i++) {
            DB::table('user_roles')->insert([
                'role' => $roles[$i]['role'],
                'created_at' => date("y-m-d h:i:s"),
                'updated_at' => date("y-m-d h:i:s")
            ]);
        }
    }
}
