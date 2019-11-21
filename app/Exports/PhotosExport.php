<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Exception;
use App\Models\Photo;

class PhotosExport implements FromView
{
    use Exportable;

    public function __construct()
    {
    }

    public function getPhotos()
    {
        $photos = Photo::where('is_deleted', false)->get();
        return $photos;
    }

    public function view(): View
    {
        $photos = $this->getPhotos();
        return view('exports.photos', [
            'photos' => $photos
        ]);
    }
}
