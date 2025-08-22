<?php

namespace App\Repositories;

use App\Models\Tenant;

class TenantRepository extends BaseRepository
{
    /**
     * TenantRepository constructor.
     */
    public function __construct(Tenant $model)
    {
        parent::__construct($model);
    }
}
