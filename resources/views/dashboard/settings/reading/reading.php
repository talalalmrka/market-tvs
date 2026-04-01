<?php

use App\Livewire\Components\SettingsPage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Reading settings')] class extends SettingsPage
{
    public $front_type;
    public $front_page;
    public $posts_page;
    public $posts_per_page;
    public $disable_search_engines;
    public function prefix()
    {
        return 'reading';
    }
    public function rules()
    {
        return [
            'front_type' => ['required', 'string', Rule::in(front_types())],
            'front_page' => [
                'nullable',
                'required_if:front_type,page',
                Rule::exists('posts', 'id')->where('type', 'page'),
            ],
            'posts_page' => [
                'nullable',
                'required_if:front_type,page',
                Rule::exists('posts', 'id')->where('type', 'page'),
            ],
            'posts_per_page' => ['required', 'numeric', 'min:5', 'max:50'],
            'disable_search_engines' => ['nullable', 'boolean'],
        ];
    }
};
