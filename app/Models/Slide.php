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
        'url',
        'type',
        'transition_class',
    ];

    protected $hidden = [
        'media'
    ];

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file')->singleFile();
    }

    public function url(): Attribute
    {
        return Attribute::get(fn() => $this->getFirstMediaUrl('file'));
    }

    public function type(): Attribute
    {
        return Attribute::get(fn() => $this->getFirstMedia('file')?->type);
    }
    public function transitionClass(): Attribute
    {
        return Attribute::get(function () {
            try {
                $transitions = slide_transitions();
                if ($this->transition === 'random') {
                    return collect($transitions)
                        ->except('random')
                        ->random();
                } else {
                    return $transitions[$this->transition];
                }
            } catch (\Exception $e) {
                return 'Error: ' . $e->getMessage();
            }
        });
    }
}
