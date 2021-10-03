<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\UserType;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'type_id' => UserType::getForAdmin(),
        ]);

        DB::table('users')->insert([
            'username' => 'coco',
            'email' => 'coco@test.com',
            'password' => Hash::make('password'),
            'type_id' => UserType::getForCustomer(),
        ]);
    }
}
