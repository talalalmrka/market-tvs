<?php

namespace App\Models;

use App\Traits\HasMeta;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MenuItem extends Model
{
    /** @use HasFactory<\Database\Factories\MenuItemFactory> */
    use HasFactory, HasMeta;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'model_type',
        'model_id',
        'name',
        'icon',
        'order',
        // 'type',
        // 'page_id',
        // 'post_id',
        // 'category_id',
        'url',
        'class_name',
        'navigate',
        'new_tab',
    ];

    protected $with = [
        'children',
    ];

    protected $appends = [
        'type',
        'title',
        'parent_name',

    ];

    protected $hidden = [
        'itemable',
        'parent',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($menuItem) {
            if ($menuItem->order === null) {
                if ($menuItem->parent_id) {
                    $parent = static::find($menuItem->parent_id);
                    if ($parent) {
                        $menuItem->order = $parent->children()->count();
                    }
                } else {
                    if ($menuItem->menu_id) {
                        $menu = Menu::find($menuItem->menu_id);
                        if ($menu) {
                            $menuItem->order = $menu->items()->count();
                        }
                    }
                }
            }
        });
        static::updating(function ($menuItem) {
            /*if ($menuItem->isDirty('parent_id')) {
                // Reorder old siblings
                $oldParentId = $menuItem->getOriginal('parent_id');
                if ($oldParentId) {
                    $oldSiblings = static::where('parent_id', $oldParentId)->orderBy('order')->get();
                    foreach ($oldSiblings as $index => $sibling) {
                        $sibling->order = $index;
                        $sibling->saveQuietly();
                    }
                }

                // Reorder new siblings
                if ($menuItem->parent_id) {
                    $newSiblings = static::where('parent_id', $menuItem->parent_id)->orderBy('order')->get();
                    foreach ($newSiblings as $index => $sibling) {
                        $sibling->order = $index;
                        $sibling->saveQuietly();
                    }
                }
            }*/
        });
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function parentName(): Attribute
    {
        return Attribute::get(fn () => $this->parent?->name);
    }

    public function type(): Attribute
    {
        return Attribute::get(function () {
            if ($this->itemable) {
                $type = data_get($this->itemable, 'type');
                if ($type) {
                    return $type;
                }

                return Str::singular($this->itemable->getTable());
            } else {
                return 'custom';
            }
        });
    }

    public function itemable()
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    public function itemableName(): Attribute
    {
        return Attribute::get(fn () => $this->itemable?->name);
    }

    public function itemablePermalink(): Attribute
    {
        return Attribute::get(fn () => $this->itemable?->permalink);
    }

    public function itemableThumbnailUrl(): Attribute
    {
        return Attribute::get(fn () => $this->itemable?->getThumbnailUrl('xs'));
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    /*public function page()
    {
        return $this->belongsTo(Post::class);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }*/
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    public function isSubItem()
    {
        return ! empty($this->parent);
    }

    public function isPage()
    {
        // return $this->type === 'page';
        return $this->itemable instanceof Post && $this->itemable?->type === 'post';
    }

    public function isPost()
    {
        // return $this->type === 'post';
        return $this->itemable instanceof Post && $this->itemable?->type === 'page';
    }

    public function isCategory()
    {
        // return $this->type === 'category';
        return $this->itemable instanceof Category && $this->itemable?->type === 'category';
    }

    public function isTag()
    {
        // return $this->type === 'category';
        return $this->itemable instanceof Category && $this->itemable?->type === 'tag';
    }

    public function isCustom()
    {
        return $this->type == 'custom';
    }

    /* public function getParentNameAttribute()
    {
        return $this->parent?->name;
    } */
    /* public function getHrefAttribute()
    {
        return match ($this->type) {
            'page' => $this->page?->permalink,
            'post' => $this->post?->permalink,
            'category' => $this->category?->permalink,
            default => $this->url,
        };
    } */

    public function href(): Attribute
    {
        return Attribute::get(fn () => $this->itemable ? $this->itemable?->permalink : $this->url);
    }

    /* public function getTargetAttribute()
    {
        return $this->new_tab ? '_blank' : null;
    } */

    public function target(): Attribute
    {
        return Attribute::get(fn () => $this->new_tab ? '_blank' : null);
    }

    /* public function getTitleAttribute()
    {
        return $this->getMeta('title') ?? $this->name;
    } */

    public function title(): Attribute
    {
        return Attribute::get(fn () => $this->getMeta('title') ?? $this->name);
    }

    public function render()
    {
        return view('components.nav-menu-item', [
            'item' => $this,
        ]);
    }
}
