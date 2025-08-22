<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;

trait HasTenantScope
{
    /**
     * The "booted" method of the model.
     */
    protected static function bootedHasTenantScope(): void
    {
        static::addGlobalScope(new TenantScope);

        // Automatically set tenant_id when creating new records
        static::creating(function ($model) {
            $user = Auth::user();
            if ($user && isset($user->tenant_id) && $user->tenant_id && ! $model->tenant_id) {
                $model->tenant_id = $user->tenant_id;
            }
        });

        // Automatically set tenant_id when updating records
        static::updating(function ($model) {
            $user = Auth::user();
            if ($user && isset($user->tenant_id) && $user->tenant_id) {
                $model->tenant_id = $user->tenant_id;
            }
        });
    }
}
