<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions');
    }
}
