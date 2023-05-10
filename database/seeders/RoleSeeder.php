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
            array('name' => "admin"),
            array('name' => "student"),
            array('name' => "secretary"),
            array('name' => "manager"),
        );
        for ($i = 0; $i < count($roles); $i++) {
            DB::table('user_roles')->insert([
                'name' => $roles[$i]['name'],
                'created_at' => date("y-m-d h:i:s"),
                'updated_at' => date("y-m-d h:i:s")
            ]);
        }
    }
}
