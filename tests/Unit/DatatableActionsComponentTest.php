<?php

declare(strict_types=1);

namespace Select\Components\Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Select\Components\Tests\TestCase;

class DatatableActionsComponentTest extends TestCase
{
    public function test_renderiza_acoes_com_route_prefix_e_record_id(): void
    {
        $uuid = '0191eb7c-7200-7212-9c3f-67db6f272a2e';
        $html = Blade::render('<x-datatable.actions routePrefix="parceiros" :recordId="$uuid" />', [
            'uuid' => $uuid,
        ]);

        $this->assertStringContainsString(route('parceiros.show', $uuid), $html);
        $this->assertStringContainsString(route('parceiros.edit', $uuid), $html);
        $this->assertStringContainsString(route('parceiros.delete', $uuid), $html);
        $this->assertStringContainsString('Visualizar', $html);
        $this->assertStringContainsString('Editar', $html);
        $this->assertStringContainsString('Excluir', $html);
    }

    public function test_renderiza_acoes_com_alias_id(): void
    {
        $uuid = '0191eb7c-7200-7212-9c3f-67db6f272a2e';
        $html = Blade::render('<x-datatable.actions routePrefix="parceiros" :id="$uuid" />', [
            'uuid' => $uuid,
        ]);

        $this->assertStringContainsString(route('parceiros.show', $uuid), $html);
        $this->assertStringContainsString(route('parceiros.edit', $uuid), $html);
        $this->assertStringContainsString(route('parceiros.delete', $uuid), $html);
    }
}
