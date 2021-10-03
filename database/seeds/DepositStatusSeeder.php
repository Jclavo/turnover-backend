<?php

use Illuminate\Database\Seeder;

class DepositStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('deposit_statuses')->insert([
            'code' => '1',
            'name' => 'pending'
        ]);

        DB::table('deposit_statuses')->insert([
            'code' => '2',
            'name' => 'accepted'
        ]);

        DB::table('deposit_statuses')->insert([
            'code' => '3',
            'name' => 'rejected'
        ]);
    }
}
