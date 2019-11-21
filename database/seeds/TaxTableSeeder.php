<?php

use Illuminate\Database\Seeder;
use App\Models\Tax;

class TaxTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 5; $i++){
            Tax::create([
                'start_date_use' => \Carbon\Carbon::now(),
                'tax_percent' => rand(20,30),
                'manager_id' => 1,
                'is_deleted' => false
            ]);
        }
    }
}
