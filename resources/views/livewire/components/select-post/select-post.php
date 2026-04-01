<?php

use App\Models\Post;
use App\Option;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Component;

new class extends Component
{
    public $id = '';

    public $type = 'post';

    public $label = null;

    public $icon = null;

    public $required = false;

    #[Modelable]
    public $value;

    public $error = null;

    public $info = null;

    public $placeholder = 'Select Post';

    public $notIn = null;

    public $search = '';

    public $searchCols = [
        'name',
        'slug',
        'content',
    ];

    public $limit = 10;

    public $class = null;

    public $size = null;

    public $container_class = null;

    public $dropdown_class = null;

    public function query()
    {
        $query = Post::type($this->type);
        if (! empty($this->search)) {
            $query->where(function ($q) {
                foreach ($this->searchCols as $col) {
                    $q->orWhere($col, 'like', "%{$this->search}%");
                }
            });
        }

        return $query;
    }

    public function optionLabel(Post $post)
    {
        $label = $post->name;

        return $label;
    }

    #[Computed()]
    public function options()
    {
        return $this->query()->limit($this->limit)->get()->map(fn (Post $post) => Option::make([
            'label' => $post->name,
            'value' => $post->id,
            'selected' => $post->id === $this->value,
        ]))->toArray();
    }

    #[Computed()]
    public function selectedLabel()
    {
        return $this->value ? Post::find($this->value)->name : $this->placeholder;
    }
};
