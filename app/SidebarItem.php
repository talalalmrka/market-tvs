<?php

namespace App;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Livewire\Wireable;

class SidebarItem implements Arrayable, Wireable
{
    public function __construct(
        public ?string $id = null,
        public string $type = 'link',
        public ?string $label = null,
        public ?string $href = null,
        public ?string $icon = null,
        public bool $navigate = true,
        public bool $open = false,
        public bool $permission = true,
        public array $atts = [
            'wire:current' => 'active',
        ],
        public ?Collection $items = null,
    ) {}

    /**
     * All items
     *
     * @return Illuminate\Support\Collection
     */
    public static function all(): Collection
    {
        return collect([
            self::make([
                'type' => 'link',
                'label' => __('Dashboard'),
                'icon' => 'bi-speedometer',
                'href' => route('dashboard'),
            ]),
            self::make([
                'type' => 'link',
                'label' => __('Users'),
                'icon' => 'bi-people',
                'href' => route('dashboard.users'),
                'permission' => current_user()?->can('manage_users'),
            ]),
            self::make([
                'type' => 'collapse',
                'label' => __('Roles & Permissions'),
                'icon' => 'bi-person-fill-lock',
                'permission' => current_user()?->can('manage_roles') || current_user()?->can('manage_permissions'),
                'open' => request()->routeIs([
                    'dashboard.roles',
                    'dashboard.roles.*',
                    'dashboard.permissions',
                    'dashboard.permissions.*',
                ]),
                'items' => [
                    self::make([
                        'type' => 'link',
                        'label' => __('Roles'),
                        'icon' => 'bi-person-gear',
                        'href' => route('dashboard.roles'),
                        'permission' => current_user()?->can('manage_roles'),
                    ]),
                    self::make([
                        'type' => 'link',
                        'label' => __('Permissions'),
                        'icon' => 'bi-key-fill',
                        'href' => route('dashboard.permissions'),
                        'permission' => current_user()?->can('manage_permissions'),
                    ]),
                ],
            ]),
            self::make([
                'type' => 'link',
                'label' => __('Screens'),
                'icon' => 'bi-tv',
                'href' => route('dashboard.screens'),
                'permission' => current_user()?->can('manage_screens'),
            ]),
            self::make([
                'type' => 'link',
                'label' => __('Menus'),
                'icon' => 'bi-list-ul',
                'href' => route('dashboard.menus'),
                'permission' => current_user()?->can('manage_menus'),
            ]),
            self::make([
                'type' => 'link',
                'label' => __('Media'),
                'icon' => 'bi-image',
                'href' => route('dashboard.media'),
                'permission' => current_user()?->can('manage_media'),
            ]),
            self::make([
                'type' => 'collapse',
                'label' => __('Crud'),
                'icon' => 'bi-table',
                'open' => request()->routeIs(['dashboard.crud', 'dashboard.crud.*']),
                'permission' => current_user()?->hasRole('admin'),
                'items' => models()->map(function ($model) {
                    $table = data_get($model, 'table');
                    $href = route('dashboard.crud.item', $table);
                    $permission = current_user_can("manage_{$table}");

                    return self::make([
                        'type' => 'link',
                        'label' => data_get($model, 'plural'),
                        'icon' => data_get($model, 'icon'),
                        'href' => $href,
                        'permission' => $permission,
                    ]);
                }),
            ]),
            self::make([
                'type' => 'link',
                'label' => __('Translations'),
                'icon' => 'bi-translate',
                'href' => route('dashboard.translations'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'type' => 'collapse',
                'label' => __('Ui Components'),
                'icon' => 'bi-grid',
                'open' => request()->routeIs(['dashboard.ui', 'dashboard.ui.*']),
                'items' => ui_pages()->map(function ($page) {
                    $route = data_get($page, 'route');
                    $label = data_get($page, 'label');
                    $icon = data_get($page, 'icon');

                    return self::make([
                        'type' => 'link',
                        'label' => $label,
                        'icon' => $icon,
                        'href' => route($route),
                    ]);
                }),
            ]),
            self::make([
                'type' => 'link',
                'label' => __('Terminal'),
                'icon' => 'bi-terminal',
                'href' => route('dashboard.terminal'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'type' => 'collapse',
                'label' => __('Settings'),
                'icon' => 'bi-gear-wide-connected',
                'permission' => current_user()?->can('manage_settings'),
                'open' => request()->routeIs([
                    'dashboard.settings',
                    'dashboard.settings.*',
                ]),
                'items' => self::settings(),
            ]),
        ]);
    }

    /**
     * get settings items
     *
     * @return array
     */
    public static function settings()
    {
        return collect([
            self::make([
                'id' => 'index',
                'type' => 'link',
                'label' => __('Manage Settings'),
                'icon' => 'bi-gear-wide-connected',
                'href' => url('dashboard/settings'),
                'permission' => current_user()?->can('manage_settings'),
                'atts' => [
                    'wire:current.exact' => 'active',
                ],
            ]),
            self::make([
                'id' => 'app',
                'type' => 'link',
                'label' => __('General Settings'),
                'icon' => 'bi-globe',
                'href' => url('dashboard/settings/app'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'membership',
                'type' => 'link',
                'label' => __('Membership settings'),
                'icon' => 'bi-person-gear',
                'href' => url('dashboard/settings/membership'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'reading',
                'type' => 'link',
                'label' => __('Reading settings'),
                'icon' => 'bi-book',
                'href' => url('dashboard/settings/reading'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'ads',
                'type' => 'link',
                'label' => __('Ads settings'),
                'icon' => 'bi-megaphone',
                'href' => url('dashboard/settings/ads'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'design',
                'type' => 'link',
                'label' => __('Design settings'),
                'icon' => 'bi-window',
                'href' => url('dashboard/settings/design'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'type' => 'collapse',
                'label' => __('Typography'),
                'icon' => 'bi-type',
                'permission' => current_user()?->can('manage_fonts'),
                'open' => request()->routeIs([
                    'dashboard.settings.fonts',
                    'dashboard.settings.typography',
                ]),
                'items' => [
                    self::make([
                        'id' => 'fonts',
                        'type' => 'link',
                        'label' => __('Custom fonts'),
                        'icon' => 'bi-fonts',
                        'href' => url('dashboard/settings/fonts'),
                        'permission' => current_user()?->can('manage_fonts'),
                    ]),
                    self::make([
                        'id' => 'typography',
                        'type' => 'link',
                        'label' => __('Settings'),
                        'icon' => 'bi-type',
                        'href' => url('dashboard/settings/typography'),
                        'permission' => current_user()?->can('manage_fonts'),
                    ]),
                ],
            ]),
            self::make([
                'id' => 'auth',
                'type' => 'link',
                'label' => __('Auth settings'),
                'icon' => 'bi-key',
                'href' => url('dashboard/settings/auth'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'broadcasting',
                'type' => 'link',
                'label' => __('Bradcasting settings'),
                'icon' => 'bi-send',
                'href' => url('dashboard/settings/broadcasting'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'cache',
                'type' => 'link',
                'label' => __('Cache settings'),
                'icon' => 'bi-clock',
                'href' => url('dashboard/settings/cache'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'database',
                'type' => 'link',
                'label' => __('Database settings'),
                'icon' => 'bi-database',
                'href' => url('dashboard/settings/database'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'filesystems',
                'type' => 'link',
                'label' => __('Filesystems settings'),
                'icon' => 'bi-file',
                'href' => url('dashboard/settings/filesystems'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'fortify',
                'label' => __('Fortify settings'),
                'icon' => 'bi-person-gear',
                'href' => url('dashboard/settings/fortify'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'livewire',
                'label' => __('Livewire settings'),
                'icon' => 'bi-lightning',
                'href' => url('dashboard/settings/livewire'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'logging',
                'label' => __('Logging settings'),
                'icon' => 'bi-list',
                'href' => url('dashboard/settings/logging'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'mail',
                'type' => 'link',
                'label' => __('Mail settings'),
                'icon' => 'bi-envelope',
                'href' => url('dashboard/settings/mail'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'media-library',
                'label' => __('Media library settings'),
                'icon' => 'bi-image',
                'href' => url('dashboard/settings/media-library'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'permission',
                'label' => __('Permission settings'),
                'icon' => 'bi-key',
                'href' => url('dashboard/settings/permission'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'queue',
                'label' => __('Queue settings'),
                'icon' => 'bi-calendar',
                'href' => url('dashboard/settings/queue'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'reverb',
                'label' => __('Reverb settings'),
                'icon' => 'bi-send',
                'href' => url('dashboard/settings/reverb'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'santcum',
                'label' => __('Sanctum settings'),
                'icon' => 'bi-link',
                'href' => url('dashboard/settings/sanctum'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'services',
                'label' => __('Services settings'),
                'icon' => 'bi-server',
                'href' => url('dashboard/settings/services'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'session',
                'label' => __('Session settings'),
                'icon' => 'bi-database',
                'href' => url('dashboard/settings/session'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'id' => 'tbumbnail',
                'label' => __('Thumbnail settings'),
                'icon' => 'bi-image',
                'href' => url('dashboard/settings/thumbnail'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'type' => 'link',
                'label' => __('Environment variables'),
                'icon' => 'bi-gear',
                'href' => url('dashboard/settings/env'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
            self::make([
                'type' => 'link',
                'label' => __('Import/Export settings'),
                'icon' => 'bi-box-arrow-in-down',
                'href' => url('dashboard/settings/import'),
                'permission' => current_user()?->can('manage_settings'),
            ]),
        ]);
    }

    /**
     * get route data
     *
     * @return array
     */
    public function getRouteData()
    {
        $uri = $this->id === 'index'
            ? '/'
            : "/{$this->id}";
        $view = "dashboard::settings.{$this->id}";
        $name = $this->id === 'index'
            ? 'dashboard.settings'
            : "dashboard.settings.{$this->id}";
        $viewExists = view()->exists("{$view}.{$this->id}");

        return [
            'uri' => $uri,
            'name' => $name,
            'view' => $view,
            'viewExists' => $viewExists,
            'route' => route_has($name) ? route($name) : null,
        ];
    }

    public static function settingsRoutes()
    {
        return self::flatSettings()->map(fn (SidebarItem $item) => $item->getRouteData());
    }

    public static function registerSettingsRoutes()
    {
        self::flatSettings()->each(function (SidebarItem $item) {
            $uri = $item->id === 'index'
                ? '/'
                : "/{$item->id}";
            $view = "dashboard::settings.{$item->id}";
            $name = $item->id === 'index'
                ? 'dashboard.settings'
                : "dashboard.settings.{$item->id}";
            if (view()->exists("{$view}.{$item->id}")) {
                Route::livewire($uri, $view)->name($name);
            }
        });
    }

    /**
     * get settings items
     *
     * @param  Illuminate\Support\Collection|null  $settings
     * @param  Illuminate\Support\Collection|null  $flat
     * @return Illuminate\Support\Collection
     */
    public static function flatSettings(?Collection $settings = null, ?Collection &$flat = null)
    {
        if (empty($settings)) {
            $settings = self::settings();
        }
        if (empty($flat)) {
            $flat = collect();
        }
        collect($settings)->each(function (SidebarItem $item) use (&$flat) {
            $items = $item->items;
            $item->items = null;
            $flat->add($item);
            if ($item->type === 'collapse' && $items->isNotEmpty()) {
                self::flatSettings($items, $flat);
            }
        });

        return $flat;
    }
    /* public static function routes()
    {
        return self::flat()->map(function (SidebarItem $item) {
            $id = $item->id;
            $path = Str::replace('dashboard.', '', $id);
            $uri = Str::replace('.', '/', $path);
            $view = "dashboard::{$path}";
            $name = $id;
            return [
                'id' => $id,
                'uri' => $uri,
                'view' => $view,
                'name' => $name,
            ];
        });
    }
    public static function registerRoutes()
    {
        Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'verified']], function () {
            self::flat()->each(function (SidebarItem $item) {
                $id = $item->id;
                $path = Str::replace('dashboard.', '', $id);
                $uri = Str::replace('.', '/', $path);
                $view = "dashboard::{$path}";
                Route::livewire($uri, $view)->name($id);
            });
        });
    } */

    /**
     * flat items
     *
     * @param  Illuminate\Support\Collection|null  $flat
     * @return Illuminate\Support\Collection
     */
    public static function flat(?Collection $all = null, ?Collection &$flat = null)
    {
        if (empty($all)) {
            $all = self::all();
        }
        if (empty($flat)) {
            $flat = collect();
        }
        $all->each(function (SidebarItem $item) use (&$flat) {
            $items = $item->items;
            $item->items = null;
            $flat->add($item);
            if ($item->type === 'collapse' && $items->isNotEmpty()) {
                self::flat($items, $flat);
            }
        });

        return $flat;
    }

    /**
     * Search in items
     *
     * @param  string|null  $term
     * @return Illuminate\Support\Collection
     */
    public static function search($term)
    {
        return self::flat()
            ->filter(function (SidebarItem $item) use ($term) {
                return $item->type === 'link' && Str::contains(Str::lower($item->label), Str::lower($term));
            })->values();
    }

    public static function make($value): self
    {
        $id = data_get($value, 'id');
        $type = data_get($value, 'type', 'link');
        $label = data_get($value, 'label');
        $href = data_get($value, 'href');
        $icon = data_get($value, 'icon');
        // $navigate = boolval(data_get($value, 'navigate', true));
        $navigate = data_get($value, 'navigate', true);
        $open = boolval(data_get($value, 'open', false));
        $permission = boolval(data_get($value, 'permission', true));
        $atts = data_get($value, 'atts', [
            'wire:current' => 'active',
        ]);
        $items = data_get($value, 'items', []);
        if ($type === 'collapse') {
            if (is_array($items)) {
                $items = collect($items);
            } elseif ($items instanceof Collection) {
                $items = $items;
            } else {
                $items = collect();
            }
        } else {
            $items = null;
        }

        return new self(
            $id,
            $type,
            $label,
            $href,
            $icon,
            $navigate,
            $open,
            $permission,
            $atts,
            $items
        );
    }

    public static function fromLivewire($value): self
    {
        return self::make($value);
    }

    public function toLivewire(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $out = [
            'id' => $this->id,
            'type' => $this->type,
            'label' => $this->label,
            'href' => $this->href,
            'icon' => $this->icon,
            'navigate' => boolval($this->navigate),
            'open' => $this->open,
            'permission' => $this->permission,
            'atts' => $this->atts,
            'route' => $this->getRouteData(),
        ];
        if ($this->type === 'collapse') {
            $out['items'] = $this->items?->toArray();
        }

        return $out;
    }

    public function render($atts = [])
    {
        $this->atts = [
            ...$this->atts,
            ...$atts,
        ];

        return view('components.sidebar-item', ['item' => $this]);
    }

    public static function sidebar()
    {
        $ret = '';
        self::all()->each(function (SidebarItem $item) use (&$ret) {
            if ($item->permission) {
                $ret .= $item->render();
            }
        });

        return $ret;
    }
}
