<?php

declare(strict_types=1);

namespace Select\Components\Components\Datatable;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Select\Components\Support\StringSupport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

/**
 * Componente Blade para Tabelas de Dados (DataTable).
 *
 * Envelopa listagens tabulares responsivas com suporte opcional a card,
 * paginação integrada, ordenação e integração com Simple-DataTables client-side.
 */
class Table extends Component
{
    /**
     * Flag indicando se a listagem está vazia.
     */
    public bool $isEmpty;

    /**
     * Configuração de colunas para o simple-datatables.
     *
     * @var array<int, array<string, mixed>>
     */
    public array $columnsJs = [];

    /**
     * Opções default computadas para o simple-datatables.
     *
     * @var array<string, mixed>
     */
    public array $defaultOptions = [];

    /**
     * Cria uma nova instância do componente Table.
     *
     * @param string|null $id Identificador HTML da tabela.
     * @param array|Collection $headers Lista de cabeçalhos de coluna e suas configurações.
     * @param mixed $items Coleção ou paginador de itens exibidos na listagem.
     * @param int|null $qtdRegistros Quantidade explícita de registros (se nulo, computado).
     * @param string $emptyMessage Mensagem de feedback caso a tabela esteja vazia.
     * @param array $datatableOptions Opções adicionais de inicialização do simple-datatables.
     * @param string $class Classes CSS adicionais aplicadas ao elemento table.
     * @param bool $paginacao Ativação da paginação client-side via simple-datatables.
     * @param int $registrosPorPagina Quantidade padrão de itens por página na paginação.
     * @param bool $ordenavel Se as colunas permitem ordenação interativa.
     * @param bool $card Envelopa a tabela em um container de Card Bootstrap.
     * @param bool $jsDatatable Se deve carregar e inicializar a biblioteca simple-datatables.
     * @param string|null $tbodyId Atributo id para a tag tbody.
     * @param string|null $theadClass Classes CSS customizadas para a tag thead.
     * @param string $responsiveClass Classes CSS do container responsivo (padrão: 'd-inline-block w-100 table-responsive').
     * @param string|null $responsiveStyle Estilos CSS inline do container responsivo.
     */
    public function __construct(
        public ?string $id = null,
        public array|Collection $headers = [],
        public mixed $items = null,
        public ?int $qtdRegistros = null,
        public string $emptyMessage = 'Nenhum registro encontrado.',
        public array $datatableOptions = [],
        public string $class = '',
        public bool $paginacao = false,
        public int $registrosPorPagina = 15,
        public bool $ordenavel = false,
        public bool $card = true,
        public bool $jsDatatable = true,
        public ?string $tbodyId = null,
        public ?string $theadClass = null,
        public string $responsiveClass = 'd-inline-block w-100 table-responsive',
        public ?string $responsiveStyle = null,
    ) {
        $this->id = ComponentIdPrefixEnum::DATATABLE->generateId($this->id);
        $this->headers = $this->headers instanceof Collection ? $this->headers->all() : (array) $this->headers;

        if ($this->qtdRegistros === null && $this->items !== null) {
            $this->qtdRegistros = is_countable($this->items) ? count($this->items) : 0;
        }

        $this->isEmpty = ($this->qtdRegistros !== null && $this->qtdRegistros === 0);

        $this->columnsJs = $this->buildColumnsJs();
        $this->defaultOptions = $this->buildDefaultOptions();
    }

    /**
     * Constrói a configuração de colunas para o simple-datatables.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function buildColumnsJs(): array
    {
        $columns = [];

        foreach ($this->headers as $index => $header) {
            if (!is_array($header)) {
                $header = ['label' => (string) $header];
            }

            $columns[] = [
                'select' => $index,
                'width' => $header['width'] ?? 'auto',
                'type' => $header['type'] ?? 'string',
                'sortable' => $this->ordenavel ? (isset($header['type']) && in_array($header['type'], ['string', 'number', 'date'], true)) : false,
            ];
        }

        return $columns;
    }

    /**
     * Constrói as opções default do simple-datatables.
     *
     * @return array<string, mixed>
     */
    protected function buildDefaultOptions(): array
    {
        $options = [
            'searchable' => false,
            'paging' => $this->paginacao,
            'perPage' => $this->paginacao ? $this->registrosPorPagina : 0,
            'sortable' => $this->ordenavel,
            'labels' => [
                'placeholder' => 'Pesquisar...',
                'perPage' => 'registros por página',
                'noRows' => 'Nenhum registro encontrado',
                'info' => 'Mostrando {start} até {end} de {rows} registros',
                'searchTitle' => 'Pesquisar registros',
                'pageTitle' => 'Página {page}',
                'noResults' => 'Nenhum resultado encontrado em sua pesquisa',
                'sortBy' => 'Ordenar por',
                'sortAsc' => 'Ordem crescente',
                'sortDesc' => 'Ordem decrescente',
            ],
        ];

        if (!empty($this->columnsJs)) {
            $options['columns'] = $this->columnsJs;
        }

        return $options;
    }



    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.datatable.table');
    }
}
