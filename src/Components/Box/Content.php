<?php

declare(strict_types=1);

namespace Select\Components\Components\Box;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Select\Components\Support\StringSupport;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Card / Box Container (Content).
 *
 * Renderiza cards estruturados com suporte a cabeçalho estilizado, ícones,
 * dimensionamento em grid responsivo (col-*), ações de cabeçalho e botão de colapso.
 */
class Content extends Component
{
    public string $name;

    public string $id;

    public string $collapseId;

    public string $iconId;

    public bool $hasIcon;

    public bool $isLightHeader;

    public bool $hasCustomSizes;

    public bool $renderRow;

    public string $resolvedIconColor;

    public string $collapseButtonClass;

    public string $collapseIconColor;

    /**
     * Cria uma nova instância do componente Content.
     *
     * @param string|null $name Nome identificador da box.
     * @param string|null $id Identificador HTML da box.
     * @param string $titleColor Cor contextual do texto do título (ex: 'dark', 'primary').
     * @param string $bgTitleColor Classes CSS de fundo do cabeçalho do card.
     * @param string|null $icon Nome do ícone a ser exibido no cabeçalho.
     * @param string|null $iconColor Cor do ícone do cabeçalho.
     * @param string $title Título exibido no cabeçalho da box.
     * @param string $bottom_space Espaçamento inferior do container (ex: 'mb-3').
     * @param string|int|null $sizeXs Largura da coluna no breakpoint Extra Small.
     * @param string|int|null $sizeSm Largura da coluna no breakpoint Small.
     * @param string|int|null $sizeMd Largura da coluna no breakpoint Medium.
     * @param string|int|null $sizeLg Largura da coluna no breakpoint Large.
     * @param string|int|null $sizeXl Largura da coluna no breakpoint Extra Large.
     * @param string|int|null $sizeXxl Largura da coluna no breakpoint Extra Extra Large.
     * @param bool|null $hasRow Se deve envelopar a box em um elemento div.row.
     * @param bool $collapse Se deve habilitar botão para colapsar/expandir o conteúdo.
     * @param mixed $actions Slot ou conteúdo de ações no lado direito do cabeçalho.
     * @param string|null $class Classes CSS adicionais para o elemento de coluna.
     * @param string|null $cardClass Classes adicionais para o elemento card interno.
     * @param string|null $bodyClass Classes adicionais para o elemento card-body interno.
     * @param string|null $style Estilos inline adicionais.
     */
    public function __construct(
        ?string $name = null,
        ?string $id = null,
        public string $titleColor = 'dark',
        public string $bgTitleColor = 'bg-white border-bottom py-3',
        public ?string $icon = null,
        public ?string $iconColor = null,
        public string $title = '',
        public string $bottom_space = 'mb-3',
        public string|int|null $sizeXs = null,
        public string|int|null $sizeSm = null,
        public string|int|null $sizeMd = null,
        public string|int|null $sizeLg = null,
        public string|int|null $sizeXl = null,
        public string|int|null $sizeXxl = null,
        public ?bool $hasRow = null,
        public bool $collapse = true,
        public mixed $actions = null,
        public ?string $class = null,
        public ?string $cardClass = null,
        public ?string $bodyClass = null,
        public ?string $style = null,
    ) {
        $this->name = $name ?? StringSupport::nameRandom();
        $this->id = StringSupport::hasValue($id)
            ? (string) $id
            : ($name !== null ? "box_{$this->name}_id" : ComponentIdPrefixEnum::BOX->generateId());

        $this->collapseId = 'collapse_' . $this->name . '_body_id';
        $this->iconId = 'collapse_' . $this->name . '_icon_id';
        $this->hasIcon = !empty($this->icon);

        $this->isLightHeader = !str_contains($this->bgTitleColor, 'gradient')
            && !str_contains($this->bgTitleColor, 'bg-primary')
            && !str_contains($this->bgTitleColor, 'bg-dark');

        $this->hasCustomSizes = !empty($this->sizeXs)
            || !empty($this->sizeSm)
            || !empty($this->sizeMd)
            || !empty($this->sizeLg)
            || !empty($this->sizeXl)
            || !empty($this->sizeXxl);

        $this->renderRow = $this->hasRow ?? !$this->hasCustomSizes;
        $this->resolvedIconColor = $this->iconColor ?? ($this->isLightHeader ? 'primary' : 'white');
        $this->collapseButtonClass = $this->isLightHeader ? 'btn-transparent-dark text-muted' : 'text-white';
        $this->collapseIconColor = $this->isLightHeader ? 'muted' : 'white';
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.box.content');
    }
}
