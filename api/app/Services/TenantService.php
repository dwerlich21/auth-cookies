<?php

namespace App\Services;

use App\Repositories\TenantRepository;

class TenantService extends BaseService
{
    /**
     * TenantService constructor.
     */
    public function __construct(TenantRepository $repository)
    {
        parent::__construct($repository);
    }
}
