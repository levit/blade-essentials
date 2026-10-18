<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Select\Components\Support\StringSupport;
use BackedEnum;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

/**
 * Componente de Campo de Seleção Múltipla com Checkboxes (Field SelectCheckbox).
 *
 * Renderiza um dropdown no estilo select do Bootstrap que encapsula caixas de seleção
 * nativas, suportando contagem inteligente de itens selecionados, busca rápida,
 * ações de selecionar todos / limpar e preservação de valores antigos (old()).
 */
class SelectCheckbox extends BaseFieldComponent
{
    /**
     * Nome do campo sanitizado sem delimitadores de array.
     */
    public string $cleanName = '';

    /**
     * Nome do campo garantido no formato de array para envio múltiplo (ex: 'itens[]').
     */
    public string $arrayNameField = '';

    /**
     * Indica se a interação com o componente está desabilitada.
     */
    public bool $isDisabled = false;

    /**
     * Lista de valores pré-selecionados convertidos em strings para conferência.
     */
    public array $selectedStrings = [];

    /**
     * Quantidade total de opções disponíveis no dropdown.
     */
    public int $totalOptions = 0;

    /**
     * Quantidade de itens atualmente marcados como selecionados.
     */
    public int $selectedCount = 0;

    /**
     * Rótulos dos itens selecionados para exibição no resumo do botão.
     */
    public array $selectedLabels = [];

    /**
     * Texto textual inicial exibido dentro do botão do dropdown.
     */
    public string $initialText = '';

    /**
     * Classes CSS finais resolvidas para o botão toggle do dropdown.
     */
    public string $buttonClasses = '';

    /**
     * Construtor do componente SelectCheckbox.
     *
     * @param string|null $nameField Nome do campo (ex: 'perfis' ou 'perfis[]').
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado.
     * @param string|null $label Rótulo exibido para o componente.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $inputClass Classes CSS base do elemento de entrada.
     * @param mixed $options Array associativo [valor => texto] ou Collection com as opções.
     * @param mixed $selected Valores pré-selecionados (array, Collection ou valor escalar).
     * @param string $placeholder Texto exibido quando nenhuma opção estiver marcada.
     * @param int $maxDisplay Máximo de itens listados textualmente antes de abreviar para "X selecionados".
     * @param string $allSelectedText Texto quando todas as opções estiverem marcadas (> maxDisplay).
     * @param bool $showActions Se exibe atalhos "Selecionar Todos" e "Limpar".
     * @param string $selectAllLabel Rótulo da ação Selecionar Todos.
     * @param string $clearLabel Rótulo da ação Limpar.
     * @param string $selectClass Classes CSS base do botão dropdown.
     * @param string $menuClass Classes CSS adicionais para a lista do dropdown menu.
     * @param string $menuMaxHeight Altura máxima com scroll para o dropdown menu.
     * @param bool|null $searchable Se habilita campo de pesquisa rápida dentro do menu.
     * @param string $searchPlaceholder Texto de placeholder do campo de pesquisa.
     * @param bool $useOldValue Se deve recuperar valores da sessão via old().
     * @param mixed $value Alias para selected.
     * @param bool|null $required Flag explícita de obrigatoriedade.
     * @param mixed $model Model Eloquent para inferência.
     * @param mixed $form FormRequest ou formulário para inferência.
     * @param bool $readonly Se o campo é apenas leitura.
     * @param bool $disabled Se o campo está desabilitado.
     * @param bool $onlyView Se o campo está em modo de visualização estrita.
     */
    public function __construct(
        ?string $nameField = null,
        ?string $name = null,
        ?string $id = null,
        ?string $label = null,
        string $labelClass = 'small mb-1',
        string $inputClass = 'form-select',
        public mixed $options = [],
        public mixed $selected = [],
        public string $placeholder = 'Selecione as opções',
        public int $maxDisplay = 2,
        public string $allSelectedText = 'Todos selecionados',
        public bool $showActions = true,
        public string $selectAllLabel = 'Selecionar Todos',
        public string $clearLabel = 'Limpar',
        public string $selectClass = 'form-select text-start',
        public string $menuClass = '',
        public string $menuMaxHeight = '260px',
        public ?bool $searchable = null,
        public string $searchPlaceholder = 'Buscar opção...',
        bool $useOldValue = true,
        mixed $value = null,
        ?bool $required = null,
        mixed $model = null,
        mixed $form = null,
        bool $readonly = false,
        bool $disabled = false,
        bool $onlyView = false,
    ) {
        parent::__construct(
            nameField: $nameField,
            name: $name,
            id: $id,
            label: $label,
            labelClass: $labelClass,
            inputClass: $inputClass,
            useOldValue: $useOldValue,
            value: $value,
            required: $required,
            model: $model,
            form: $form,
            readonly: $readonly,
            disabled: $disabled,
            onlyView: $onlyView,
        );

        $resolvedRawName = (string) ($this->nameField ?? '');
        $this->cleanName = str_replace(['[]', '[', ']'], '', $resolvedRawName);
        $this->arrayNameField = str_ends_with($resolvedRawName, '[]') ? $resolvedRawName : "{$resolvedRawName}[]";

        $this->isDisabled = $this->disabled || $this->readonly;
        if ($this->isDisabled) {
            $this->isRequired = false;
        }
        $this->required = $this->isRequired;

        // Normalização das opções
        if ($this->options instanceof Collection) {
            $this->options = $this->options->toArray();
        } elseif (!is_array($this->options)) {
            $this->options = (array) $this->options;
        }

        // Normalização dos valores selecionados
        $baseSelected = $this->value ?? $this->selected ?? [];
        if ($this->useOldValue && $this->cleanName !== '' && old($this->cleanName) !== null) {
            $rawSelected = old($this->cleanName);
        } else {
            $rawSelected = $baseSelected;
        }

        if ($rawSelected instanceof Collection) {
            $selectedList = $rawSelected->toArray();
        } elseif (is_array($rawSelected)) {
            $selectedList = $rawSelected;
        } elseif (StringSupport::hasValue($rawSelected)) {
            $selectedList = [$rawSelected];
        } else {
            $selectedList = [];
        }

        $this->selectedStrings = array_map(
            static fn(mixed $v): string => (string) ($v instanceof BackedEnum ? $v->value : $v),
            $selectedList
        );

        $this->totalOptions = count($this->options);
        $this->selectedCount = 0;
        $this->selectedLabels = [];

        foreach ($this->options as $optVal => $optTxt) {
            $strVal = (string) ($optVal instanceof BackedEnum ? $optVal->value : $optVal);
            if (in_array($strVal, $this->selectedStrings, true)) {
                $this->selectedCount++;
                $this->selectedLabels[] = (string) $optTxt;
            }
        }

        // Determinação do texto inicial do botão
        if ($this->selectedCount === 0) {
            $this->initialText = $this->placeholder;
        } elseif ($this->selectedCount === $this->totalOptions && $this->totalOptions > $this->maxDisplay) {
            $this->initialText = $this->allSelectedText;
        } elseif ($this->selectedCount <= $this->maxDisplay) {
            $this->initialText = implode(', ', $this->selectedLabels);
        } else {
            $this->initialText = "{$this->selectedCount} selecionados";
        }

        $this->buttonClasses = trim(
            $this->selectClass . ' d-flex justify-content-between align-items-center' . ($this->hasError ? ' is-invalid' : '')
        );
    }

    /**
     * Retorna o prefixo padrão para o ID HTML do componente.
     */
    protected function getIdPrefix(): string
    {
        return 'select_chk';
    }

    /**
     * Retorna a view para renderização do componente.
     */
    public function render(): View
    {
        return view('components.field.select-checkbox');
    }
}
