<?php

declare(strict_types=1);

namespace Select\Components\Components\Box;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Select\Components\Support\StringSupport;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Coluna do Grid de Layout (Col).
 *
 * Renderiza uma coluna Bootstrap com suporte completo aos breakpoints
 * responsivos (xs, sm, md, lg, xl, xxl) e classes adicionais.
 */
class Col extends Component
{
    public string $name;

    public string $id;

    /**
     * @var array<int, string>
     */
    public array $gridClasses;

    /**
     * Cria uma nova instância do componente Col.
     *
     * @param string|null $name Nome identificador da coluna.
     * @param string|null $id Identificador HTML da coluna.
     * @param string|null $bottom_space Classe de espaçamento inferior (ex: 'mb-3').
     * @param string|int|null $sizeXs Largura da coluna no breakpoint Extra Small (padrão: '12').
     * @param string|int|null $sizeSm Largura da coluna no breakpoint Small (padrão: '12').
     * @param string|int|null $sizeMd Largura da coluna no breakpoint Medium (ex: '6', 'auto').
     * @param string|int|null $sizeLg Largura da coluna no breakpoint Large.
     * @param string|int|null $sizeXl Largura da coluna no breakpoint Extra Large.
     * @param string|int|null $sizeXxl Largura da coluna no breakpoint Extra Extra Large.
     * @param string|null $class Classes CSS adicionais aplicadas à coluna.
     */
    public function __construct(
        ?string $name = null,
        ?string $id = null,
        public ?string $bottom_space = null,
        public ?string $bottomSpace = null,
        public string|int|null $sizeXs = '12',
        public string|int|null $sizeSm = '12',
        public string|int|null $sizeMd = null,
        public string|int|null $sizeLg = null,
        public string|int|null $sizeXl = null,
        public string|int|null $sizeXxl = null,
        public ?string $class = null,
    ) {
        $this->name = $name ?? StringSupport::nameRandom();
        $this->id = StringSupport::hasValue($id)
            ? (string) $id
            : ($name !== null ? "col_{$this->name}_id" : ComponentIdPrefixEnum::COL->generateId());
        $this->bottom_space = $this->bottomSpace ?? $this->bottom_space;
        $this->gridClasses = $this->calculateGridClasses();
    }

    /**
     * Calcula a lista de classes CSS para a coluna do grid.
     *
     * @return array<int, string>
     */
    public function calculateGridClasses(): array
    {
        $classes = ['col'];

        if (!empty($this->bottom_space)) {
            $classes[] = (string) $this->bottom_space;
        }

        if (StringSupport::hasValue($this->sizeXs)) {
            $classes[] = $this->sizeXs === 'auto' ? 'col-auto' : "col-{$this->sizeXs}";
        }

        if (StringSupport::hasValue($this->sizeSm)) {
            $classes[] = $this->sizeSm === 'auto' ? 'col-sm-auto' : "col-sm-{$this->sizeSm}";
        }

        if (StringSupport::hasValue($this->sizeMd)) {
            $classes[] = $this->sizeMd === 'auto' ? 'col-md-auto' : "col-md-{$this->sizeMd}";
        }

        if (StringSupport::hasValue($this->sizeLg)) {
            $classes[] = $this->sizeLg === 'auto' ? 'col-lg-auto' : "col-lg-{$this->sizeLg}";
        }

        if (StringSupport::hasValue($this->sizeXl)) {
            $classes[] = $this->sizeXl === 'auto' ? 'col-xl-auto' : "col-xl-{$this->sizeXl}";
        }

        if (StringSupport::hasValue($this->sizeXxl)) {
            $classes[] = $this->sizeXxl === 'auto' ? 'col-xxl-auto' : "col-xxl-{$this->sizeXxl}";
        }

        if (!empty($this->class)) {
            $classes[] = (string) $this->class;
        }

        return $classes;
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.box.col');
    }
}
