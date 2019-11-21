<?php

namespace App\Http\Controllers\API;

/**
 * Class FNumberController
 * @package App\Http\Controllers\API
 */
class FNumberController extends BaseController
{
    /** Overwrite this modal
     *
     * @var string
     */
    protected $modal = 'App\Models\FNumber';

    /** Overwrite this resource
     *
     * @var string
     */
    protected $resource = 'App\Http\Resources\FNumberResource';

}
