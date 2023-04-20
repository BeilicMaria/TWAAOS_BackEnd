<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'lastName' => "Super",
            'firstName' => "Admin",
            'password' => bcrypt("AdminTWAAOS2022"),
            'email' => "admin_twaaos@gmail.com",
            'FK_roleId' => 1,
        ]);
    }
}
