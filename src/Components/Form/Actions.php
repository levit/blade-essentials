<?php

declare(strict_types=1);

namespace Select\Components\Components\Form;

use Select\Components\Support\Forms\ButtonBuilder;
use Select\Components\Support\Forms\FormBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Throwable;

/**
 * Componente Blade para Seção de Ações de Formulário (Actions).
 *
 * Renderiza o conjunto de botões de ação vinculados ao FormBuilder (salvar, cancelar, voltar, etc.)
 * com resolução dinâmica de rotas, chaves primárias e filtros de sessão preservados.
 */
class Actions extends Component
{
    /**
     * Cria uma nova instância do componente Actions de Formulário.
     *
     * @param FormBuilder|null $form Instância do FormBuilder contendo os botões configurados.
     * @param string $titulo Rótulo ou título da seção de ações (padrão: 'Opções').
     * @param bool $useIconHeader Se deve exibir ícone no cabeçalho das ações.
     */
    public function __construct(
        public ?FormBuilder $form = null,
        public string $titulo = 'Opções',
        public bool $useIconHeader = false,
    ) {
    }

    /**
     * Retorna a lista de botões configurados no formulário ativo.
     *
     * @return array<ButtonBuilder>
     */
    public function buttons(): array
    {
        return $this->form?->getButtons() ?? [];
    }

    /**
     * Resolve a URL ou rota de destino para um determinado botão de ação.
     */
    public function resolveButtonRoute(ButtonBuilder $button): ?string
    {
        if ($this->form === null) {
            return null;
        }

        $action = $button->getAction();
        if ($action === null || $action === '') {
            return null;
        }

        if (str_starts_with($action, 'http://') || str_starts_with($action, 'https://') || str_starts_with($action, '/')) {
            return $action;
        }

        $baseRoute = (string) $this->form->getBaseRoute();
        $sessionFiltersKey = str_replace('-', '_', $baseRoute) . '_filtros';

        $primaryKey = null;
        try {
            $primaryKey = $this->form->getPrimaryKey();
        } catch (Throwable) {
            $primaryKey = null;
        }

        if ($button->getUsePrimaryKey() && !empty($primaryKey)) {
            return route("{$baseRoute}.{$action}", $primaryKey);
        }

        if ($action === 'list' && session()->has($sessionFiltersKey)) {
            return route("{$baseRoute}.list", session($sessionFiltersKey));
        }

        return route("{$baseRoute}.{$action}");
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.form.actions');
    }
}
