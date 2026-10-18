<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Illuminate\Contracts\View\View;

/**
 * Componente de Campo de Opções Exclusivas (Field Radio).
 *
 * Renderiza um grupo de botões de opção (<input type="radio">) com rótulo descritivo,
 * opções mapeadas [valor => texto], suporte a layout inline, resolução inteligente
 * de estado selecionado (incluindo old() da sessão) e modo somente leitura/desabilitado.
 */
class Radio extends BaseFieldComponent
{
    /**
     * Indica se os botões de opção do grupo estão desabilitados para interação.
     */
    public bool $isDisabled = false;

    /**
     * Classes CSS finais do container/wrapper de cada botão de opção.
     */
    public string $wrapperClasses;

    /**
     * Classes CSS base do elemento de input radio.
     */
    public string $baseRadioClasses;

    /**
     * Classes CSS resolvidas para o input.
     */
    public string $classes;

    /**
     * Construtor do componente Radio.
     *
     * @param string|null $nameField Nome do campo compartilhado pelo grupo de radios.
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID base para os elementos radio (será sufixado com cada valor).
     * @param string|null $label Rótulo exibido para o grupo de opções.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $inputClass Classes CSS base do elemento de entrada.
     * @param mixed $options Lista de opções associativas [valor => rótulo] ou coleção.
     * @param bool $inline Se os botões de opção devem ser exibidos na horizontal.
     * @param string $radioClass Classes CSS dos inputs radio.
     * @param string $wrapperClass Classes CSS do container de cada radio.
     * @param mixed $selected Valor pré-selecionado do grupo.
     * @param bool $useOldValue Se deve recuperar valor da sessão via old().
     * @param mixed $value Valor inicial alternativo para seleção.
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
        ?string $label = '',
        string $labelClass = 'form-check-label small mb-2 d-block',
        string $inputClass = 'form-check-input',
        public mixed $options = [],
        public bool $inline = false,
        public string $radioClass = 'form-check-input',
        public string $wrapperClass = 'form-check',
        public mixed $selected = null,
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

        $this->name = $this->nameField;
        $resolvedRawName = (string) ($this->nameField ?? '');

        // Precedência de seleção: old() -> selected -> value
        if ($this->useOldValue && $resolvedRawName !== '' && old($resolvedRawName) !== null) {
            $this->selected = old($resolvedRawName);
        } elseif ($this->selected === null && $this->value !== null) {
            $this->selected = $this->value;
        }

        $this->value = $this->selected;

        $this->isDisabled = $this->disabled || $this->readonly;
        if ($this->isDisabled) {
            $this->isRequired = false;
        }
        $this->required = $this->isRequired;

        $this->wrapperClasses = trim($this->wrapperClass . ($this->inline ? ' form-check-inline' : ''));
        $this->baseRadioClasses = trim($this->radioClass . ($this->hasError ? ' is-invalid' : ''));
        $this->classes = $this->baseRadioClasses;
    }

    /**
     * Retorna o prefixo padrão para o ID HTML do componente.
     */
    protected function getIdPrefix(): string
    {
        return ComponentIdPrefixEnum::RADIO->value;
    }

    /**
     * Retorna a view para renderização do componente.
     */
    public function render(): View
    {
        return view('components.field.radio');
    }
}
