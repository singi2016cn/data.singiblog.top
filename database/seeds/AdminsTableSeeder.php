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
        DB::table('admin')->insert([
            'name' => 'singi',
            'email' => '787575327@qq.com',
            'password' => bcrypt('Singi2018@'),
        ]);
    }
}
