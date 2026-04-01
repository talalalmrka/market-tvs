<?php

use App\Livewire\Components\DashboardPage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

new #[Title('Models')] class extends DashboardPage
{
    /*
    * @return Illuminate\Support\Collection
    */
    #[Computed()]
    public function items()
    {
        return collect([
            [
                'label' => 'all_models()',
                'value' => all_models(),
            ],
            [
                'label' => "all_models_with('permalink')",
                'value' => all_models_with('permalink'),
            ],
            [
                'label' => "all_models_with('permalink', 'scopeWithSlug')",
                'value' => all_models_with('permalink', 'scopeWithSlug'),
            ],
            [
                'label' => 'models()',
                'value' => models(),
            ],
            [
                'label' => "models_with('permalink')",
                'value' => models_with('permalink'),
            ],
            [
                'label' => "models_with('permalink', 'scopeWithSlug')",
                'value' => models_with('permalink', 'scopeWithSlug'),
            ],
            [
                'label' => "model_property('post', 'search_cols')",
                'value' => model_property('post', 'search_cols'),
            ],
            [
                'label' => "model_property('post', 'morph')",
                'value' => model_property('post', 'morph'),
            ],
        ]);
    }
};
