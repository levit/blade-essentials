<?php

declare(strict_types=1);

namespace Select\Components\Components\Datatable;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Cabeçalho Superior de Datatable (Header).
 *
 * Renderiza um container card destacado para ações de topo, botões de inserção
 * e filtros contextuais antes da listagem principal.
 */
class Header extends Component
{
    /**
     * Cria uma nova instância do componente Header.
     *
     * @param string $class Classes CSS adicionais aplicadas ao card de cabeçalho.
     */
    public function __construct(
        public string $class = '',
    ) {
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.datatable.header');
    }
}
