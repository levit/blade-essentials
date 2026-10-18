<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Select\Components\Enums\IconeEnum;
use Select\Components\Support\StringSupport;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente de Botão de Ação (Field Button).
 *
 * Renderiza um botão (<button>) ou link estilizado (<a>) com suporte a ícones unificados,
 * variantes de cores, tamanhos, tooltips acessíveis, estados de desabilitação e rotas.
 */
class Button extends Component
{
    /**
     * Identificador HTML (id) do botão.
     */
    public string $id;

    /**
     * Texto do tooltip acessível.
     */
    public string $tooltipText;

    /**
     * Texto alternativo para leitores de tela / acessibilidade.
     */
    public string $altText;

    /**
     * Indica se o elemento deve ser renderizado como tag <button>.
     */
    public bool $isButton;

    /**
     * Indica se o botão é uma variante de fechar (btn-close).
     */
    public bool $isClose;

    /**
     * Tipo do botão HTML (button, submit, reset).
     */
    public string $buttonType;

    /**
     * Classe CSS de cor do Bootstrap (ex: btn-primary).
     */
    public string $btnColor;

    /**
     * Classe CSS de tamanho do Bootstrap (ex: btn-sm, btn-lg).
     */
    public string $sizeClass;

    /**
     * Classe CSS base do botão (btn ou vazio para close).
     */
    public string $btnBaseClass;

    /**
     * Classe CSS específica para botão de fechar (btn-close).
     */
    public string $closeClass;

    /**
     * Classes CSS finais resolvidas para o ícone interno.
     */
    public string $finalIconClass = '';

    /**
     * Tecla única ou combinação de atalho do teclado (ex: 'n', 's', 'Alt+N').
     */
    public ?string $shortcut = null;

    /**
     * Indica se o componente Kbd deve ser exibido visualmente no botão.
     */
    public bool $showShortcut = true;

    /**
     * Modificador padrão do atalho (ex: 'Alt', 'Ctrl').
     */
    public string $shortcutModifier = 'Alt';

    /**
     * Tecla única mapeada para o atributo nativo accesskey.
     */
    public ?string $resolvedAccessKey = null;

    /**
     * Identificador do atalho completo para o atributo data-shortcut (ex: 'alt+n').
     */
    public ?string $resolvedShortcut = null;

    /**
     * Texto formatado do atalho para o componente Kbd (ex: 'Alt+N').
     */
    public ?string $resolvedShortcutLabel = null;

    /**
     * Construtor do componente Button.
     *
     * @param string|null $name Nome do botão (name attribute).
     * @param string|null $label Texto exibido no botão.
     * @param string|null $id ID HTML personalizado.
     * @param string|null $icon Identificador do ícone (ex: 'eye', 'trash', 'bi-search').
     * @param string|null $iconLibrary Biblioteca do ícone (feather, bootstrap, etc.).
     * @param string|null $iconColor Cor do ícone.
     * @param string $iconClass Classes CSS adicionais para o ícone.
     * @param string|null $iconSize Tamanho do ícone (sm, md, lg, xl, 2x, etc.).
     * @param string $class Classes CSS adicionais para o botão.
     * @param string|null $color Cor base do botão (primary, secondary, danger, etc.).
     * @param string|null $size Tamanho do botão (sm, md, lg).
     * @param string $type Tipo do botão (button, submit, reset, link, close).
     * @param string $route URL ou rota acionada ao clicar.
     * @param string|null $href Alias para route.
     * @param string|null $link Alias para route.
     * @param string|null $routeMethod Método HTTP para envio de formulário (POST, PUT, DELETE, etc.).
     * @param string $tooltip Texto do tooltip explicativo.
     * @param string|null $title Título alternativo / tooltip.
     * @param string|null $alt Texto alternativo para acessibilidade.
     * @param bool $isDisabled Se o botão está desabilitado para cliques.
     * @param string|null $nameButton Alias de compatibilidade para name.
     * @param string|null $shortcut Tecla única ou atalho de teclado (ex: 'n', 's', 'Alt+N').
     * @param bool $showShortcut Se deve exibir o atalho visualmente dentro do botão.
     * @param string $shortcutModifier Modificador padrão do atalho ('Alt', 'Ctrl').
     */
    public function __construct(
        public ?string $name = null,
        public ?string $label = '',
        ?string $id = null,
        public ?string $icon = null,
        public ?string $iconLibrary = null,
        public ?string $iconColor = null,
        public string $iconClass = '',
        public ?string $iconSize = null,
        public string $class = 'me-1',
        public ?string $color = null,
        public ?string $size = null,
        public string $type = 'button',
        public string $route = '',
        public ?string $href = null,
        public ?string $link = null,
        public ?string $routeMethod = null,
        public string $tooltip = '',
        public ?string $title = null,
        public ?string $alt = null,
        public bool $isDisabled = false,
        public ?string $nameButton = null,
        ?string $shortcut = null,
        bool $showShortcut = true,
        string $shortcutModifier = 'Alt',
        public ?string $rotulo = null,
        public ?string $icone = null,
        public ?string $variant = null,
    ) {
        $this->label = StringSupport::hasValue($this->label) ? $this->label : ($this->rotulo ?? '');
        $this->icon = $this->icon ?? $this->icone;
        $this->color = $this->color ?? $this->variant;
        $this->shortcut = $shortcut;
        $this->showShortcut = $showShortcut;
        $this->shortcutModifier = $shortcutModifier;
        $hasExplicitName = StringSupport::hasValue($this->name) || StringSupport::hasValue($this->nameButton);
        $this->name = $this->name ?? ($this->nameButton ?? StringSupport::nameRandom());
        $this->id = StringSupport::hasValue($id)
            ? (string) $id
            : ($hasExplicitName ? "btn_{$this->name}_id" : ComponentIdPrefixEnum::BUTTON->generateId());
        $this->route = $this->route !== '' ? $this->route : ((string) ($this->href ?: ($this->link ?: '')));

        // Resolução unificada de tooltip e texto acessível em português
        $this->tooltipText = $this->tooltip ?: ($this->title ?: ($this->label ?: ($this->icon ? IconeEnum::translateAction($this->icon) : '')));
        $this->altText = $this->alt ?: $this->tooltipText;

        $hasExplicitBtnClose = (bool) preg_match('/(^|\s)btn-close(\s|$)/', $this->class);
        $this->isClose = $this->type === 'close' || str_contains($this->class, 'btn-close');
        $this->isButton = in_array($this->type, ['button', 'submit', 'reset', 'close'], true);
        $this->btnColor = $this->color ? "btn-{$this->color}" : '';
        $this->sizeClass = $this->isClose
            ? ''
            : ($this->size
                ? (in_array($this->size, ['md', 'default', 'normal'], true) ? '' : "btn-{$this->size}")
                : (str_contains($this->class, 'btn-lg') || str_contains($this->class, 'btn-md')
                    ? ''
                    : 'btn-sm'));
        $this->btnBaseClass = $this->isClose ? '' : 'btn';
        $this->buttonType = $this->type === 'close' ? 'button' : $this->type;
        $this->closeClass = $this->isClose && !$hasExplicitBtnClose ? 'btn-close' : '';

        if ($this->isClose && $this->class === 'me-1') {
            $this->class = '';
        }

        if ($this->isDisabled) {
            $this->route = '';
        }

        $this->routeMethod = $this->routeMethod ? strtoupper($this->routeMethod) : null;

        if (StringSupport::hasValue($this->icon)) {
            $iconCls = $this->iconClass;
            $iconCls = (string) preg_replace('/\b(mt-\d+|my-\d+|mb-\d+)\b/', '', $iconCls);
            if (!empty($this->label) && !str_contains($iconCls, 'me-') && !str_contains($iconCls, 'm-')) {
                $iconCls = trim("me-1 {$iconCls}");
            }
            $this->finalIconClass = trim($iconCls);
        }

        if (StringSupport::hasValue($this->shortcut)) {
            $rawShortcut = trim((string) $this->shortcut);
            $lower = strtolower($rawShortcut);

            if (str_contains($lower, '+')) {
                $parts = explode('+', $rawShortcut);
                $keyPart = end($parts);
                $this->resolvedAccessKey = strtolower(trim((string) $keyPart));
                $this->resolvedShortcut = strtolower(implode('+', array_map('trim', $parts)));
                $this->resolvedShortcutLabel = implode('+', array_map('ucfirst', array_map('trim', $parts)));
            } else {
                $this->resolvedAccessKey = strtolower($rawShortcut);
                $modifier = ucfirst(trim($this->shortcutModifier ?: 'Alt'));
                $keyFormatted = strtoupper($rawShortcut);
                $this->resolvedShortcut = strtolower("{$modifier}+{$this->resolvedAccessKey}");
                $this->resolvedShortcutLabel = "{$modifier}+{$keyFormatted}";
            }

            // Enriquecer tooltip e altText se não contiverem o atalho
            if (!empty($this->resolvedShortcutLabel) && !str_contains($this->tooltipText, $this->resolvedShortcutLabel)) {
                $this->tooltipText .= " ({$this->resolvedShortcutLabel})";
                $this->altText .= " ({$this->resolvedShortcutLabel})";
            }
        }
    }

    /**
     * Retorna a view para renderização do componente.
     */
    public function render(): View
    {
        return view('components.field.button');
    }
}
