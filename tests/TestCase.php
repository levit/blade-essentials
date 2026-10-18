<?php

declare(strict_types=1);

namespace Select\Components\Tests;

use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Select\Components\SelectComponentsServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!class_exists(\App\Enums\ComponentIdPrefixEnum::class)) {
            class_alias(\Select\Components\Enums\ComponentIdPrefixEnum::class, 'App\\Enums\\ComponentIdPrefixEnum');
        }

        if (!class_exists('App\\Models\\User')) {
            eval('namespace App\Models; class User extends \Illuminate\Foundation\Auth\User { protected $guarded = []; }');
        }

        if (!class_exists('App\\Models\\Instituicao')) {
            eval('namespace App\Models; class Instituicao extends \Illuminate\Database\Eloquent\Model { protected $guarded = []; }');
        }

        if (!class_exists('App\\Models\\Modulo')) {
            eval('namespace App\Models; class Modulo extends \Illuminate\Database\Eloquent\Model { protected $guarded = []; }');
        }

        if (!class_exists('App\\Services\\MenuService')) {
            eval('namespace App\Services; class MenuService {}');
        }

        $this->app['view']->addLocation(__DIR__ . '/fixtures/views');

        Route::get('/parceiros/{id}', fn () => 'show')->name('parceiros.show');
        Route::get('/parceiros/{id}/edit', fn () => 'edit')->name('parceiros.edit');
        Route::delete('/parceiros/{id}', fn () => 'delete')->name('parceiros.delete');

        Route::get('/cargos', fn () => 'list')->name('cargos.list');
        Route::post('/cargos', fn () => 'store')->name('cargos.store');
        Route::get('/cargos/create', fn () => 'create')->name('cargos.create');
    }

    protected function getPackageProviders($app): array
    {
        return [
            SelectComponentsServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:m+Pjl13q9Vw2k9p1sF4G8H8L1a3M5N7Q9U1V3W5Y7Z0=');
    }
}
