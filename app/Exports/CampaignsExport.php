<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Exception;
use App\Models\Campaign;

class CampaignsExport implements FromView
{
    use Exportable;

    public function __construct()
    {
    }

    public function getCampaigns()
    {
        $campaigns = Campaign::where('is_deleted', 0)->get();
        return $campaigns;
    }

    public function view(): View
    {
        $campaigns = $this->getCampaigns();
        return view('exports.campaigns', [
            'campaigns' => $campaigns
        ]);
    }
}
