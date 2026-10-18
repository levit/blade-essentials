<?php

declare(strict_types=1);

namespace Select\Components\Components\Datatable;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Seção de Filtros de Tabelas (Filters).
 *
 * Renderiza um painel expansível/colapsável de busca textual, seleção de status
 * baseada em enums e intervalos de datas com botões de filtrar e limpar.
 */
class Filters extends Component
{
    /**
     * Opções de status formatadas para o select de status.
     *
     * @var array<string|int, string>
     */
    public array $statusOptions = [];

    /**
     * Estilo inline CSS inicial de visibilidade.
     */
    public string $displayStyle;

    /**
     * URL resolvida para limpeza de filtros aplicados.
     */
    public string $resolvedClearRoute;

    /**
     * Cria uma nova instância do componente Filters.
     *
     * @param string $action URL de ação do formulário de filtros (submissão GET).
     * @param string|null $clearRoute URL para redefinição/limpeza dos filtros aplicados.
     * @param string $DivFilterId ID do elemento container dos filtros.
     * @param string $ButtonToggleId ID do botão que alterna a visibilidade dos filtros.
     * @param array $statuses Lista de instâncias de Enum com método label() para filtro de status.
     * @param bool $iniciarOcultado Se a seção de filtros inicia colapsada/oculta.
     * @param bool $mostrarDataInicial Se exibe o campo datepicker de data inicial.
     * @param bool $mostrarDataFinal Se exibe o campo datepicker de data final.
     * @param bool $mostrarBuscaTexto Se exibe o campo de busca por texto livre.
     */
    public function __construct(
        public string $action,
        public ?string $clearRoute = null,
        public string $DivFilterId = 'filterSectionId',
        public string $ButtonToggleId = 'toggleFilterBtnId',
        public array $statuses = [],
        public bool $iniciarOcultado = true,
        public bool $mostrarDataInicial = false,
        public bool $mostrarDataFinal = false,
        public bool $mostrarBuscaTexto = true,
    ) {
        $this->displayStyle = $this->iniciarOcultado ? 'none' : 'block';

        foreach ($this->statuses as $status) {
            if (is_object($status) && method_exists($status, 'label')) {
                $this->statusOptions[$status->value ?? $status->name] = $status->label();
            } elseif (is_string($status) || is_int($status)) {
                $this->statusOptions[$status] = (string) $status;
            }
        }

        $this->resolvedClearRoute = $this->clearRoute ?? (str_contains($this->action, '?') ? $this->action . '&limpar=1' : $this->action . '?limpar=1');
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.datatable.filters');
    }
}
