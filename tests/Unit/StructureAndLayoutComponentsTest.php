<?php

declare(strict_types=1);

namespace Select\Components\Tests\Unit;

use Select\Components\Enums\ButtonStyleEnum;
use Select\Components\Enums\ButtonTypeEnum;
use Select\Components\Support\Forms\ButtonBuilder;
use Select\Components\Support\Forms\FormBuilder;
use Select\Components\Support\FormSupport;
use Select\Components\Components\Box\Col as BoxCol;
use Select\Components\Components\Box\Content as BoxContent;
use Select\Components\Components\Box\Row as BoxRow;
use Select\Components\Components\Form\Actions as FormActions;
use Select\Components\Components\Form\Form as FormComponent;
use Select\Components\Components\Maps as MapsComponent;
use Select\Components\Components\Modal\Content as ModalContent;
use Select\Components\Components\Modal\Modal as ModalComponent;
use Select\Components\Components\Window\Content as WindowContent;
use Select\Components\Components\Window\Crud as WindowCrud;
use Select\Components\Components\Window\Header as WindowHeader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Select\Components\Tests\TestCase;

class StructureAndLayoutComponentsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Registra rota de teste para testes de botão/ação
        Route::get('/test-route-list', fn () => 'list')->name('usuarios.list');
        Route::get('/test-route-create', fn () => 'create')->name('usuarios.create');
        Route::get('/test-route-edit/{id}', fn ($id) => 'edit ' . $id)->name('usuarios.edit');
    }

    // ==========================================
    // BOX SUBDOMAIN TESTS
    // ==========================================

    public function test_box_content_renders_correctly(): void
    {
        $html = Blade::render(
            '<x-box.content title="Filtros de Pesquisa" icon="bi-search" :collapse="true">
                <p>Conteúdo da box</p>
            </x-box.content>'
        );

        $this->assertStringContainsString('Filtros de Pesquisa', $html);
        $this->assertStringContainsString('card-header', $html);
        $this->assertStringContainsString('card-body', $html);
        $this->assertStringContainsString('Conteúdo da box', $html);
        $this->assertStringContainsString('box-collapse-btn', $html);
        $this->assertStringContainsString('data-bs-toggle="collapse"', $html);
    }

    public function test_box_content_with_custom_grid_sizes_and_actions(): void
    {
        $html = Blade::render(
            '<x-box.content title="Card Detalhes" sizeMd="6" sizeLg="4">
                <x-slot:actions>
                    <button class="btn btn-sm btn-primary">Ação</button>
                </x-slot:actions>
                <span>Slot Interno</span>
            </x-box.content>'
        );

        $this->assertStringContainsString('col-md-6', $html);
        $this->assertStringContainsString('col-lg-4', $html);
        $this->assertStringContainsString('<button class="btn btn-sm btn-primary">Ação</button>', $html);
        $this->assertStringContainsString('Slot Interno', $html);
    }

    public function test_box_content_direct_view_rendering(): void
    {
        $html = view('components.box.content', [
            'name' => 'direto',
            'id' => 'box_direto_id',
            'title' => 'Box Direta',
            'slot' => 'Conteúdo Direto',
        ])->render();

        $this->assertStringContainsString('id="box_direto_id"', $html);
        $this->assertStringContainsString('Box Direta', $html);
        $this->assertStringContainsString('Conteúdo Direto', $html);
    }

    public function test_box_row_renders_correctly(): void
    {
        $html = Blade::render(
            '<x-box.row class="align-items-center" bottom_space="mb-4">
                <p>Linha interna</p>
            </x-box.row>'
        );

        $this->assertStringContainsString('row', $html);
        $this->assertStringContainsString('mb-4', $html);
        $this->assertStringContainsString('align-items-center', $html);
        $this->assertStringContainsString('Linha interna', $html);
    }

    public function test_box_row_direct_view_rendering(): void
    {
        $html = view('components.box.row', [
            'name' => 'row_teste',
            'id' => 'row_custom_id',
            'bottom_space' => 'mb-2',
            'slot' => 'Linha Direta',
        ])->render();

        $this->assertStringContainsString('id="row_custom_id"', $html);
        $this->assertStringContainsString('mb-2', $html);
        $this->assertStringContainsString('Linha Direta', $html);
    }

    public function test_box_col_renders_grid_classes(): void
    {
        $html = Blade::render(
            '<x-box.col sizeXs="12" sizeMd="6" sizeLg="4" bottom_space="mb-3" class="p-2">
                <span>Coluna Grid</span>
            </x-box.col>'
        );

        $this->assertStringContainsString('col-12', $html);
        $this->assertStringContainsString('col-md-6', $html);
        $this->assertStringContainsString('col-lg-4', $html);
        $this->assertStringContainsString('mb-3', $html);
        $this->assertStringContainsString('p-2', $html);
        $this->assertStringContainsString('Coluna Grid', $html);
    }

    public function test_box_col_calculate_grid_classes_logic(): void
    {
        $col = new BoxCol(
            sizeXs: '12',
            sizeSm: '6',
            sizeMd: '4',
            sizeLg: '3',
            sizeXl: '2',
            bottom_space: 'mb-4',
            class: 'custom-col'
        );

        $classes = $col->calculateGridClasses();

        $this->assertContains('col', $classes);
        $this->assertContains('col-12', $classes);
        $this->assertContains('col-sm-6', $classes);
        $this->assertContains('col-md-4', $classes);
        $this->assertContains('col-lg-3', $classes);
        $this->assertContains('col-xl-2', $classes);
        $this->assertContains('mb-4', $classes);
        $this->assertContains('custom-col', $classes);
    }

    // ==========================================
    // MODAL SUBDOMAIN TESTS
    // ==========================================

    public function test_modal_renders_with_header_and_body(): void
    {
        $html = Blade::render(
            '<x-modal id="modalTeste" title="Confirmar Operação" icon="bi-shield-check" size="lg">
                <p>Você tem certeza que deseja prosseguir?</p>
            </x-modal>'
        );

        $this->assertStringContainsString('id="modalTeste"', $html);
        $this->assertStringContainsString('modal-lg', $html);
        $this->assertStringContainsString('modal-dialog-centered', $html);
        $this->assertStringContainsString('modal-dialog-scrollable', $html);
        $this->assertStringContainsString('Confirmar Operação', $html);
        $this->assertStringContainsString('Você tem certeza que deseja prosseguir?', $html);
        $this->assertStringContainsString('btn-close', $html);
    }

    public function test_modal_with_form_and_action_buttons(): void
    {
        $html = Blade::render(
            '<x-modal id="modalForm" title="Novo Registro" formAction="/usuarios/salvar" cancelLabel="Fechar" submitLabel="Gravar">
                <input type="text" name="nome" value="Carlos" />
            </x-modal>'
        );

        $this->assertStringContainsString('id="modalForm"', $html);
        $this->assertStringContainsString('action="/usuarios/salvar"', $html);
        $this->assertStringContainsString('Fechar', $html);
        $this->assertStringContainsString('Gravar', $html);
        $this->assertStringContainsString('type="submit"', $html);
        $this->assertStringContainsString('name="nome"', $html);
    }

    public function test_modal_static_backdrop_and_keyboard_disabled(): void
    {
        $modal = new ModalComponent(
            id: 'modalEstatico',
            static: true
        );

        $this->assertSame('static', $modal->bsBackdrop);
        $this->assertSame('false', $modal->bsKeyboard);

        $html = Blade::render('<x-modal id="modalEstatico" :static="true">Conteúdo</x-modal>');
        $this->assertStringContainsString('data-bs-backdrop="static"', $html);
        $this->assertStringContainsString('data-bs-keyboard="false"', $html);
    }

    public function test_modal_content_component_direct_rendering(): void
    {
        $html = view('components.modal.content', [
            'id' => 'customModalContent',
            'title' => 'Conteúdo Parcial',
            'color' => 'danger',
            'slot' => 'Corpo do modal em perigo',
        ])->render();

        $this->assertStringContainsString('id="customModalContent"', $html);
        $this->assertStringContainsString('bg-danger text-white', $html);
        $this->assertStringContainsString('Conteúdo Parcial', $html);
        $this->assertStringContainsString('Corpo do modal em perigo', $html);
    }

    // ==========================================
    // WINDOW SUBDOMAIN TESTS
    // ==========================================

    public function test_window_header_renders_caption_subtitle_and_icon(): void
    {
        $html = Blade::render(
            '<x-window.header caption="Gestão de Candidatos" icon="bi-people" subtitle="Gerencie as inscrições ativas">
                <x-slot:actions>
                    <a href="/novo" class="btn btn-sm btn-primary">Novo Candidato</a>
                </x-slot:actions>
            </x-window.header>'
        );

        $this->assertStringContainsString('Gestão de Candidatos', $html);
        $this->assertStringContainsString('Gerencie as inscrições ativas', $html);
        $this->assertStringContainsString('Novo Candidato', $html);
        $this->assertStringContainsString('page-header', $html);
    }

    public function test_window_header_resolves_breadcrumbs(): void
    {
        $header = new WindowHeader(
            caption: 'Editais',
            breadcrumbs: [
                ['label' => 'Painel', 'url' => '/admin'],
                ['label' => 'Concursos', 'url' => '/concursos'],
                ['label' => 'Detalhes'],
            ]
        );

        $resolved = $header->resolvedBreadcrumbs;
        $this->assertCount(3, $resolved);
        $this->assertSame('Painel', $resolved[0]['label']);
        $this->assertSame('/admin', $resolved[0]['url']);
        $this->assertFalse($resolved[0]['active']);

        $this->assertSame('Detalhes', $resolved[2]['label']);
        $this->assertNull($resolved[2]['url']);
        $this->assertTrue($resolved[2]['active']);

        $html = Blade::render(
            '<x-window.header caption="Editais" :breadcrumbs="$breadcrumbs" />',
            ['breadcrumbs' => [
                ['label' => 'Painel', 'url' => '/admin'],
                ['label' => 'Detalhes'],
            ]]
        );

        $this->assertStringContainsString('Painel', $html);
        $this->assertStringContainsString('/admin', $html);
        $this->assertStringContainsString('breadcrumb-item active', $html);
    }

    public function test_window_content_wraps_slot(): void
    {
        $html = Blade::render(
            '<x-window.content class="custom-window-class">
                <p>Conteúdo da página interna</p>
            </x-window.content>'
        );

        $this->assertStringContainsString('container-fluid px-4', $html);
        $this->assertStringContainsString('custom-window-class', $html);
        $this->assertStringContainsString('Conteúdo da página interna', $html);
    }

    public function test_window_modal_wrapper(): void
    {
        $html = Blade::render(
            '<x-window.modal id="winModal" title="Janela Modal">
                <p>Slot da janela</p>
            </x-window.modal>'
        );

        $this->assertStringContainsString('id="winModal"', $html);
        $this->assertStringContainsString('Janela Modal', $html);
        $this->assertStringContainsString('Slot da janela', $html);
    }

    public function test_window_crud_throws_when_form_is_missing(): void
    {
        $this->expectException(\Throwable::class);
        $this->expectExceptionMessage("A variável 'form' deve ser fornecida para construção do formulário.");

        view('components.window.crud')->render();
    }

    public function test_window_crud_initializes_active_form_and_getters(): void
    {
        $formBuilder = new class extends FormBuilder {
            public function __construct()
            {
                $this->setTitle('Cadastro de Concurso');
                $this->setCaptionIcon('bi-award');
            }
        };

        $crud = new WindowCrud(form: $formBuilder);

        $this->assertSame('Cadastro de Concurso', $crud->getTitle());
        $this->assertSame('bi-award', $crud->getCaptionIcon());
        $this->assertSame('POST', $crud->getMethod());
        $this->assertTrue($crud->needsCsrf());
        $this->assertSame($formBuilder, FormSupport::getActiveForm());
    }

    public function test_window_crud_renders_autofocus_script(): void
    {
        $mockMenu = \Mockery::mock(\App\Services\MenuService::class)->shouldIgnoreMissing();
        $mockMenu->shouldReceive('getModulosDisponiveisParaUsuario')->andReturn(collect());
        $mockMenu->shouldReceive('getEventosDisponiveisParaUsuario')->andReturn(collect());
        $mockMenu->shouldReceive('getInstituicoesDisponiveisParaUsuario')->andReturn(collect());
        $this->app->instance(\App\Services\MenuService::class, $mockMenu);

        $user = new \App\Models\User(['id' => 1, 'name' => 'Admin', 'email' => 'admin@softtan.com.br']);
        \Illuminate\Support\Facades\Auth::setUser($user);

        $formBuilder = FormBuilder::create('Novo Cadastro', 'Criar Registro', 'usuarios');

        $html = view('components.window.crud', [
            'form' => $formBuilder,
            'slot' => '<input type="text" name="nome" id="input_nome" />',
        ])->render();

        $this->assertStringContainsString('aplicarFocoPrimeiroCampo', $html);
        $this->assertStringContainsString('input:not([type="hidden"])', $html);
        $this->assertStringContainsString('button:not([disabled])', $html);
    }

    // ==========================================
    // FORM SUBDOMAIN TESTS
    // ==========================================

    public function test_form_component_post_with_csrf(): void
    {
        $html = Blade::render(
            '<x-form.form action="/usuarios/salvar" method="POST">
                <input type="text" name="nome" />
            </x-form.form>'
        );

        $this->assertStringContainsString('action="/usuarios/salvar"', $html);
        $this->assertStringContainsString('method="POST"', $html);
        $this->assertStringContainsString('name="_token"', $html);
        $this->assertStringNotContainsString('name="_method"', $html);
    }

    public function test_form_component_get_without_csrf(): void
    {
        $html = Blade::render(
            '<x-form.form action="/busca" method="GET">
                <input type="text" name="q" />
            </x-form.form>'
        );

        $this->assertStringContainsString('action="/busca"', $html);
        $this->assertStringContainsString('method="GET"', $html);
        $this->assertStringNotContainsString('name="_token"', $html);
    }

    public function test_form_component_spoofed_put_and_delete_methods(): void
    {
        $formPut = new FormComponent(action: '/itens/1', method: 'PUT');
        $this->assertTrue($formPut->isSpoofed);
        $this->assertSame('PUT', $formPut->spoofMethod());
        $this->assertSame('POST', $formPut->htmlMethod);
        $this->assertTrue($formPut->shouldRenderCsrf());

        $html = Blade::render(
            '<x-form.form action="/itens/1" method="PUT">
                <input type="hidden" name="id" value="1" />
            </x-form.form>'
        );

        $this->assertStringContainsString('method="POST"', $html);
        $this->assertStringContainsString('name="_method"', $html);
        $this->assertStringContainsString('value="PUT"', $html);
        $this->assertStringContainsString('name="_token"', $html);
    }

    public function test_form_actions_resolves_buttons_and_routes(): void
    {
        $formBuilder = new class extends FormBuilder {
            public function __construct()
            {
                $this->setBaseRoute('usuarios');
                $btn = new ButtonBuilder('Salvar');
                $btn->setName('btnSalvar')
                    ->setAction('create')
                    ->setType(ButtonTypeEnum::SUBMIT)
                    ->setStyle(ButtonStyleEnum::PADRAO);
                $this->addButton($btn);
            }
        };

        $actions = new FormActions(form: $formBuilder);
        $buttons = $actions->buttons();

        $this->assertCount(1, $buttons);
        $resolvedRoute = $actions->resolveButtonRoute($buttons[0]);
        $this->assertSame(route('usuarios.create'), $resolvedRoute);

        $html = Blade::render(
            '<x-form.actions :form="$form" />',
            ['form' => $formBuilder]
        );

        $this->assertStringContainsString('Salvar', $html);
        $this->assertStringContainsString(route('usuarios.create'), $html);
    }

    // ==========================================
    // MAPS SUBDOMAIN TESTS
    // ==========================================

    public function test_maps_component_renders_container_and_scripts(): void
    {
        $html = Blade::render(
            '<x-maps name="local" height="400px" width="100%" latitude="-23.55052" longitude="-46.633308" zoom="15" />'
        );

        $this->assertStringContainsString('id="maps_local_id_wrapper"', $html);
        $this->assertStringContainsString('id="container_maps_local_id"', $html);
        $this->assertStringContainsString('height: 400px;', $html);
        $this->assertStringContainsString('width: 100%;', $html);
        $this->assertStringContainsString('recarregarMapa_maps_local_id', $html);
        $this->assertStringContainsString('OpenStreetMap', $html);
    }

    public function test_maps_component_normalizes_address_data(): void
    {
        $map = new MapsComponent(
            name: 'polo',
            cep: '01001-000',
            logradouro: 'Praça da Sé',
            bairro: 'Sé',
            cidade: 'São Paulo',
            uf: 'SP',
            titulo: 'Polo Central'
        );

        $addressData = $map->getAddressData();

        $this->assertSame('01001-000', $addressData['cep']);
        $this->assertSame('Praça da Sé', $addressData['logradouro']);
        $this->assertSame('Sé', $addressData['bairro']);
        $this->assertSame('São Paulo', $addressData['cidade']);
        $this->assertSame('SP', $addressData['uf']);
        $this->assertSame('Polo Central', $addressData['titulo']);
    }

    public function test_maps_direct_view_rendering(): void
    {
        $html = view('components.maps', [
            'name' => 'direto_mapa',
            'id' => 'maps_direto_id',
            'height' => '300px',
            'showToolbar' => true,
        ])->render();

        $this->assertStringContainsString('id="container_maps_direto_id"', $html);
        $this->assertStringContainsString('height: 300px;', $html);
    }

    public function test_component_id_prefix_enum_generates_random_id(): void
    {
        $id1 = \Select\Components\Enums\ComponentIdPrefixEnum::INPUT->generateId();
        $id2 = \Select\Components\Enums\ComponentIdPrefixEnum::INPUT->generateId();

        $this->assertStringStartsWith('input_', $id1);
        $this->assertStringEndsWith('_id', $id1);
        $this->assertNotSame($id1, $id2);

        $idCol = \Select\Components\Enums\ComponentIdPrefixEnum::COL->generateId();
        $this->assertStringStartsWith('col_', $idCol);
        $this->assertStringEndsWith('_id', $idCol);
    }

    public function test_component_id_respects_explicit_id(): void
    {
        $custom = 'meu_id_customizado';
        $resolved = \Select\Components\Enums\ComponentIdPrefixEnum::SELECT->generateId($custom);

        $this->assertSame($custom, $resolved);

        $html = Blade::render('<x-box.col id="coluna_especifica"><span>Teste</span></x-box.col>');
        $this->assertStringContainsString('id="coluna_especifica"', $html);

        $htmlRow = Blade::render('<x-box.row id="linha_especifica"><span>Teste</span></x-box.row>');
        $this->assertStringContainsString('id="linha_especifica"', $htmlRow);
    }
}
