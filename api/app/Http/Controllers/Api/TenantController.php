<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TenantRequest;
use App\Services\TenantService;

class TenantController extends Controller
{
    /**
     * TenantController constructor.
     *
     * @param  TenantRequest  $request
     */
    public function __construct(TenantService $service)
    {
        parent::__construct($service, new TenantRequest);
    }
}
