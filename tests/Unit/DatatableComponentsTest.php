<?php

declare(strict_types=1);

namespace Select\Components\Tests\Unit;

use Select\Components\Components\Datatable\Filters;
use Select\Components\Components\Datatable\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Select\Components\Tests\TestCase;

enum StatusFiltroTesteEnum: string
{
    case ATIVO = 'ativo';
    case INATIVO = 'inativo';

    public function label(): string
    {
        return match ($this) {
            self::ATIVO => 'Ativo',
            self::INATIVO => 'Inativo',
        };
    }
}

class DatatableComponentsTest extends TestCase
{
    public function test_table_renderiza_estrutura_basica_com_headers_e_id_customizado(): void
    {
        $headers = [
            ['label' => 'ID', 'width' => '10%', 'align' => 'center'],
            ['label' => 'Nome', 'align' => 'start'],
            ['label' => 'Status', 'type' => 'string'],
        ];

        $html = Blade::render(
            '<x-datatable.table id="custom_table_id" :headers="$headers" :card="true" :jsDatatable="false">
                <x-datatable.row>
                    <x-datatable.cell align="center">1</x-datatable.cell>
                    <x-datatable.cell>Teste</x-datatable.cell>
                    <x-datatable.cell>Ativo</x-datatable.cell>
                </x-datatable.row>
            </x-datatable.table>',
            ['headers' => $headers]
        );

        $this->assertStringContainsString('id="custom_table_id"', $html);
        $this->assertStringContainsString('<th class="text-center" style="width: 10%;">', $html);
        $this->assertStringContainsString('ID', $html);
        $this->assertStringContainsString('Nome', $html);
        $this->assertStringContainsString('Status', $html);
        $this->assertStringContainsString('card mt-2', $html);
        $this->assertStringContainsString('table-responsive', $html);
        $this->assertStringContainsString('Teste', $html);
    }

    public function test_table_classe_precalcula_colunas_js_e_default_options(): void
    {
        $headers = [
            ['label' => 'Código', 'width' => '50px', 'type' => 'number'],
            ['label' => 'Título', 'type' => 'string'],
        ];

        $component = new Table(
            id: 'tbl_teste',
            headers: $headers,
            paginacao: true,
            registrosPorPagina: 25,
            ordenavel: true,
        );

        $this->assertSame('tbl_teste', $component->id);
        $this->assertCount(2, $component->columnsJs);
        $this->assertSame('50px', $component->columnsJs[0]['width']);
        $this->assertSame('number', $component->columnsJs[0]['type']);
        $this->assertTrue($component->columnsJs[0]['sortable']);

        $this->assertTrue($component->defaultOptions['paging']);
        $this->assertSame(25, $component->defaultOptions['perPage']);
        $this->assertTrue($component->defaultOptions['sortable']);
        $this->assertArrayHasKey('columns', $component->defaultOptions);
        $this->assertFalse($component->isEmpty);
    }

    public function test_table_renderiza_empty_automaticamente_quando_items_vazio(): void
    {
        $headers = [
            ['label' => 'Código'],
            ['label' => 'Nome'],
        ];

        $html = Blade::render(
            '<x-datatable.table :headers="$headers" :items="$items" emptyMessage="Nenhum registro para exibir." :jsDatatable="false">
                <x-datatable.row>
                    <x-datatable.cell>1</x-datatable.cell>
                    <x-datatable.cell>Ignorado</x-datatable.cell>
                </x-datatable.row>
            </x-datatable.table>',
            [
                'headers' => $headers,
                'items' => new Collection([]),
            ]
        );

        $this->assertStringContainsString('Nenhum registro para exibir.', $html);
        $this->assertStringContainsString('colspan="2"', $html);
        $this->assertStringNotContainsString('Ignorado', $html);
    }

    public function test_table_renderiza_paginador_quando_items_possui_paginas(): void
    {
        $paginator = new LengthAwarePaginator(
            items: range(1, 10),
            total: 50,
            perPage: 10,
            currentPage: 1,
            options: ['path' => '/teste']
        );

        $html = Blade::render(
            '<x-datatable.table :items="$paginator" :jsDatatable="false" :card="false">
                <x-datatable.row>
                    <x-datatable.cell>Item</x-datatable.cell>
                </x-datatable.row>
            </x-datatable.table>',
            ['paginator' => $paginator]
        );

        $this->assertStringContainsString('Mostrando <strong>1</strong> até <strong>10</strong> de <strong>50</strong> registros', $html);
        $this->assertStringContainsString('datatable-pagination', $html);
        $this->assertStringNotContainsString('<div class="card mt-2">', $html);
    }

    public function test_empty_state_renderiza_com_message_e_icone(): void
    {
        $html = Blade::render(
            '<x-datatable.empty :colspan="4" message="Lista vazia de teste" icon="search" />'
        );

        $this->assertStringContainsString('colspan="4"', $html);
        $this->assertStringContainsString('Lista vazia de teste', $html);
        $this->assertStringContainsString('data-feather="search"', $html);
    }

    public function test_empty_state_renderiza_com_slot_customizado(): void
    {
        $html = Blade::render(
            '<x-datatable.empty :colspan="3">
                <span class="custom-alert">Nenhum item adicionado</span>
            </x-datatable.empty>'
        );

        $this->assertStringContainsString('colspan="3"', $html);
        $this->assertStringContainsString('custom-alert', $html);
        $this->assertStringContainsString('Nenhum item adicionado', $html);
    }

    public function test_empty_state_funciona_com_alias_datatable_empty_state(): void
    {
        $html = Blade::render(
            '<x-datatable.empty-state :colspan="2" message="Mensagem via alias" />'
        );

        $this->assertStringContainsString('colspan="2"', $html);
        $this->assertStringContainsString('Mensagem via alias', $html);
    }

    public function test_header_renderiza_card_e_slot(): void
    {
        $html = Blade::render(
            '<x-datatable.header class="custom-header-class">
                <button type="button">Novo</button>
            </x-datatable.header>'
        );

        $this->assertStringContainsString('datatable-header-card', $html);
        $this->assertStringContainsString('custom-header-class', $html);
        $this->assertStringContainsString('Novo', $html);
    }

    public function test_row_renderiza_alinhamento_e_classes_css(): void
    {
        $html = Blade::render(
            '<x-datatable.row align="top" class="table-warning">
                <td>Conteúdo</td>
            </x-datatable.row>'
        );

        $this->assertStringContainsString('class="align-top table-warning"', $html);
        $this->assertStringContainsString('Conteúdo', $html);
    }

    public function test_cell_renderiza_alinhamentos_e_atributos(): void
    {
        $htmlCenter = Blade::render(
            '<x-datatable.cell align="center" :colspan="2" :rowspan="1" sort_data="123" class="fw-bold">
                Valor
            </x-datatable.cell>'
        );

        $this->assertStringContainsString('class="text-center fw-bold"', $htmlCenter);
        $this->assertStringContainsString('colspan="2"', $htmlCenter);
        $this->assertStringContainsString('rowspan="1"', $htmlCenter);
        $this->assertStringContainsString('data-sort="123"', $htmlCenter);
        $this->assertStringContainsString('Valor', $htmlCenter);

        $htmlEnd = Blade::render(
            '<x-datatable.cell align="end">Fim</x-datatable.cell>'
        );
        $this->assertStringContainsString('class="text-end"', $htmlEnd);
        $this->assertStringNotContainsString('colspan', $htmlEnd);
        $this->assertStringNotContainsString('rowspan', $htmlEnd);
        $this->assertStringNotContainsString('data-sort', $htmlEnd);
    }

    public function test_filters_renderiza_campos_e_botoes_com_rotas(): void
    {
        $action = '/admin/usuarios';
        $clearRoute = '/admin/usuarios/limpar';

        $html = Blade::render(
            '<x-datatable.filters 
                :action="$action" 
                :clearRoute="$clearRoute" 
                :statuses="[$statusEnum]" 
                :iniciarOcultado="false" 
                :mostrarDataInicial="true" 
                :mostrarDataFinal="true" 
                :mostrarBuscaTexto="true"
            >
                <div class="custom-filter-field">Campo Extra</div>
            </x-datatable.filters>',
            [
                'action' => $action,
                'clearRoute' => $clearRoute,
                'statusEnum' => StatusFiltroTesteEnum::ATIVO,
            ]
        );

        $this->assertStringContainsString('action="/admin/usuarios"', $html);
        $this->assertStringContainsString('display: block', $html);
        $this->assertStringContainsString('name="search"', $html);
        $this->assertStringContainsString('name="status"', $html);
        $this->assertStringContainsString('name="date_from"', $html);
        $this->assertStringContainsString('name="date_to"', $html);
        $this->assertStringContainsString('href="/admin/usuarios/limpar"', $html);
        $this->assertStringContainsString('Campo Extra', $html);
        $this->assertStringContainsString(StatusFiltroTesteEnum::ATIVO->label(), $html);
    }

    public function test_filters_classe_precalcula_status_options_e_resolved_clear_route(): void
    {
        $component = new Filters(
            action: '/produtos',
            statuses: [StatusFiltroTesteEnum::ATIVO, StatusFiltroTesteEnum::INATIVO],
            iniciarOcultado: true,
        );

        $this->assertSame('none', $component->displayStyle);
        $this->assertSame('/produtos?limpar=1', $component->resolvedClearRoute);
        $this->assertArrayHasKey(StatusFiltroTesteEnum::ATIVO->value, $component->statusOptions);
        $this->assertSame(StatusFiltroTesteEnum::ATIVO->label(), $component->statusOptions[StatusFiltroTesteEnum::ATIVO->value]);
    }
}
