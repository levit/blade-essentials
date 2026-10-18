<?php

declare(strict_types=1);

namespace Select\Components\Components\Datatable;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Estado Vazio em Tabelas (EmptyState).
 *
 * Renderiza uma linha informando a ausência de registros com ícone ilustrativo
 * e mensagem customizada, cobrindo o colspan especificado.
 */
class EmptyState extends Component
{
    /**
     * Cria uma nova instância do componente EmptyState.
     *
     * @param string|int|null $colspan Quantidade de colunas que a célula deve ocupar.
     * @param string $message Mensagem informativa para listagem vazia.
     * @param string $icon Nome do ícone ilustrativo centralizado.
     */
    public function __construct(
        public string|int|null $colspan = null,
        public string $message = 'Nenhum registro encontrado.',
        public string $icon = 'inbox',
    ) {
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.datatable.empty');
    }
}
