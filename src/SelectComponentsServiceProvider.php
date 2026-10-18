<?php

declare(strict_types=1);

namespace Select\Components;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SelectComponentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/select-components.php', 'select-components');
    }

    public function boot(): void
    {
        // 1. Carrega as views com o namespace do pacote
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'select-components');

        // 2. Mapeamento de namespaces no Blade (garante retrocompatibilidade com <x-field.*>, <x-misc.*>, etc.)
        Blade::componentNamespace('Select\\Components\\Components\\Field', 'field');
        Blade::componentNamespace('Select\\Components\\Components\\Misc', 'misc');
        Blade::componentNamespace('Select\\Components\\Components\\Datatable', 'datatable');
        Blade::componentNamespace('Select\\Components\\Components\\Box', 'box');
        Blade::componentNamespace('Select\\Components\\Components\\Window', 'window');
        Blade::componentNamespace('Select\\Components\\Components\\Form', 'form');
        Blade::componentNamespace('Select\\Components\\Components\\Modal', 'modal');

        // 3. Publicações via vendor:publish
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/select-components.php' => config_path('select-components.php'),
            ], 'select-components-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/select-components'),
            ], 'select-components-views');

            $this->publishes([
                __DIR__ . '/../resources/css' => public_path('vendor/select-components/css'),
                __DIR__ . '/../resources/js' => public_path('vendor/select-components/js'),
            ], 'select-components-assets');
        }
    }
}
