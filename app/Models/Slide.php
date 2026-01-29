<?php

namespace App\Models;

use App\Traits\WithActive;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Slide extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\SlideFactory> */
    use HasFactory,
        InteractsWithMedia,
        WithActive;

    protected $fillable = [
        'time_slot_id',
        'name',
        'duration',
        'transition',
        'order',
        'is_active'
    ];

    protected $appends = [
        'url'
    ];

    /* protected $with = [
        'media'
    ]; */

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file');
    }

    public function url(): Attribute
    {
        return Attribute::get(fn() => $this->getFirstMediaUrl('file'));
    }
}
