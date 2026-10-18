<?php

declare(strict_types=1);

namespace Select\Components\Components\Datatable;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Linha de Tabela (Row).
 *
 * Renderiza uma linha HTML (tr) padronizada com alinhamento vertical e classes utilitárias.
 */
class Row extends Component
{
    /**
     * Classe CSS resolvida de alinhamento vertical.
     */
    public string $alignmentClass;

    /**
     * Cria uma nova instância do componente Row.
     *
     * @param string $align Alinhamento vertical da linha (ex: 'middle', 'top', 'bottom').
     * @param string $class Classes CSS adicionais aplicadas à linha.
     */
    public function __construct(
        public string $align = 'middle',
        public string $class = '',
    ) {
        $this->alignmentClass = $this->align !== '' ? 'align-' . $this->align : '';
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.datatable.row');
    }
}
