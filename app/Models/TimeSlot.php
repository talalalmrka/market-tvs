<?php

namespace App\Models;

use App\Traits\WithActive;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    /** @use HasFactory<\Database\Factories\TimeSlotFactory> */
    use HasFactory, WithActive;
    protected $fillable = [
        'screen_id',
        'name',
        'start_time',
        'end_time',
        'slide_duration',
        'priority',
        'is_active'
    ];

    protected $with = [
        'slides'
    ];
    
    protected $appends = [
    	'file'
    ];
    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }


    public function slides()
    {
        return $this->hasMany(Slide::class)->orderBy('order');
    }
    
    public function file(): Attribute
    {
        return Attribute::get(fn() => null);
    }
}
