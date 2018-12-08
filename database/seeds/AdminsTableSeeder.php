<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_users')->insert([
            'name' => 'singi',
            'username' => '787575327@qq.com',
            'password' => bcrypt('Singi2018@'),
        ]);
    }
}
