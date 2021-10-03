<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_types')->insert([
            'code' => '1',
            'name' => 'admin'
        ]);

        DB::table('user_types')->insert([
            'code' => '2',
            'name' => 'customer'
        ]);
    }
}
