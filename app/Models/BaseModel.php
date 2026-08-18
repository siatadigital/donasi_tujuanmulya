<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    public function scopeLatest($query, $field = null)
    {
        return $query->orderBy($field ?: 'created_at', 'desc');
    }
}
