<?php

namespace App\Http\Controllers\API;

class CashController extends BaseController
{
    /**
     * @var string
     */

    protected $modal = 'App\Models\CashDepart';

    /**
     * @var string
     */

    protected $resource = 'App\Http\Resources\CashResource';

    public function __construct()
    {
        parent::__construct();
    }
}
