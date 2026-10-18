<?php

declare(strict_types=1);

namespace Select\Components\Components\Misc;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Select\Components\Enums\IconeEnum;
use Select\Components\Support\StringSupport;
use BackedEnum;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Ícones do Sistema.
 *
 * Suporta múltiplas bibliotecas (Bootstrap Icons, Feather, FontAwesome, Ionicons, Material, Remixicon, Heroicons)
 * com resolução dinâmica, cores contextuais, tamanhos padronizados e carregamento único de dependências.
 */
class Icone extends Component
{
    /**
     * Instância do IconeEnum resolvida para a biblioteca.
     */
    public IconeEnum $enumLib;

    /**
     * Nome do ícone sem prefixos de biblioteca.
     */
    public string $iconName;

    /**
     * Caminho do arquivo CSS da biblioteca (se houver).
     */
    public ?string $cssFile;

    /**
     * Caminho do arquivo JS da biblioteca (se houver).
     */
    public ?string $jsFile;

    /**
     * Classes CSS completas resolvidas.
     */
    public string $computedClasses;

    /**
     * Rótulo de acessibilidade / tooltip traduzido.
     */
    public string $tooltipText;

    /**
     * Texto alternativo para acessibilidade.
     */
    public string $altText;

    /**
     * Indica se o ícone possui nome válido a ser exibido.
     */
    public bool $hasIcon;

    /**
     * Cria uma nova instância do componente Icone.
     */
    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public string|BackedEnum|null $library = null,
        public string|BackedEnum $icon = '',
        public ?string $color = null,
        public string $class = '',
        public ?string $title = null,
        public ?string $alt = null,
        public ?string $size = null,
    ) {
        $rawIcon = $this->icon instanceof BackedEnum ? (string) $this->icon->value : (string) $this->icon;
        $this->enumLib = IconeEnum::resolveLibrary($rawIcon, $this->library);
        $this->hasIcon = ! empty(trim($rawIcon));
        $this->iconName = IconeEnum::getNameWithoutPrefix($rawIcon);
        $this->cssFile = $this->enumLib->cssFile();
        $this->jsFile = $this->enumLib->jsFile();

        $hasExplicitName = StringSupport::hasValue($this->name);
        $resolvedName = $this->name ?? StringSupport::nameRandom();
        $this->name = $resolvedName;
        $this->id = StringSupport::hasValue($this->id)
            ? (string) $this->id
            : ($hasExplicitName ? "ico_{$resolvedName}_id" : ComponentIdPrefixEnum::ICON->generateId());

        $defaultLabel = IconeEnum::translateAction($this->iconName);
        $this->tooltipText = $this->title ?? $defaultLabel;
        $this->altText = $this->alt ?? $this->tooltipText;

        $textColor = empty($this->color) ? '' : "text-{$this->color}";
        $sizeClass = $this->size ? "icon-{$this->size}" : '';
        $this->computedClasses = trim("{$textColor} {$sizeClass} {$this->class}");
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.misc.icone');
    }
}
