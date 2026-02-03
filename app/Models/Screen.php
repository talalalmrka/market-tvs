<?php

namespace App\Models;

use App\Traits\HasSlug;
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
        HasUser,
        HasSlug;

    protected $fillable = [
        'user_id',
        'name',
        'uuid',
        'slug',
        'is_active'
    ];

    protected $with = [
        'timeSlots'
    ];

    protected static function booted()
    {
        static::creating(function ($screen) {
            $screen->uuid = Str::uuid();
            if (empty($screen->slug)) {
                $screen->slug = self::generateSlug($screen->name);
            }
        });
        static::updating(function ($screen) {
            if (empty($screen->slug)) {
                $screen->slug = self::generateSlug($screen->name);
            }
        });
    }
    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }
}
