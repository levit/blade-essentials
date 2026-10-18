<?php

declare(strict_types=1);

namespace Select\Components\Components\Misc;

use Select\Components\Support\StringSupport;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Rótulos (Labels) de Campos de Formulário.
 *
 * Renderiza rótulos semânticos integrados a inputs com suporte a indicador de obrigatoriedade.
 */
class Label extends Component
{
    /**
     * Texto do rótulo resolvido.
     */
    public ?string $labelText;

    /**
     * Classes CSS padrão resolvidas.
     */
    public string $defaultClass;

    /**
     * Cria uma nova instância do componente Label.
     */
    public function __construct(
        public ?string $for = null,
        public ?string $label = null,
        public mixed $value = null,
        public bool $required = false,
        public ?string $labelClass = null,
    ) {
        $raw = $this->label ?? $this->value;
        $this->labelText = $raw !== null ? (string) $raw : null;
        $this->defaultClass = $this->labelClass ?? 'small mb-1';
    }

    /**
     * Determina se o label possui texto para exibição.
     */
    public function hasLabel(): bool
    {
        return StringSupport::hasValue($this->labelText);
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.misc.label');
    }
}
