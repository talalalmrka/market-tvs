<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Screen;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Retrieve all models collection
 *
 * @return Illuminate\Support\Collection<int, class-string<Model>>
 */
if (! function_exists('all_models')) {
    function all_models()
    {
        return collect(File::files(app_path('Models')))
            ->map(fn ($file) => 'App\\Models\\'.$file->getFilenameWithoutExtension())
            ->filter(fn ($class) => class_exists($class) && is_subclass_of($class, Model::class))
            ->values();
    }
}
/**
 * Retrieve all models meta collection
 *
 * @return Illuminate\Support\Collection<int, array<string, mixed>>
 */
if (! function_exists('models')) {
    function models()
    {
        $models = all_models();
        $out = [];
        foreach ($models as $class) {
            $base = class_basename($class);
            $id = Str::snake($base);
            $plural = Str::plural($base);
            $table = app($class)->getModel()->getTable();
            $query = app($class)->query();
            $tr = "models.{$id}.select";
            $select_label = __($tr);
            $types = data_get(models_types(), $id);
            if (is_array($types) && ! empty($types)) {
                foreach ($types as $type) {
                    $tr = "models.{$type}.select";
                    $select_label = __($tr);
                    $query->where('type', $type);
                    $out[] = [
                        'id' => $type,
                        'name' => ucfirst($type),
                        'icon' => config("icons.{$type}"),
                        'plural' => ucfirst(Str::plural($type)),
                        'select_label' => $select_label,
                        'table' => $table,
                        'morph' => $class,
                        'args' => [
                            'type' => $type,
                        ],
                        'search_cols' => model_search_cols($type),
                        'query' => $query,
                    ];
                }
            } else {
                $out[] = [
                    'id' => $id,
                    'name' => $base,
                    'icon' => config("icons.{$id}"),
                    'select_label' => $select_label,
                    'plural' => $plural,
                    'select_label' => $select_label,
                    'table' => $table,
                    'morph' => $class,
                    'args' => [],
                    'search_cols' => model_search_cols($id),
                    'query' => $query,
                ];
            }
        }

        return collect($out);
    }
}

/**
 * Retrieve models with method
 *
 * @param  string  $method
 * @return Illuminate\Support\Collection<int, class-string<Model>>
 */
if (! function_exists('all_models_with')) {
    function all_models_with(string ...$methods)
    {
        return all_models()
            ->filter(function ($class) use ($methods) {
                foreach ($methods as $method) {
                    if (! method_exists($class, $method)) {
                        return false;
                    }
                }

                return true;
            })
            ->values();
    }
}

/**
 * Retrieve models with method meta
 *
 * @param  string  ...$methods
 * @return Illuminate\Support\Collection<int, class-string<Model>>
 */
if (! function_exists('models_with')) {
    function models_with(...$methods)
    {
        return models()
            ->filter(function ($item) use ($methods) {
                $class = data_get($item, 'morph');
                if (empty($class)) {
                    return false;
                }
                foreach ($methods as $method) {
                    if (! method_exists($class, $method)) {
                        return false;
                    }
                }

                return true;
            })->values();
    }
}
/**
 * Models data
 *
 * @return Collection
 */
/*if (!function_exists('models')) {
    function models()
    {
        return collect([
            [
                'id' => 'page',
                'name' => __('Page'),
                'plural' => __('Pages'),
                'icon' => 'bi-file-earmark-text',
                'select_label' => __('Select page'),
                'value' => 'page',
                'query' => Post::type('page'),
                'morph' => 'App\Models\Post',
                'args' => [
                    'type' => 'page',
                ],
                'search_cols' => [
                    'name',
                    'slug',
                    'content',
                ],
            ],
            [
                'id' => 'post',
                'name' => __('Post'),
                'plural' => __('Posts'),
                'icon' => 'bi-newspaper',
                'select_label' => __('Select post'),
                'query' => Post::type('post'),
                'morph' => 'App\Models\Post',
                'args' => [
                    'type' => 'post',
                ],
                'search_cols' => [
                    'name',
                    'slug',
                    'content',
                ],
            ],
            [
                'id' => 'category',
                'name' => __('Category'),
                'plural' => __('Categories'),
                'icon' => 'bi-folder',
                'select_label' => __('Select category'),
                'query' => Category::type('category'),
                'morph' => 'App\Models\Category',
                'args' => [
                    'type' => 'category',
                ],
                'search_cols' => [
                    'name',
                    'slug',
                    'description',
                ],
            ],
            [
                'id' => 'tag',
                'name' => __('Tag'),
                'plural' => __('Tags'),
                'icon' => 'bi-tag',
                'select_label' => __('Select tag'),
                'query' => Category::type('tag'),
                'morph' => 'App\Models\Category',
                'args' => [
                    'type' => 'tag',
                ],
                'search_cols' => [
                    'name',
                    'slug',
                    'description',
                ],
            ],
            [
                'id' => 'user',
                'name' => __('User'),
                'plural' => __('Users'),
                'icon' => 'bi-person',
                'select_label' => __('Select user'),
                'query' => User::query(),
                'morph' => 'App\Models\User',
                'search_cols' => [
                    'name',
                    'email',
                ],
            ],
            [
                'id' => 'screen',
                'name' => __('Screen'),
                'plural' => __('Screens'),
                'icon' => 'bi-tv',
                'select_label' => __('Select screen'),
                'query' => Screen::query(),
                'morph' => 'App\Models\Screen',
                'search_cols' => [
                    'name',
                    'slug',
                    'description',
                ],
            ],
        ]);
    }
}*/

/**
 * Get model morphs
 *
 * @return array<string>
 */
if (! function_exists('model_morphs')) {
    function model_morphs(): array
    {
        return models()->map(fn ($model) => data_get($model, 'morph'))->toArray();
    }
}

/**
 * Get model type options
 *
 * @return array<string, string>
 */
if (! function_exists('model_type_options')) {
    function model_type_options(): array
    {
        return models()->map(fn ($model) => [
            'label' => data_get($model, 'name'),
            'value' => data_get($model, 'id'),
        ])->toArray();
    }
}

/**
 * Get model type values
 *
 * @return array<string>
 */
if (! function_exists('model_type_values')) {
    function model_type_values(): array
    {
        return Arr::map(model_type_options(), fn ($option) => data_get($option, 'value'));
    }
}

/**
 * Retrieve the model data by id
 *
 * @param  string  $id
 * @return mixed|null
 */
if (! function_exists('model_data')) {
    function model_data(string $id)
    {
        return models()->firstWhere('id', $id);
    }
}

/**
 * Retrieve the model property value by id
 *
 * @param  string  $id
 * @return mixed|null
 */
if (! function_exists('model_property')) {
    function model_property(string $id, string $property, $default = null)
    {
        return data_get(model_data($id), $property, $default);
    }
}

/**
 * Retrieve all models meta collection
 *
 * @return array<string, mixed>>
 */
if (! function_exists('models_types')) {
    function models_types()
    {
        return [
            'post' => [
                'page',
                'post',
            ],
            'category' => [
                'category',
                'tag',
            ],
        ];
    }
}

/**
 * Retrieve model search cols
 *
 * @param  string  $id
 * @return array
 */
if (! function_exists('model_search_cols')) {
    function model_search_cols(string $id)
    {
        $searchCols = [
            'category' => [
                'name',
                'slug',
                'description',
            ],
            'post' => [
                'name',
                'slug',
                'content',
            ],
            'page' => [
                'name',
                'slug',
                'content',
            ],
            'user' => [
                'name',
                'email',
            ],
            'screen' => [
                'name',
                'slug',
                'description',
            ],
        ];

        return data_get($searchCols, $id);
    }
}
