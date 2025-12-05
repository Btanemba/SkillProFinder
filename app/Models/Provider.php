<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends User
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'users';

    protected static function booted()
    {
        parent::booted();
        
        static::addGlobalScope('provider', function ($query) {
            $query->where('user_type', 'provider');
        });
    }
}
