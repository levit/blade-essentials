<?php

declare(strict_types=1);

namespace Select\Components\Components\Misc;

use Select\Components\Enums\BadgeVariantEnum;
use BackedEnum;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Badges e Rótulos do Sistema.
 *
 * Suporta múltiplos estilos visuais (soft, solid, outline), integração com Enums
 * de status, ícones, dots indicadores pulsantes e estados acessíveis.
 */
class Badge extends Component
{
    /**
     * Rótulo ou texto final resolvido.
     */
    public ?string $resolvedLabel = null;

    /**
     * Cor contextual resolvida.
     */
    public string $resolvedColor = 'secondary';

    /**
     * Nome do ícone resolvido.
     */
    public ?string $resolvedIcon = null;

    /**
     * Cor contextual do ponto indicador (dot).
     */
    public string $resolvedDotColor = 'secondary';

    /**
     * Classes CSS completas resolvidas para o badge.
     */
    public string $badgeClasses = '';

    /**
     * Cria uma nova instância do componente Badge.
     *
     * @param string|int|float|null $text Texto/conteúdo do badge.
     * @param string|int|float|null $label Alias para text.
     * @param mixed $value Valor exibido caso text/label não sejam informados.
     * @param mixed $status Status ou Enum para inferência de cor, texto e ícone.
     * @param string|BackedEnum|null $color Cor base (Bootstrap ou cor temática).
     * @param string|BackedEnum $variant Variante visual ('soft', 'solid', 'outline').
     * @param bool $soft Se deve renderizar no padrão soft (fundo translúcido).
     * @param bool $outline Se deve renderizar no padrão outline (somente borda).
     * @param bool $pill Se deve utilizar bordas 100% arredondadas (estilo pílula).
     * @param string|BackedEnum|null $size Tamanho do badge ('sm', 'lg' ou padrão).
     * @param string|null $icon Nome do ícone a ser renderizado.
     * @param string $iconPosition Posição do ícone ('left' ou 'right').
     * @param bool $dot Exibe ponto circular indicador antes do texto.
     * @param string|BackedEnum|null $dotColor Cor do ponto indicador.
     * @param bool $pulse Aplica animação pulsante ao ponto indicador.
     * @param string|null $href URL caso o badge funcione como link clicável.
     * @param string|null $target Alvo do link (_blank, _self, etc.).
     * @param bool $dismissible Se deve exibir botão de fechar.
     * @param string|null $title Tooltip do elemento.
     * @param string|null $role Papel semântico ARIA (ex: status, alert).
     */
    public function __construct(
        public string|int|float|null $text = null,
        public string|int|float|null $label = null,
        public mixed $value = null,
        public mixed $status = null,
        public string|BackedEnum|null $color = 'secondary',
        public string|BackedEnum $variant = 'soft',
        public bool $soft = true,
        public bool $outline = false,
        public bool $pill = false,
        public string|BackedEnum|null $size = null,
        public ?string $icon = null,
        public string $iconPosition = 'left',
        public bool $dot = false,
        public string|BackedEnum|null $dotColor = null,
        public bool $pulse = false,
        public ?string $href = null,
        public ?string $target = null,
        public bool $dismissible = false,
        public ?string $title = null,
        public ?string $role = null,
    ) {
        $this->resolveProperties();
    }

    /**
     * Resolve dados contextuais, cores, estilos e acessibilidade do badge.
     */
    protected function resolveProperties(): void
    {
        // 1. Resolução do texto/rótulo
        $rawLabel = $this->text ?? $this->label ?? $this->value;

        if ($this->status !== null && $rawLabel === null) {
            if (is_object($this->status) && method_exists($this->status, 'label')) {
                $rawLabel = $this->status->label();
            } elseif ($this->status instanceof BackedEnum) {
                $rawLabel = (string) $this->status->value;
            } elseif (is_string($this->status) || is_numeric($this->status)) {
                $rawLabel = (string) $this->status;
            }
        }
        $this->resolvedLabel = $rawLabel !== null ? (string) $rawLabel : null;

        // 2. Resolução da cor contextual
        $resolvedColor = $this->color;
        if ($this->status !== null && ($resolvedColor === 'secondary' || $resolvedColor === null)) {
            if (is_object($this->status)) {
                if (method_exists($this->status, 'badgeColor')) {
                    $resolvedColor = $this->status->badgeColor();
                } elseif (method_exists($this->status, 'color')) {
                    $resolvedColor = $this->status->color();
                } elseif (method_exists($this->status, 'getClass')) {
                    $resolvedColor = $this->status->getClass();
                } elseif (method_exists($this->status, 'badgeClass')) {
                    $rawBadgeClass = (string) $this->status->badgeClass();
                    if (preg_match('/bg-([a-z]+)(?:-soft|-subtle|-lt)?/', $rawBadgeClass, $matches)) {
                        $resolvedColor = $matches[1];
                    }
                }
            }
        }

        if ($resolvedColor instanceof BackedEnum) {
            $resolvedColor = $resolvedColor->value;
        }
        $resolvedColor = is_string($resolvedColor) ? trim($resolvedColor) : 'secondary';
        if ($resolvedColor === '') {
            $resolvedColor = 'secondary';
        }
        $this->resolvedColor = $resolvedColor;

        // 3. Resolução do ícone
        $resolvedIcon = $this->icon;
        if ($resolvedIcon === null && $this->status !== null && is_object($this->status)) {
            if (method_exists($this->status, 'getIcon')) {
                $resolvedIcon = $this->status->getIcon();
            } elseif (method_exists($this->status, 'icon')) {
                $resolvedIcon = $this->status->icon();
            }
        }
        if ($resolvedIcon instanceof BackedEnum) {
            $resolvedIcon = (string) $resolvedIcon->value;
        }
        $this->resolvedIcon = $resolvedIcon;

        // 4. Normalização de variante e tamanho
        $variantValue = $this->variant instanceof BackedEnum ? (string) $this->variant->value : (string) $this->variant;
        $sizeValue = $this->size instanceof BackedEnum ? (string) $this->size->value : ($this->size ? (string) $this->size : null);

        // 5. Determinação de estilo visual (Outline, Solid ou Soft)
        $isOutline = $this->outline || $variantValue === 'outline' || $variantValue === BadgeVariantEnum::OUTLINE->value;
        $isSolid = (!$this->soft && !$isOutline) || $variantValue === 'solid' || $variantValue === BadgeVariantEnum::SOLID->value;

        if ($isOutline) {
            $outlineBorder = ($this->resolvedColor === 'light') ? 'border-secondary-subtle text-secondary' : "border-{$this->resolvedColor} text-{$this->resolvedColor}";
            $styleClass = "bg-transparent border {$outlineBorder}";
        } elseif ($this->resolvedColor === 'light') {
            $styleClass = 'bg-light text-dark border border-light-subtle';
        } elseif ($this->resolvedColor === 'dark') {
            $styleClass = 'bg-dark text-white';
        } elseif ($isSolid) {
            $isDarkText = in_array($this->resolvedColor, ['warning', 'yellow'], true);
            $textColor = $isDarkText ? 'text-dark' : 'text-white';
            $styleClass = trim("bg-{$this->resolvedColor} {$textColor}");
        } else {
            $styleClass = "bg-{$this->resolvedColor}-soft text-{$this->resolvedColor} border border-{$this->resolvedColor}-soft";
        }

        // 6. Formato e tamanho
        $shapeClass = $this->pill ? 'rounded-pill' : 'rounded';
        $sizeClass = match ($sizeValue) {
            'sm' => 'badge-sm px-2 py-0.5',
            'lg' => 'badge-lg px-3 py-1.5',
            default => 'px-2 py-1',
        };

        // 7. Classes combinadas
        $classes = "badge {$styleClass} {$shapeClass} {$sizeClass} fw-semibold d-inline-flex align-items-center";
        if ($this->href) {
            $classes .= ' text-decoration-none lift-sm';
        }
        $this->badgeClasses = $classes;

        // 8. Cor do ponto indicador (dot)
        $dotColor = $this->dotColor ?? $this->resolvedColor;
        if ($dotColor instanceof BackedEnum) {
            $dotColor = $dotColor->value;
        }
        $this->resolvedDotColor = (string) $dotColor;

        // 9. Papel ARIA (acessibilidade)
        $this->role = $this->role ?? ($this->status !== null ? 'status' : null);
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.misc.badge');
    }
}
