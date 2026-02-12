<?php

namespace App\Models;

use App\Traits\WithActive;
use App\Traits\WithDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    /** @use HasFactory<\Database\Factories\TimeSlotFactory> */
    use HasFactory,
        WithActive,
        WithDate;

    protected $fillable = [
        'screen_id',
        'name',
        'start_time',
        'end_time',
        'duration',
        'is_active'
    ];

    protected $with = [
        'slides'
    ];

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }


    public function slides()
    {
        return $this->hasMany(Slide::class)->orderBy('order');
    }

    protected function startTimeFormatted(): Attribute
    {
        return Attribute::make(get: fn() => $this->start_time ? Carbon::parse($this->start_time)->format('h:i A') : null);
    }

    protected function endTimeFormatted(): Attribute
    {
        return Attribute::make(get: fn() => $this->end_time ? Carbon::parse($this->end_time)->format('h:i A') : null);
    }
}
