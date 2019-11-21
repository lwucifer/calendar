<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Exception;
use App\Models\Store;

class StoresExport implements FromView
{
    use Exportable;

    public function __construct()
    {
    }

    public function getStores()
    {
        $stores = Store::where('is_deleted', 0)->get();
        return $stores;
    }

    public function view(): View
    {
        $stores = $this->getStores();
        return view('exports.stores', [
            'stores' => $stores
        ]);
    }
}
