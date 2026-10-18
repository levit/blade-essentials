<?php

declare(strict_types=1);

namespace Select\Components\Components\Modal;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Conteúdo Estruturado de Modal (Content).
 *
 * Renderiza o bloco modal-content (cabeçalho, corpo e rodapé com botões de ação),
 * podendo ser utilizado tanto dentro de modal padrão quanto em respostas AJAX parciais.
 */
class Content extends Component
{
    public ?string $id;

    public ?string $labelId;

    public bool $hasIcon;

    public bool $isDarkHeader;

    public string $headerClasses;

    public string $titleColorClass;

    public string $resolvedIconColor;

    public string $closeButtonClass;

    public bool $isForm;

    public bool $hasAutoButtons;

    /**
     * Cria uma nova instância do componente Content de Modal.
     *
     * @param string|null $name Nome do conteúdo (para identificação).
     * @param string|null $id Identificador HTML único do modal-content.
     * @param string|null $labelId Identificador HTML para o título/label de acessibilidade.
     * @param string $title Título exibido no cabeçalho.
     * @param string $titleClass Classes adicionais aplicadas ao título.
     * @param string|null $icon Nome do ícone no cabeçalho.
     * @param string|null $iconColor Cor do ícone do cabeçalho.
     * @param string $iconClass Classes adicionais do ícone.
     * @param string $color Esquema de cor do cabeçalho ('primary', 'light', 'white', 'dark', 'danger', etc.).
     * @param string|null $headerClass Classes CSS customizadas para o modal-header.
     * @param string $bodyClass Classes CSS customizadas para o modal-body.
     * @param string $footerClass Classes CSS customizadas para o modal-footer.
     * @param string $contentClass Classes CSS adicionais aplicadas ao modal-content.
     * @param bool $showHeader Se o cabeçalho deve ser renderizado.
     * @param bool $dismissible Se deve exibir botão de fechar.
     * @param string $closeLabel Texto acessível para o botão de fechar.
     * @param bool $rawBody Se true, não envelopa o slot em uma div modal-body.
     * @param string|null $formAction URL de action caso utilize formulário.
     * @param string $formMethod Método HTTP do formulário.
     * @param string|null $formEnctype Enctype do formulário.
     * @param string|null $cancelLabel Texto do botão cancelar/fechar no rodapé.
     * @param string $cancelColor Cor do botão cancelar.
     * @param string|null $cancelIcon Ícone do botão cancelar.
     * @param string|null $submitLabel Texto do botão de submissão no rodapé.
     * @param string $submitColor Cor do botão de submissão.
     * @param string|null $submitIcon Ícone do botão de submissão.
     */
    public function __construct(
        public ?string $name = null,
        ?string $id = null,
        ?string $labelId = null,
        public string $title = '',
        public string $titleClass = '',
        public ?string $icon = null,
        public ?string $iconColor = null,
        public string $iconClass = 'me-2 fs-5',
        public string $color = 'primary',
        public ?string $headerClass = null,
        public string $bodyClass = '',
        public string $footerClass = 'bg-light border-top',
        public string $contentClass = 'border shadow-lg',
        public bool $showHeader = true,
        public bool $dismissible = true,
        public string $closeLabel = 'Fechar',
        public bool $rawBody = false,
        public ?string $formAction = null,
        public string $formMethod = 'POST',
        public ?string $formEnctype = null,
        public ?string $cancelLabel = null,
        public string $cancelColor = 'secondary',
        public ?string $cancelIcon = null,
        public ?string $submitLabel = null,
        public string $submitColor = 'primary',
        public ?string $submitIcon = null,
    ) {
        $this->id = $id ?? ($this->name ? "content_{$this->name}" : null);
        $this->labelId = $labelId ?? ($this->id ? "{$this->id}Label" : null);

        $this->hasIcon = !empty($this->icon) && trim((string) $this->icon) !== '';

        $customHeader = (string) ($this->headerClass ?? '');
        $this->isDarkHeader = in_array($this->color, ['primary', 'danger', 'success', 'dark', 'info', 'secondary'], true)
            || str_contains($customHeader, 'text-white')
            || str_contains($customHeader, 'bg-primary')
            || str_contains($customHeader, 'bg-dark')
            || str_contains($customHeader, 'bg-danger')
            || str_contains($customHeader, 'bg-success')
            || str_contains($customHeader, 'bg-info');

        $defaultHeaderClass = match ($this->color) {
            'primary'   => 'bg-primary text-white border-bottom',
            'danger'    => 'bg-danger text-white border-bottom',
            'success'   => 'bg-success text-white border-bottom',
            'info'      => 'bg-info text-white border-bottom',
            'dark'      => 'bg-dark text-white border-bottom',
            'secondary' => 'bg-secondary text-white border-bottom',
            'warning'   => 'bg-warning text-dark border-bottom',
            'light'     => 'bg-light text-dark border-bottom',
            'white'     => 'bg-white text-dark border-bottom',
            default     => $this->color ? "bg-{$this->color} text-white border-bottom" : 'border-bottom',
        };

        $this->headerClasses = trim(($this->headerClass ? "{$defaultHeaderClass} {$this->headerClass}" : $defaultHeaderClass));
        $this->titleColorClass = $this->isDarkHeader ? 'text-white' : 'text-dark';
        $this->resolvedIconColor = $this->iconColor ?? ($this->isDarkHeader ? 'white' : ($this->color === 'warning' ? 'dark' : 'primary'));
        $this->closeButtonClass = $this->isDarkHeader ? 'btn-close-white' : '';

        $this->isForm = !empty($this->formAction);
        $this->hasAutoButtons = !empty($this->submitLabel) || !empty($this->cancelLabel);
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.modal.content');
    }
}
