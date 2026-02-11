<?php

namespace App\Traits;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait WithDate
{
    public function date(): Attribute
    {
        $format = get_option('date_format', 'd M, Y');
        return Attribute::get(fn() => $this->created_at?->format($format));
    }
    public function dateAgo(): Attribute
    {
        return Attribute::get(fn() => $this->created_at?->diffForHumans(null, CarbonInterface::DIFF_ABSOLUTE, true));
    }
    public function updateDate(): Attribute
    {
        $format = get_option('date_format', 'd M, Y');
        return Attribute::get(fn() => $this->updated_at?->format($format));
    }
    public function updateDateAgo(): Attribute
    {
        return Attribute::get(fn() => $this->updated_at?->diffForHumans(null, CarbonInterface::DIFF_ABSOLUTE, true));
    }
}
