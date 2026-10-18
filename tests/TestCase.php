<?php

declare(strict_types=1);

namespace Select\Components\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Select\Components\SelectComponentsServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
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
