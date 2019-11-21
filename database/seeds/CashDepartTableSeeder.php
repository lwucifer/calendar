<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CashDepartTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cash_departs')->insert([
            'name' => 'レジ部門1',
            'manager_id'=> 1
        ]);
        DB::table('cash_departs')->insert([
            'name' => 'レジ部門2',
            'manager_id'=> 1
        ]);
    }
}
