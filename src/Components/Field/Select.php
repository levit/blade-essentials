<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Select\Components\Support\StringSupport;
use BackedEnum;
use Illuminate\Contracts\View\View;

/**
 * Componente de Campo de Caixa de Seleção / Dropdown (Field Select).
 *
 * Renderiza um elemento HTML <select> com rótulo, suporte a opções dinâmicas via API,
 * encadeamento de selects dependentes (ex: UF -> Cidades), resolução de valores antigos (old()),
 * suporte a Enums e modo estrito de visualização / somente leitura formatado.
 */
class Select extends BaseFieldComponent
{
    /**
     * Nome do campo resolvido para submissão.
     */
    public ?string $resolvedName = null;

    /**
     * Nome do campo sanitizado sem notação de array (ex: 'uf' para 'uf[]').
     */
    public string $cleanName = '';

    /**
     * Valor selecionado resolvido (com precedência de old()).
     */
    public mixed $selectedValue = null;

    /**
     * Indica se o tamanho do select é numérico/HTML (size attribute) ou classe do Bootstrap (sm/lg).
     */
    public bool $isHtmlSize = false;

    /**
     * Classes CSS resolvidas para o select.
     */
    public string $classes;

    /**
     * Indica se o select está desabilitado para interação.
     */
    public bool $isDisabled = false;

    /**
     * Quantidade total de opções disponíveis.
     */
    public int $optionsCount = 0;

    /**
     * Indica se há opções para exibição.
     */
    public bool $hasOptions = false;

    /**
     * Indica se o componente possui endpoint de API configurado.
     */
    public bool $hasUrl = false;

    /**
     * Texto formatado para exibição em modo somente leitura (readonly).
     */
    public string $displayValue = '-';

    /**
     * Valor a ser submetido via campo oculto quando readonly.
     */
    public mixed $hiddenValue = '';

    /**
     * Classes CSS aplicadas ao campo de texto em modo readonly.
     */
    public string $readonlyClass = 'form-control';

    /**
     * Construtor do componente Select.
     *
     * @param string|null $nameField Nome do campo (name attribute).
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado.
     * @param string|null $label Rótulo exibido para o select.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $inputClass Classes CSS base do elemento de entrada.
     * @param string $selectClass Classes CSS base do select (padrão 'form-select').
     * @param string|null $wrapperClass Classes CSS de container/wrapper opcional (ex: 'col-md-6 mb-3').
     * @param string|null $size Tamanho do select ('sm', 'lg' ou número de linhas HTML).
     * @param mixed $selected Valor pré-selecionado (para edição ou padrão).
     * @param string|null $placeholder Texto do primeiro option vazio ('Selecione uma opção').
     * @param string|null $apiUrl Endpoint HTTP para carregamento assíncrono de dados.
     * @param string|null $urlParam Parâmetro na URL da API (ex: 'uf').
     * @param mixed $options Coleção ou array associativo [valor => texto].
     * @param string $emptyMessage Mensagem exibida quando não há dados.
     * @param string|null $dependsOn ID do select pai do qual depende.
     * @param string|null $targetUpdate ID do select dependente que será atualizado na alteração.
     * @param bool $loadOnInit Se deve disparar requisição da API ao carregar o DOM.
     * @param bool $useOldValue Se deve recuperar valor da sessão via old().
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
        public string $selectClass = 'form-select',
        public ?string $wrapperClass = null,
        public ?string $size = null,
        public mixed $selected = null,
        public ?string $placeholder = 'Selecione uma opção',
        public ?string $apiUrl = null,
        public ?string $urlParam = null,
        public mixed $options = [],
        public string $emptyMessage = 'Nenhum item disponível',
        public ?string $dependsOn = null,
        public ?string $targetUpdate = null,
        public bool $loadOnInit = true,
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

        $this->resolvedName = $this->nameField;
        $this->cleanName = str_replace('[]', '', (string) ($this->resolvedName ?? ''));

        // Normalização de opções
        if ($this->options instanceof \Illuminate\Support\Collection) {
            $this->options = $this->options->toArray();
        } elseif (!is_array($this->options)) {
            $this->options = (array) $this->options;
        }

        $baseSelected = $this->value ?? $this->selected;
        if ($this->useOldValue && $this->cleanName !== '') {
            $this->selectedValue = old($this->cleanName, $baseSelected);
        } else {
            $this->selectedValue = $baseSelected;
        }

        $this->value = $this->selectedValue;
        $this->selected = $this->selectedValue;

        $this->isHtmlSize = $this->size !== null && !in_array($this->size, ['sm', 'lg'], true);

        $baseClasses = $this->selectClass;
        if ($this->size !== null && in_array($this->size, ['sm', 'lg'], true)) {
            $baseClasses .= " form-select-{$this->size}";
        }

        $this->classes = trim($baseClasses . ($this->hasError ? ' is-invalid' : ''));

        $this->isDisabled = $this->disabled || $this->readonly;
        if ($this->isDisabled) {
            $this->isRequired = false;
        }
        $this->required = $this->isRequired;

        $this->optionsCount = count($this->options);
        $this->hasOptions = $this->optionsCount > 0;
        $this->hasUrl = $this->apiUrl !== null;

        if ($this->optionsCount === 1) {
            $firstKey = array_key_first($this->options);
            $this->value = $this->value ?? $firstKey;
        }

        // Resolução para modo somente leitura (readonly)
        $selectedText = '';
        if (StringSupport::hasValue($this->selectedValue)) {
            $rawSelected = ($this->selectedValue instanceof BackedEnum) ? $this->selectedValue->value : $this->selectedValue;
            if ($this->hasOptions) {
                foreach ($this->options as $optVal => $optLabel) {
                    $optKey = ($optVal instanceof BackedEnum) ? $optVal->value : $optVal;
                    if ((string) $optKey === (string) $rawSelected) {
                        $selectedText = (string) $optLabel;
                        break;
                    }
                }
            } elseif ($this->selectedValue instanceof BackedEnum && method_exists($this->selectedValue, 'label')) {
                /** @var object $enumObj */
                $enumObj = $this->selectedValue;
                $selectedText = (string) $enumObj->label();
            }
            if ($selectedText === '' && StringSupport::hasValue($rawSelected)) {
                $selectedText = (string) $rawSelected;
            }
        }

        $this->displayValue = $selectedText !== '' ? $selectedText : '-';
        $this->readonlyClass = 'form-control ' . ($this->hasError ? 'is-invalid' : '');
        $this->hiddenValue = ($this->selectedValue instanceof BackedEnum) ? $this->selectedValue->value : ($this->selectedValue ?? '');
    }

    /**
     * Retorna o prefixo padrão para o ID HTML do componente.
     */
    protected function getIdPrefix(): string
    {
        return ComponentIdPrefixEnum::SELECT->value;
    }

    /**
     * Retorna a view para renderização do componente.
     */
    public function render(): View
    {
        return view('components.field.select');
    }
}
