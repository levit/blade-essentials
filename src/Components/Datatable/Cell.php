<?php

declare(strict_types=1);

namespace Select\Components\Components\Datatable;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Célula de Tabela (Cell).
 *
 * Renderiza uma célula HTML (td) com alinhamento textual flexível,
 * mesclagem de células (colspan/rowspan) e metadados para ordenação.
 */
class Cell extends Component
{
    /**
     * Classe CSS de alinhamento textual resolvida.
     */
    public string $alignmentClass;

    /**
     * Cria uma nova instância do componente Cell.
     *
     * @param string $align Alinhamento horizontal do conteúdo ('start', 'left', 'center', 'middle', 'right', 'end').
     * @param string $class Classes CSS adicionais aplicadas à célula.
     * @param string|int|null $colspan Quantidade de colunas mescladas.
     * @param string|int|null $rowspan Quantidade de linhas mescladas.
     * @param mixed $sort_data Valor explícito para data-sort (usado por bibliotecas de ordenação).
     * @param mixed $sortData Alias camelCase para sort_data.
     */
    public function __construct(
        public string $align = 'left',
        public string $class = '',
        public string|int|null $colspan = null,
        public string|int|null $rowspan = null,
        public mixed $sort_data = null,
        public mixed $sortData = null,
    ) {
        $this->sort_data = $this->sortData ?? $this->sort_data;
        $this->alignmentClass = match ($this->align) {
            'center', 'middle' => 'text-center',
            'right', 'end' => 'text-end',
            default => 'text-start',
        };
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.datatable.cell');
    }
}
