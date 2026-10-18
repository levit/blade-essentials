<?php

declare(strict_types=1);

namespace Select\Components\Components\Misc;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Select\Components\Support\StringSupport;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

/**
 * Componente Blade para Links Hipertexto (Links) do Sistema.
 *
 * Renderiza âncoras HTML com tratamento seguro de target, rel e estilos padronizados.
 */
class Link extends Component
{
    /**
     * Identificador HTML id resolvido.
     */
    public string $id;

    /**
     * Atributo rel de segurança computado.
     */
    public ?string $rel;

    /**
     * Cria uma nova instância do componente Link.
     */
    public function __construct(
        public ?string $name = null,
        ?string $id = null,
        public ?string $href = '#',
        public ?string $target = '_self',
        public ?string $title = null,
        public ?string $label = null,
        ?string $rel = null,
    ) {
        $this->href = StringSupport::hasValue($this->href) ? (string) $this->href : '#';
        $this->target = StringSupport::hasValue($this->target) ? (string) $this->target : '_self';
        $hasExplicitName = StringSupport::hasValue($this->name);
        $this->name = $this->name ?? StringSupport::nameRandom();
        $this->id = StringSupport::hasValue($id)
            ? (string) $id
            : ($hasExplicitName ? "link_{$this->name}_id" : ComponentIdPrefixEnum::LINK->generateId());
        $this->rel = $rel ?? ($this->target === '_blank' ? 'noopener noreferrer' : null);
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.misc.link');
    }
}
