<?php

use App\Livewire\Components\SettingsPage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

new #[Title('Typography settings')] class extends SettingsPage
{
    public $font_family;

    public $font_smoothing;

    public $font_size;

    public $font_weight;

    public $font_style;

    public $font_display;

    public function prefix()
    {
        return 'typography';
    }
    public function rules()
    {
        return [
            'font_family' => ['nullable', Rule::in(font_families())],
            'font_smoothing' => ['nullable', Rule::in(font_smoothings())],
            'font_size' => ['nullable', Rule::in(font_sizes())],
            'font_weight' => ['nullable', Rule::in(font_weights())],
            'font_style' => ['nullable', Rule::in(font_styles())],
            'font_display' => ['nullable', Rule::in(font_displays())],
        ];
    }

    #[Computed()]
    public function previewClasses()
    {
        return css_classes([
            "font-$this->font_family" => $this->font_family,
            $this->font_smoothing => $this->font_smoothing,
            "text-$this->font_size" => $this->font_size,
        ]);
    }

    #[Computed()]
    public function previewStyles()
    {
        return css_styles([
            "font-weight: $this->font_weight",
            "font-style: $this->font_style",
        ]);
    }

    #[Computed()]
    public function previewTitle()
    {
        return __('Preview (:family)', ['family' => Str::title($this->font_family)]);
    }
};
