<?php

declare(strict_types=1);

namespace Select\Components\Components\Modal;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Select\Components\Support\StringSupport;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Janela Modal (Modal).
 *
 * Renderiza modal Bootstrap 5 / SB Admin Pro padronizado, gerenciando backdrop,
 * teclado, animações fade, dimensões responsivas e formulários integrados.
 */
class Modal extends Component
{
    public string $id;

    public string $dialogId;

    public string $contentId;

    public string $labelId;

    public string $sizeClass;

    public ?string $bsBackdrop;

    public ?string $bsKeyboard;

    /**
     * Cria uma nova instância do componente Modal.
     *
     * @param string|null $name Nome do modal (utilizado para gerar IDs automáticos).
     * @param string|null $id Identificador HTML único do container modal.
     * @param string|null $dialogId Identificador HTML para modal-dialog.
     * @param string|null $contentId Identificador HTML para modal-content.
     * @param string|null $labelId Identificador HTML para o título/label de acessibilidade.
     * @param string $title Título exibido no cabeçalho do modal.
     * @param string $titleClass Classes adicionais aplicadas ao título.
     * @param string|null $icon Nome do ícone no cabeçalho.
     * @param string|null $iconColor Cor do ícone do cabeçalho.
     * @param string $iconClass Classes adicionais do ícone.
     * @param string|null $size Tamanho do modal ('sm', 'lg', 'xl', 'fullscreen', etc.).
     * @param bool $centered Centralização vertical na tela (modal-dialog-centered).
     * @param bool $scrollable Habilita rolagem interna de conteúdo longo (modal-dialog-scrollable).
     * @param bool $fade Ativa efeito de transição suave ao abrir e fechar.
     * @param bool $static Backdrop estático que bloqueia fechamento ao clicar fora.
     * @param string|bool|null $backdrop Valor explícito para data-bs-backdrop.
     * @param string|bool|null $keyboard Valor explícito para data-bs-keyboard.
     * @param string $color Esquema de cor do cabeçalho ('primary', 'light', 'white', 'dark', 'danger', etc.).
     * @param string|null $headerClass Classes CSS customizadas para o modal-header.
     * @param string $bodyClass Classes CSS customizadas para o modal-body.
     * @param string $footerClass Classes CSS customizadas para o modal-footer.
     * @param string $contentClass Classes CSS customizadas para o modal-content.
     * @param string $dialogClass Classes CSS customizadas para o modal-dialog.
     * @param bool $showHeader Se o cabeçalho deve ser renderizado.
     * @param bool $dismissible Se deve exibir botão de fechar no canto superior direito.
     * @param string $closeLabel Texto acessível para o botão de fechar.
     * @param bool $rawBody Se true, não envelopa o slot em uma div modal-body padrão.
     * @param string|null $formAction URL de action caso o modal seja um formulário direto.
     * @param string $formMethod Método HTTP do formulário (padrão: 'POST').
     * @param string|null $formEnctype Enctype do formulário (ex: multipart/form-data).
     * @param string|null $cancelLabel Texto do botão cancelar/fechar no rodapé.
     * @param string $cancelColor Cor do botão cancelar (padrão: 'secondary').
     * @param string|null $cancelIcon Ícone do botão cancelar.
     * @param string|null $submitLabel Texto do botão de submissão no rodapé.
     * @param string $submitColor Cor do botão de submissão (padrão: 'primary').
     * @param string|null $submitIcon Ícone do botão de submissão.
     */
    public function __construct(
        public ?string $name = null,
        ?string $id = null,
        ?string $dialogId = null,
        ?string $contentId = null,
        ?string $labelId = null,
        public string $title = '',
        public string $titleClass = '',
        public ?string $icon = null,
        public ?string $iconColor = null,
        public string $iconClass = 'me-2 fs-5',
        public ?string $size = null,
        public bool $centered = true,
        public bool $scrollable = true,
        public bool $fade = true,
        public bool $static = false,
        public string|bool|null $backdrop = null,
        public string|bool|null $keyboard = null,
        public string $color = 'primary',
        public ?string $headerClass = null,
        public string $bodyClass = '',
        public string $footerClass = 'bg-light border-top',
        public string $contentClass = 'border shadow-lg',
        public string $dialogClass = '',
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
        $this->id = StringSupport::hasValue($id)
            ? (string) $id
            : ($this->name ? "modal_{$this->name}" : ComponentIdPrefixEnum::MODAL->generateId());
        $this->dialogId = $dialogId ?? "{$this->id}Dialog";
        $this->contentId = $contentId ?? "{$this->id}Content";
        $this->labelId = $labelId ?? "{$this->id}Label";

        $this->sizeClass = match ($this->size) {
            'sm' => 'modal-sm',
            'lg' => 'modal-lg',
            'xl' => 'modal-xl',
            'fullscreen' => 'modal-fullscreen',
            'fullscreen-sm-down' => 'modal-fullscreen-sm-down',
            'fullscreen-md-down' => 'modal-fullscreen-md-down',
            'fullscreen-lg-down' => 'modal-fullscreen-lg-down',
            'fullscreen-xl-down' => 'modal-fullscreen-xl-down',
            'fullscreen-xxl-down' => 'modal-fullscreen-xxl-down',
            default => $this->size ? (str_starts_with($this->size, 'modal-') ? $this->size : "modal-{$this->size}") : '',
        };

        if ($this->backdrop !== null) {
            $this->bsBackdrop = is_bool($this->backdrop) ? ($this->backdrop ? 'true' : 'false') : (string) $this->backdrop;
        } else {
            $this->bsBackdrop = $this->static ? 'static' : null;
        }

        if ($this->keyboard !== null) {
            $this->bsKeyboard = is_bool($this->keyboard) ? ($this->keyboard ? 'true' : 'false') : (string) $this->keyboard;
        } else {
            $this->bsKeyboard = $this->static ? 'false' : null;
        }
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.modal');
    }
}
