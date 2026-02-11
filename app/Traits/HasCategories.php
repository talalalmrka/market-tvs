<?php

namespace App\Traits;

use App\Models\Category;
use Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasCategories
{
    /**
     * Relationship with the Category model.
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'model', 'model_category');
    }

    public function getCategoryAttribute()
    {
        return $this->categories()->first();
    }

    /**
     * Scope the model query to certain categories only.
     *
     * @param  Builder  $query
     * @param  string|int|array|Category|Collection  $categories
     * @param  bool  $without  Determine if the query should exclude these categories.
     * @return Builder
     */
    public function scopeCategory(Builder $query, $categories, $without = false): Builder
    {
        $categoryIds = $this->resolveCategoryIds($categories);

        return $query->whereHas('categories', function ($query) use ($categoryIds, $without) {
            if ($without) {
                $query->whereNotIn('categories.id', $categoryIds);
            } else {
                $query->whereIn('categories.id', $categoryIds);
            }
        });
    }

    /**
     * Scope the model query has categories
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeCategorized($query)
    {
        return $query->whereHas('categories', function ($query) {
            $query->where('type', 'category');
        });
    }
    /**
     * Scope the model query has not categories
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeUnCategorized($query)
    {
        return $query->whereDoesntHave('categories', function ($query) {
            $query->where('type', 'category');
        });
    }

    /**
     * Assign the given categories to the model.
     *
     * @param  string|int|array|Category|Collection  ...$categories
     * @return $this
     */
    public function assignCategory(...$categories)
    {
        $categoryIds = $this->resolveCategoryIds($categories);
        $this->categories()->syncWithoutDetaching($categoryIds);

        return $this;
    }

    /**
     * Revoke the given category from the model.
     *
     * @param  string|int|Category  $category
     * @return $this
     */
    public function removeCategory($category)
    {
        $categoryId = $this->resolveCategoryId($category);
        if ($categoryId) {
            $this->categories()->detach($categoryId);
        }

        return $this;
    }

    /**
     * Sync the given categories, removing any that are not in the list.
     *
     * @param  string|int|array|Category|Collection  ...$categories
     * @return $this
     */
    public function syncCategories(...$categories)
    {
        $categoryIds = $this->resolveCategoryIds($categories);
        $this->categories()->sync($categoryIds);
        return $this;
    }

    /**
     * Determine if the model has (one of) the given categories.
     *
     * @param  string|int|Category  $category
     * @return bool
     */
    public function hasCategory($category): bool
    {
        $categoryId = $this->resolveCategoryId($category);

        return $categoryId ? $this->categories->contains('id', $categoryId) : false;
    }

    /**
     * Determine if the model has any of the given categories.
     *
     * @param  array|Collection  $categories
     * @return bool
     */
    public function hasAnyCategory(...$categories): bool
    {
        foreach ($categories as $category) {
            if ($this->hasCategory($category)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the model has all of the given categories.
     *
     * @param  array|Collection  $categories
     * @return bool
     */
    public function hasAllCategories($categories): bool
    {
        foreach ($categories as $category) {
            if (!$this->hasCategory($category)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the names of all assigned categories.
     *
     * @return Collection
     */
    public function getCategoryNames(): Collection
    {
        return $this->categories->pluck('name');
    }

    /**
     * Get the slugs of the categories assigned to the model.
     */
    public function getCategorySlugs(): Collection
    {
        return $this->categories->pluck('slug');
    }

    /**
     * Get the ids of the categories assigned to the model.
     */
    public function getCategoryIds(): Collection
    {
        return $this->categories->pluck('id');
    }

    /**
     * Resolve category(s) to IDs for handling category-related logic.
     *
     * @param  string|int|array|Category|Collection  $categories
     * @return array
     */
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

    /**
     * Resolve a single category to its ID.
     *
     * @param  string|int|Category  $category
     * @return int|null
     */
    protected function resolveCategoryId($category): ?int
    {
        if ($category instanceof Category) {
            return $category->id;
        }

        if (is_numeric($category)) {
            return $category;
        }

        return Category::where('slug', $category)->value('id');
    }

    /**
     * get the primary category name
     *
     * @return string|null
     */
    public function getCategoryNameAttribute()
    {
        return $this->category?->name;
    }

    /**
     * get category permalink
     *
     * @return string|null
     */
    public function getCategoryPermalinkAttribute()
    {
        return $this->category?->permalink;
    }

    /**
     * get categories links html
     *
     * @param array $options link options support class, title, atts default array('class' => 'link')
     * @return string
     */
    public function categoriesLinks($options = [], $type = 'category')
    {
        $default = [
            'class' => 'link',
        ];
        $options = array_merge($default, $options);
        $categories = $this->categories()->type($type)->get();
        return contents($categories->map(fn(Category $cat) => a([
            ...$options,
            ...[
                'href' => $cat->permalink,
                'label' => $cat->name,
            ]
        ]))->toArray());
        /* $ret = "";
        foreach ($this->categories()->where('type', $type) as $cat) {
            $ret .= a(array_merge($options, ['href' => $cat->permalink, 'label' => $cat->name]));
        }
        return $ret; */
    }

    /**
     * get categories names text
     *
     * @param string $seperator default (,)
     * @param string $placeholder what show if no categories default (-)
     * @return string
     */
    public function categoryNames($seperator = ',', $placeholder = '-')
    {
        $text = implode($seperator, $this->getCategoryNames()->toArray());
        return !empty(trim($text)) ? $text : $placeholder;
    }

    /**
     * is categories meta enabled in model single page
     * @return boolean
     */
    public function singleCategoriesEnabled()
    {
        return $this->categories()->count() && (bool) get_option(strtolower(class_basename($this)) . '_meta_categories');
    }
}
