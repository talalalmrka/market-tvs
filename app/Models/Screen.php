<?php

namespace App\Models;

use App\Traits\HasUser;
use App\Traits\WithActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Screen extends Model
{
    /** @use HasFactory<\Database\Factories\ScreenFactory> */
    use HasFactory,
        WithActive,
        HasUser;
    protected $fillable = [
        'user_id',
        'name',
        'uuid',
        'is_active'
    ];

    protected $with = [
        'timeSlots'
    ];


    protected static function booted()
    {
        static::creating(fn($screen) => $screen->uuid = Str::uuid());
    }
    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }
}
