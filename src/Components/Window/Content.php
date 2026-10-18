<?php

declare(strict_types=1);

namespace Select\Components\Components\Window;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Área de Conteúdo da Janela (Content).
 *
 * Envelopa o conteúdo principal da tela em um container responsivo fluido
 * com espaçamento padronizado no layout.
 */
class Content extends Component
{
    /**
     * Cria uma nova instância do componente Content de Janela.
     *
     * @param string|null $class Classes adicionais aplicadas ao container principal.
     */
    public function __construct(
        public ?string $class = null,
    ) {
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.window.content');
    }
}
