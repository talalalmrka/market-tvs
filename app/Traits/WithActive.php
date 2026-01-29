<?php

namespace App\Traits;



trait WithActive
{
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->get();
    }
}
