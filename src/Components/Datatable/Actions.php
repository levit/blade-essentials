<?php

declare(strict_types=1);

namespace Select\Components\Components\Datatable;

use Select\Components\Enums\IconeEnum;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Ações em Linhas de Tabelas (Actions).
 *
 * Renderiza o conjunto de botões rápidos de ação por registro (visualizar, editar, excluir)
 * com ícones, tooltips e rotas resolvidas dinamicamente via prefixo ou URLs explícitas.
 */
class Actions extends Component
{
    public string|int|null $resolvedRecordId;

    public string $resolvedLib;

    public ?string $urlView;

    public ?string $urlEdit;

    public ?string $urlDelete;

    /**
     * Cria uma nova instância do componente Actions.
     */
    public function __construct(
        public string $routePrefix = '',
        public string|int|null $recordId = null,
        public string|int|null $id = null,
        public bool $isDisabled = false,
        public string $iconLibrary = 'bootstrap',
        public string $iconView = 'eye',
        public string $iconEdit = 'pencil-square',
        public string $iconDelete = 'trash',
        public string $tooltipView = 'Visualizar',
        public string $tooltipEdit = 'Editar',
        public string $tooltipDelete = 'Excluir',
        public bool $showView = true,
        public bool $showEdit = true,
        public bool $showDelete = true,
        public ?string $viewRoute = null,
        public ?string $editRoute = null,
        public ?string $deleteRoute = null,
    ) {
        $this->resolvedRecordId = $this->recordId ?? $this->id;
        $libEnum = IconeEnum::resolveLibrary('', $this->iconLibrary);
        $this->resolvedLib = $libEnum->value;

        $hasPrefixAndId = $this->routePrefix !== '' && $this->resolvedRecordId !== null;
        $this->urlView = $this->viewRoute ?: ($hasPrefixAndId ? route($this->routePrefix . '.show', $this->resolvedRecordId) : null);
        $this->urlEdit = $this->editRoute ?: ($hasPrefixAndId ? route($this->routePrefix . '.edit', $this->resolvedRecordId) : null);
        $this->urlDelete = $this->deleteRoute ?: ($hasPrefixAndId ? route($this->routePrefix . '.delete', $this->resolvedRecordId) : null);
    }

    /**
     * Determina se há pelo menos uma ação configurada para exibição.
     */
    public function hasActions(): bool
    {
        return (bool) ($this->resolvedRecordId || $this->urlView || $this->urlEdit || $this->urlDelete);
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.datatable.actions');
    }
}
