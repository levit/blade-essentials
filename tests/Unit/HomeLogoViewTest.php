<?php

namespace Select\Components\Tests\Unit;

use App\Models\Instituicao;
use App\Models\User;
use App\Services\MenuService;
use Illuminate\Support\ViewErrorBag;
use Mockery;
use Select\Components\Tests\TestCase;

class HomeLogoViewTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        view()->share('errors', new ViewErrorBag);

        $mockMenu = Mockery::mock(MenuService::class)->shouldIgnoreMissing();
        $mockMenu->shouldReceive('getModulosDisponiveisParaUsuario')->andReturn(collect());
        $mockMenu->shouldReceive('getEventosDisponiveisParaUsuario')->andReturn(collect());
        $mockMenu->shouldReceive('getInstituicoesDisponiveisParaUsuario')->andReturn(collect());
        $this->app->instance(MenuService::class, $mockMenu);
    }

    public function test_home_view_renders_white_square_with_client_logo(): void
    {
        $instituicao = new Instituicao([
            'id' => 1,
            'nome' => 'Prefeitura de Teste',
            'abreviacao' => 'PMT',
            'site' => 'https://pmt.gov.br',
        ]);
        $instituicao->logotipo_path = 'instituicoes/logo_cliente.png';

        $user = new User(['id' => 1, 'name' => 'Admin', 'email' => 'admin@softtan.com.br']);
        $user->instituicao_id = 1;
        $user->setRelation('instituicao', $instituicao);
        auth()->setUser($user);

        $html = view('modulos.home.index', ['modulos' => []])->render();

        $this->assertStringContainsString('bg-white rounded-3 p-2 shadow-sm', $html);
        $this->assertStringContainsString('width: 90px; height: 90px;', $html);
        $this->assertStringContainsString('instituicoes/logo_cliente.png', $html);
        $this->assertStringContainsString('object-fit: contain;', $html);
        $this->assertStringContainsString('https://pmt.gov.br', $html);
    }

    public function test_home_view_renders_fallback_logo_when_no_custom_logo(): void
    {
        $instituicao = new Instituicao([
            'id' => 1,
            'nome' => 'Softtan Tecnologia LTDA',
            'abreviacao' => 'SOFTTAN',
        ]);

        $user = new User(['id' => 1, 'name' => 'Admin', 'email' => 'admin@softtan.com.br']);
        $user->instituicao_id = 1;
        $user->setRelation('instituicao', $instituicao);
        auth()->setUser($user);

        $html = view('modulos.home.index', ['modulos' => []])->render();

        $this->assertStringContainsString('bg-white rounded-3 p-2 shadow-sm', $html);
        $this->assertStringContainsString('width: 90px; height: 90px;', $html);
        $this->assertStringContainsString('assets/img/logos/softtan.png', $html);
        $this->assertStringContainsString('object-fit: contain;', $html);
    }

    public function test_home_view_renders_modulos_with_paragraph_typography_pattern(): void
    {
        $instituicao = new Instituicao([
            'id' => 1,
            'nome' => 'Softtan Tecnologia LTDA',
            'abreviacao' => 'SOFTTAN',
        ]);
        $user = new User(['id' => 1, 'name' => 'Admin', 'email' => 'admin@softtan.com.br']);
        $user->instituicao_id = 1;
        $user->setRelation('instituicao', $instituicao);
        auth()->setUser($user);

        $modulo = new \App\Models\Modulo([
            'id' => 1,
            'nome' => 'Módulo Concursos',
            'rota' => 'modulos.concursos',
        ]);

        $html = view('modulos.home.index', ['modulos' => collect([$modulo])])->render();

        $this->assertStringContainsString('Acesse o módulo desejado abaixo ou no topo da tela do sistema', $html);
        $this->assertStringContainsString('Módulo Concursos', $html);
        $this->assertMatchesRegularExpression('/<p[^>]*class="[^"]*text-center[^"]*fs-6[^"]*text-body[^"]*"[^>]*>\s*Módulo Concursos\s*<\/p>/', $html);
    }
}

