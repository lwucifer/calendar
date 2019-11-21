<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManagerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('managers')->insert([
            'enable_ip' => 1,
            'user_id' => 1,
            'memo' => '',
            'is_deleted' => 0
        ]);
    }
}
