<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Traits\HasUser;
use App\Traits\WithActive;
use App\Traits\WithDate;
use App\Traits\HasMeta;
use App\Traits\WithEditUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Screen extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ScreenFactory> */
    use HasFactory,
        InteractsWithMedia,
        WithActive,
        HasUser,
        HasSlug,
        WithDate,
        HasMeta,
        WithEditUrl;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'slot_duration',
        'controls_duration',
        'is_active',
        'description',
    ];

    protected $with = [
        'timeSlots'
    ];
    protected $appends = [
        'permalink',
        'api_url',
    ];

    /*protected static function booted()
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
    }*/
    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function permalink(): Attribute
    {
        return Attribute::get(fn() => !empty($this->id) ? route('screen', $this) : '');
    }
    public function apiUrl(): Attribute
    {
        return Attribute::get(fn() => !empty($this->id) ? route('api.screen', $this) : '');
    }
}
