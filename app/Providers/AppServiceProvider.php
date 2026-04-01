<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Livewire\Livewire;
use Illuminate\Support\Facades\Blade;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerDirectives();
        $this->configureLayouts();
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(
            fn(): ?Password => app()->isProduction()
                ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
                : null
        );
    }

    protected function configureLayouts(): void
    {
        Livewire::listen('component.boot', function ($component) {

            // اسم الـ page component (مثلاً: dashboard.users)
            $name = $component->getName();
            dd($name);
            /* if (Str::startsWith($name, 'dashboard.')) {
                return 'layouts.dashboard';
            }

            return 'layouts.app'; */
        });
    }
    protected function registerDirectives()
    {
        Blade::directive('menu', function ($expression) {
            $parts = explode(',', $expression, 2);
            $position = isset($parts[0]) ? trim($parts[0]) : "'default'";
            $attributes = isset($parts[1]) ? trim($parts[1]) : '[]';
            return <<<PHP
            <?php
            navMenu({$position}, {$attributes});
            ?>
            PHP;
        });

        Blade::directive('ads', function (string $expression) {
            $parts = explode(',', $expression, 2);
            $position = isset($parts[0]) ? trim($parts[0]) : null;
            $attributes = isset($parts[1]) ? trim($parts[1]) : '[]';
            return <<<PHP
            <?php
            ads({$position});
            ?>
            PHP;
        });

        Blade::directive('atts', function ($expression) {
            return "<?php echo atts($expression); ?>";
        });

        Blade::directive('cssClasses', function ($expression) {
            return "<?php echo 'class=\"' . e(css_classes($expression)) . '\"'; ?>";
        });

        Blade::directive('customCode', function ($expression) {
            return <<<PHP
            <?php
            custom_code({$expression});
            ?>
            PHP;
        });

        Blade::directive('customCss', function ($expression) {
            return <<<PHP
            <?php
            custom_css();
            ?>
            PHP;
        });

        Blade::directive('customJs', function ($expression) {
            return <<<PHP
            <?php
            custom_js();
            ?>
            PHP;
        });

        Blade::directive('preCard', function ($expression) {
            $parts = explode(',', $expression, 2);
            $obj = isset($parts[0]) ? trim($parts[0]) : "'null'";
            $title = isset($parts[1]) ? trim($parts[1]) : "''";
            $className = isset($parts[2]) ? trim($parts[2]) : "'mt-6'";
            return <<<PHP
            <?php
            pre_card({$obj}, {$title}, {$className});
            ?>
            PHP;
        });

        Blade::directive('pre100', function ($expression) {
            $parts = explode(',', $expression, 2);
            $obj = isset($parts[0]) ? trim($parts[0]) : "'null'";
            $className = isset($parts[1]) ? trim($parts[1]) : "''";
            return <<<PHP
            <?php
            pre100({$obj}, {$className});
            ?>
            PHP;
        });
    }
}
