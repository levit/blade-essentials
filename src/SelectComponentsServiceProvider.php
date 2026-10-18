<?php

declare(strict_types=1);

namespace Select\Components;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SelectComponentsServiceProvider extends ServiceProvider
{
    /**
     * Mapeamento de componentes e seus respectivos aliases Blade.
     *
     * @var array<string, class-string>
     */
    protected array $components = [
        // Box
        'box.col' => \Select\Components\Components\Box\Col::class,
        'box.content' => \Select\Components\Components\Box\Content::class,
        'box.row' => \Select\Components\Components\Box\Row::class,

        // Datatable
        'datatable.actions' => \Select\Components\Components\Datatable\Actions::class,
        'datatable.cell' => \Select\Components\Components\Datatable\Cell::class,
        'datatable.empty' => \Select\Components\Components\Datatable\EmptyState::class,
        'datatable.empty-state' => \Select\Components\Components\Datatable\EmptyState::class,
        'datatable.filters' => \Select\Components\Components\Datatable\Filters::class,
        'datatable.header' => \Select\Components\Components\Datatable\Header::class,
        'datatable.row' => \Select\Components\Components\Datatable\Row::class,
        'datatable.table' => \Select\Components\Components\Datatable\Table::class,

        // Field
        'field.button' => \Select\Components\Components\Field\Button::class,
        'field.cep' => \Select\Components\Components\Field\Cep::class,
        'field.checkbox' => \Select\Components\Components\Field\Checkbox::class,
        'field.cpf-cnpj' => \Select\Components\Components\Field\CpfCnpj::class,
        'field.datepicker' => \Select\Components\Components\Field\Datepicker::class,
        'field.datetime' => \Select\Components\Components\Field\Datetime::class,
        'field.email' => \Select\Components\Components\Field\Email::class,
        'field.file' => \Select\Components\Components\Field\File::class,
        'field.hidden' => \Select\Components\Components\Field\Hidden::class,
        'field.image' => \Select\Components\Components\Field\Image::class,
        'field.input' => \Select\Components\Components\Field\Input::class,
        'field.input-group' => \Select\Components\Components\Field\InputGroup::class,
        'field.ip' => \Select\Components\Components\Field\Ip::class,
        'field.markdown' => \Select\Components\Components\Field\Markdown::class,
        'field.money' => \Select\Components\Components\Field\Money::class,
        'field.number' => \Select\Components\Components\Field\Number::class,
        'field.phone' => \Select\Components\Components\Field\Phone::class,
        'field.radio' => \Select\Components\Components\Field\Radio::class,
        'field.select' => \Select\Components\Components\Field\Select::class,
        'field.select-checkbox' => \Select\Components\Components\Field\SelectCheckbox::class,
        'field.switch' => \Select\Components\Components\Field\SwitchField::class,
        'switch' => \Select\Components\Components\Field\SwitchField::class,
        'field.tags' => \Select\Components\Components\Field\Tags::class,
        'field.textarea' => \Select\Components\Components\Field\Textarea::class,
        'field.url' => \Select\Components\Components\Field\Url::class,
        'field.user-agent' => \Select\Components\Components\Field\UserAgent::class,

        // Form
        'form.actions' => \Select\Components\Components\Form\Actions::class,
        'form.form' => \Select\Components\Components\Form\Form::class,

        // Misc
        'misc.badge' => \Select\Components\Components\Misc\Badge::class,
        'badge' => \Select\Components\Components\Misc\Badge::class,
        'misc.icone' => \Select\Components\Components\Misc\Icone::class,
        'misc.image' => \Select\Components\Components\Misc\Image::class,
        'misc.ip' => \Select\Components\Components\Misc\Ip::class,
        'misc.kbd' => \Select\Components\Components\Misc\Kbd::class,
        'misc.label' => \Select\Components\Components\Misc\Label::class,
        'misc.link' => \Select\Components\Components\Misc\Link::class,
        'misc.messages' => \Select\Components\Components\Misc\Messages::class,
        'misc.user-agent' => \Select\Components\Components\Misc\UserAgent::class,

        // Modal
        'modal' => \Select\Components\Components\Modal::class,
        'modal.modal' => \Select\Components\Components\Modal\Modal::class,
        'modal.content' => \Select\Components\Components\Modal\Content::class,

        // Window
        'window.content' => \Select\Components\Components\Window\Content::class,
        'window.crud' => \Select\Components\Components\Window\Crud::class,
        'window.header' => \Select\Components\Components\Window\Header::class,
        'window.modal' => \Select\Components\Components\Window\Modal::class,

        // Maps
        'maps' => \Select\Components\Components\Maps::class,
    ];

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/select-components.php', 'select-components');
    }

    public function boot(): void
    {
        // 1. Carrega as views e registra o diretório de componentes para resolução direta de view('components.*')
        $this->loadViewsFrom(__DIR__ . '/../resources/views/components', 'select-components');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'select-components');
        $this->app['view']->addLocation(__DIR__ . '/../resources/views');

        // 2. Registro explícito de aliases no Blade (<x-field.*>, <x-misc.*>, <x-badge>, etc.)
        foreach ($this->components as $alias => $class) {
            Blade::component($class, $alias);
        }

        // 3. Mapeamento de namespaces no Blade (suporte a sintaxe com prefixo <x-field::*>)
        Blade::componentNamespace('Select\\Components\\Components\\Field', 'field');
        Blade::componentNamespace('Select\\Components\\Components\\Misc', 'misc');
        Blade::componentNamespace('Select\\Components\\Components\\Datatable', 'datatable');
        Blade::componentNamespace('Select\\Components\\Components\\Box', 'box');
        Blade::componentNamespace('Select\\Components\\Components\\Window', 'window');
        Blade::componentNamespace('Select\\Components\\Components\\Form', 'form');
        Blade::componentNamespace('Select\\Components\\Components\\Modal', 'modal');

        // 4. Publicações via vendor:publish
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
