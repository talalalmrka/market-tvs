<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait WithSeo
{
    public function seoTitle(): Attribute
    {
        return Attribute::get(function () {
            if (instance_quote($this)) {
                $seo_title = $this->getExcerpt(35, '', true);
                return $this->author ? $this->author_name . " - " . $seo_title : $seo_title;
            }
            if (instance_category($this)) {
                return $this->description;
            }
            // return $this->getMeta('seo_title', $this->name);
            $seo_title = $this->getMeta('seo_title');
            return !empty($seo_title) ? $seo_title : $this->name;
        });
    }
    public function seoDescription(): Attribute
    {
        return Attribute::get(function () {
            $seo_description = $this->getMeta('seo_description');
            if (instance_page($this) && front_page() && front_page()->id === $this->id) {
                return !empty($seo_description) ? $seo_description : config('app.description');
            }
            return !empty($seo_description) ? $seo_description : $this->getExcerpt(160, '', true);
        });
    }
}
