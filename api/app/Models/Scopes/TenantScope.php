<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Don't apply tenant scope to User model to avoid authentication issues
        if ($model instanceof \App\Models\User) {
            return;
        }

        if (Auth::check() && Auth::user()->tenant_id) {
            $builder->where($model->getTable().'.tenant_id', Auth::user()->tenant_id);
        }
    }
}
