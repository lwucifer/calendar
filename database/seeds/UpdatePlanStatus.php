<?php

use Illuminate\Database\Seeder;
use App\Models\Plan;

class UpdatePlanStatus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::where('status', Plan::STATUS_NEW)->update(['status' => Plan::STATUS_CONFIRM]);
    }
}
