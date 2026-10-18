<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Illuminate\Contracts\View\View;

/**
 * Componente de Campo de Caixa de Seleção (Field Checkbox).
 *
 * Renderiza um checkbox padronizado com rótulo descritivo, suporte a formato switch,
 * exibição inline, resolução inteligente de estado checado (incluindo old() e arrays) e modo desabilitado.
 */
class Checkbox extends BaseFieldComponent
{
    /**
     * Indica se o checkbox deve ser renderizado marcado (checked).
     */
    public bool $isChecked = false;

    /**
     * Indica se o componente está desabilitado para interação.
     */
    public bool $isDisabled = false;

    /**
     * Classes CSS finais resolvidas para o container/wrapper do checkbox.
     */
    public string $finalWrapperClass;

    /**
     * Classes CSS resolvidas para o elemento input checkbox.
     */
    public string $classes;

    /**
     * Construtor do componente Checkbox.
     *
     * @param string|null $nameField Nome do campo (atributo name).
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado.
     * @param string|null $label Rótulo exibido para o checkbox.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $checkboxClass Classes CSS do elemento input.
     * @param string $wrapperClass Classes CSS do container externo.
     * @param mixed $checked Estado inicial de seleção (bool, string, int).
     * @param bool $inline Se o checkbox deve ser renderizado em linha (form-check-inline).
     * @param bool $switch Se deve aplicar estilo de switch do Bootstrap (form-switch).
     * @param string $type Tipo do input (padrão 'checkbox').
     * @param bool $useOldValue Se deve recuperar valor da sessão via old().
     * @param mixed $value Valor atribuído quando marcado (padrão '1').
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
        string $labelClass = 'form-check-label',
        public string $checkboxClass = 'form-check-input',
        public string $wrapperClass = 'form-check',
        public mixed $checked = false,
        public bool $inline = false,
        public bool $switch = false,
        public string $type = 'checkbox',
        bool $useOldValue = true,
        mixed $value = '1',
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
            inputClass: $checkboxClass,
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
        $this->checkboxClass = $this->inputClass;
        $resolvedName = (string) ($this->nameField ?? '');
        $cleanName = str_replace('[]', '', $resolvedName);

        // Resolução do estado checked (respeita old() prioritariamente quando habilitado)
        if ($this->useOldValue && old($cleanName) !== null) {
            $oldVal = old($cleanName);
            if (is_array($oldVal)) {
                $this->isChecked = in_array((string) $this->value, array_map('strval', $oldVal), true);
            } else {
                $this->isChecked = (string) $oldVal === (string) $this->value || (bool) $oldVal;
            }
        } elseif (!is_null($this->checked)) {
            $this->isChecked = filter_var($this->checked, FILTER_VALIDATE_BOOLEAN);
        } else {
            $this->isChecked = false;
        }

        $this->isDisabled = $this->disabled || $this->readonly;
        if ($this->isDisabled) {
            $this->isRequired = false;
        }
        $this->required = $this->isRequired;

        $this->classes = trim($this->checkboxClass . ($this->hasError ? ' is-invalid' : ''));

        $wrapper = $this->wrapperClass;
        if ($this->switch) {
            $wrapper .= ' form-switch';
        }
        if ($this->inline) {
            $wrapper .= ' form-check-inline';
        }
        $this->finalWrapperClass = trim($wrapper);
    }

    /**
     * Retorna o prefixo padrão para o ID HTML do componente.
     */
    protected function getIdPrefix(): string
    {
        return ComponentIdPrefixEnum::CHECKBOX->value;
    }

    /**
     * Retorna a view para renderização do componente.
     */
    public function render(): View
    {
        return view('components.field.checkbox');
    }
}
