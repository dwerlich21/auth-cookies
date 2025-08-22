<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'city_id',
        'logo',
        'primary_color',
        'secondary_color',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];



    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
