<?php

declare(strict_types=1);

namespace Select\Components\Components\Misc;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Universal de Imagem do Sistema.
 *
 * Renderiza elementos de imagem de forma responsiva, compatível com HTTP/HTTPS dinâmico,
 * suporte a dimensões inline, lazy loading e fallback gracioso sem quebrar o layout.
 */
class Image extends Component
{
    /**
     * URL resolvida final para exibição na tag img.
     */
    public string $resolvedSrc;

    /**
     * Estilos CSS inline calculados a partir de width e height.
     */
    public string $inlineStyle;

    /**
     * Cria uma nova instância do componente Image.
     */
    public function __construct(
        public string $src = '',
        public string $alt = '',
        public string|int|null $width = null,
        public string|int|null $height = null,
        public ?string $fallbackText = null,
        public bool $lazy = false,
    ) {
        $resolved = $this->src;
        if ($resolved !== '' && ! str_starts_with($resolved, 'http://') && ! str_starts_with($resolved, 'https://') && ! str_starts_with($resolved, 'data:')) {
            $resolved = asset(ltrim($resolved, '/'), request()->isSecure());
        }
        $this->resolvedSrc = $resolved;

        $styles = [];
        if ($this->height !== null) {
            $styles[] = "height: {$this->height};";
        }
        if ($this->width !== null) {
            $styles[] = "width: {$this->width};";
        }
        $this->inlineStyle = implode(' ', $styles);
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.misc.image');
    }
}
