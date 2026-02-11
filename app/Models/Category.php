<?php

namespace App\Models;

use App\Models\Post;
use App\Traits\HasMeta;
use App\Traits\HasNextPrev;
use App\Traits\HasThumbnail;
use App\Traits\WithDate;
use App\Traits\WithEditUrl;
use App\Traits\WithExcerpt;
use App\Traits\WithPermalink;
use App\Traits\WithSeo;
use App\Traits\WithShare;
use App\Traits\WithViews;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory,
        InteractsWithMedia,
        HasThumbnail,
        HasMeta,
        WithDate,
        WithEditUrl,
        WithPermalink,
        WithDate,
        WithSeo,
        WithViews,
        WithExcerpt,
        HasNextPrev,
        WithShare;
    protected $fillable = [
        'name',
        'slug',
        'type',
        'parent_id',
        'description',
    ];
    protected $appends = [
        'permalink',
        'thumbnails',
        'thumbnail',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = self::generateSlug($category->name, $category->type);
            }
        });

        static::updating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = self::generateSlug($category->name, $category->type);
            }
        });
    }

    public static function generateSlug($name, $type, $separator = '-', $language = 'en', $dictionary = ['@' => 'at']): string
    {
        $slug = Str::slug($name, $separator, $language, $dictionary);
        $originalSlug = $slug;
        $count = 1;
        while (self::where('slug', $slug)->where('type', $type)->exists()) {
            $slug = $originalSlug . $separator . $count++;
        }
        return $slug;
    }
    public function scopeSlug($query, $slug, $type)
    {
        return $query->where('slug', $slug)->where('type', $type)->first();
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function hasParent(): Attribute
    {
        return Attribute::get(fn() => $this->parent instanceof Category);
    }
    public function parentName(): Attribute
    {
        return Attribute::get(fn() => $this->parent?->name);
    }
    public function parentPermalink(): Attribute
    {
        return Attribute::get(fn() => $this->parent?->permalink);
    }
    public function parents()
    {
        $parents = collect();
        $category = $this->parent;
        while ($category) {
            $parents->push($category);
            $category = $category->parent;
        }
        return $parents;
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('id', 'asc');
    }
    public function posts()
    {
        return $this->morphedByMany(
            Post::class,
            'model',
            'model_category'
        );
    }
    public function postsCount(): Attribute
    {
        return Attribute::get(fn() => $this->posts()->status('publish')->count());
    }
    public function postsCountFormatted(): Attribute
    {
        return Attribute::get(fn() => number_format($this->posts_count));
    }
    public function postsPermalink(): Attribute
    {
        return Attribute::get(fn() => route('category.posts', $this));
    }
    public function scopeHasPosts($query)
    {
        return $query->type('category')->whereHas('posts', function ($query) {
            $query->where('type', 'post')->where('status', 'publish');
        });
    }
    public function screens()
    {
        return $this->morphedByMany(
            Screen::class,
            'model',
            'model_category'
        );
    }
    public function screensCount(): Attribute
    {
        return Attribute::get(fn() => $this->books()->status('publish')->count());
    }
    public function screensCountFormatted(): Attribute
    {
        return Attribute::get(fn() => number_format($this->books_count));
    }

    public function scopeTop($query)
    {
        return $query->where('parent_id', null);
    }
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%")
            ->orWhere('description', 'like', "%{$term}%");
    }
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
    public function getThumbnailFallbackUrlAttribute()
    {
        return asset('assets/images/category.svg');
    }
    public function registerMediaCollections(): void
    {
        $this->registerThumbnail();
    }
    public function getPathAttribute()
    {
        $segments = [];
        $category = $this;

        while ($category) {
            $segments[] = $category->slug;
            $category = $category->parent;
        }

        return implode('/', array_reverse($segments));
    }
    public function parentsCount()
    {
        $count = 0;
        $category = $this;
        while ($category) {
            $category = $category->parent;
            if ($category) {
                $count++;
            }
        }
        return $count;
    }
    public function getLabelAttribute()
    {
        return str_repeat('-', $this->parentsCount()) . $this->name;
    }

    public function scopeCategory($query)
    {
        return $query->where('type', 'category');
    }
    public function scopeTag($query)
    {
        return $query->where('type', 'tag');
    }

    public function hasAnyChild($categories)
    {
        $categoryIds = $this->resolveCategoryIds($categories);
        foreach ($categoryIds as $categoryId) {
            if ($this->children()->where('id', $categoryId)->exists()) {
                return true;
            }
            foreach ($this->children as $child) {
                if ($child->hasAnyChild($categoryId)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function resolveCategoryIds($categories): array
    {
        if ($categories instanceof Collection) {
            return $categories->pluck('id')->all();
        }

        if ($categories instanceof Category) {
            return [$categories->id];
        }

        if (is_array($categories)) {
            $categories = array_filter(Arr::flatten($categories));
            return Category::whereIn('slug', $categories)
                ->orWhereIn('id', $categories)
                ->pluck('id')
                ->all();
        }

        return [Category::where('slug', $categories)->orWhere('id', $categories)->value('id')];
    }

    public function related()
    {
        $table = $this->getTable();
        $related_count = get_option("related_{$table}_count", get_option('related_posts_count', 5));
        return self::query()->type($this->type)->take($related_count)->get();
    }

    public function title(): Attribute
    {
        return Attribute::get(function () {
            $title = get_option('category_title');
            if ($this->has_quotes && !$this->has_books) {
                $title = get_option('category_quotes_title');
            }
            if ($this->has_books && !$this->has_quotes) {
                $title = get_option('category_books_title');
            }
            return Str::replace(["{name}", "{quotes_count}", "{books_count}"], [$this->name, $this->quotes_count, $this->books_count], $title);
        });
    }
    public function seoTitle(): Attribute
    {
        return Attribute::get(function () {
            $seo_title = $this->getMeta('seo_title') ?? get_option('category_seo_title') ?? '';
            if ($this->has_quotes && !$this->has_books) {
                $seo_title = get_option('category_quotes_seo_title');
            }
            if ($this->has_books && !$this->has_quotes) {
                $seo_title = get_option('category_books_seo_title');
            }
            return Str::replace(["{name}", "{quotes_count}", "{books_count}"], [$this->name, $this->quotes_count, $this->books_count], $seo_title);
        });
    }
    public function seoDescription(): Attribute
    {
        return Attribute::get(function () {
            $seo_description = $this->getMeta('seo_description') ?? get_option('category_seo_description') ?? $this->getExcerpt(35, '', true);
            if ($this->has_quotes && !$this->has_books) {
                $seo_description = get_option('category_quotes_seo_description');
            }
            if ($this->has_books && !$this->has_quotes) {
                $seo_description = get_option('category_books_seo_description');
            }
            return Str::replace(["{name}", "{quotes_count}", "{books_count}"], [$this->name, $this->quotes_count, $this->books_count], $seo_description);
        });
    }
}
