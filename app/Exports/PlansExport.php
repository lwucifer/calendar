<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Exception;
use App\Models\Plan;

class PlansExport implements FromView
{
    use Exportable;

    public function __construct()
    {
    }

    public function getPlans()
    {
        $photos = Plan::where('is_deleted', 0)->get();
        return $photos;
    }

    public function view(): View
    {
        $plans = $this->getPlans();
        return view('exports.plans', [
            'plans' => $plans
        ]);
    }
}
