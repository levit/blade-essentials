<?php

declare(strict_types=1);

namespace Select\Components\Components\Box;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Select\Components\Support\StringSupport;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Linha do Grid de Layout (Row).
 *
 * Renderiza uma linha Bootstrap (row) com identificador gerado e espaçamento configurável.
 */
class Row extends Component
{
    public string $name;

    public string $id;

    /**
     * Cria uma nova instância do componente Row.
     *
     * @param  string|null  $name  Nome identificador da linha.
     * @param  string|null  $id  Identificador HTML da linha.
     * @param  string  $bottom_space  Classe de espaçamento inferior (padrão: 'mb-3').
     * @param  string|null  $class  Classes CSS adicionais aplicadas à linha.
     */
    public function __construct(
        ?string $name = null,
        ?string $id = null,
        public string $bottom_space = 'mb-3',
        public ?string $bottomSpace = null,
        public ?string $class = null,
    ) {
        $this->name = $name ?? StringSupport::nameRandom();
        $this->id = StringSupport::hasValue($id)
            ? (string) $id
            : ($name !== null ? "row_{$this->name}_id" : ComponentIdPrefixEnum::ROW->generateId());
        $this->bottom_space = $this->bottomSpace ?? $this->bottom_space;
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.box.row');
    }
}
