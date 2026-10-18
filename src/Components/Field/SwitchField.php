<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Campo de Formulário Switch Toggle (Bootstrap 5 / SB Admin Pro).
 *
 * Renderiza switch interativo acessível (role="switch"), com suporte a old(), modo onlyView e validação.
 * A classe é nomeada SwitchField para evitar conflito com a palavra reservada 'switch' da linguagem PHP.
 */
class SwitchField extends BaseFieldComponent
{
    /**
     * Cria uma nova instância do componente SwitchField.
     *
     * @param string|null $nameField Nome do campo no formulário.
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado do elemento.
     * @param string|null $label Rótulo exibido ao lado do switch.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $checkboxClass Classes CSS do elemento input switch.
     * @param string $wrapperClass Classes CSS do contêiner wrapper.
     * @param bool|string|int|null $checked Estado inicial marcado/desmarcado (padrão false).
     * @param bool $useOldValue Se deve recuperar valor da sessão (old).
     * @param mixed $value Valor enviado quando checado (padrão '1').
     * @param bool|null $required Flag de obrigatoriedade explícita.
     * @param mixed $model Model Eloquent para inferência de regras.
     * @param mixed $form FormRequest para inferência de regras.
     * @param bool $readonly Se o campo é somente leitura.
     * @param bool $disabled Se o campo está desabilitado.
     * @param bool $onlyView Se o campo está em modo somente visualização.
     * @param string|null $size Modificador de tamanho opcional.
     */
    /**
     * Indica se o switch deve estar marcado (checked).
     */
    public bool $isChecked = false;

    /**
     * Indica se o componente está desabilitado para interação.
     */
    public bool $isDisabled = false;

    /**
     * Classes CSS resolvidas para o container/wrapper do switch.
     */
    public string $finalWrapperClass;

    /**
     * Classes CSS resolvidas para o elemento input switch.
     */
    public string $inputClasses;

    public function __construct(
        ?string $nameField = null,
        ?string $name = null,
        ?string $id = null,
        ?string $label = '',
        string $labelClass = 'form-check-label',
        public string $checkboxClass = 'form-check-input',
        public string $wrapperClass = 'form-check form-switch',
        public bool|string|int|null $checked = false,
        bool $useOldValue = true,
        mixed $value = '1',
        ?bool $required = null,
        mixed $model = null,
        mixed $form = null,
        bool $readonly = false,
        bool $disabled = false,
        bool $onlyView = false,
        public ?string $size = null,
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

        // Resolução do estado checked (prioriza old() quando habilitado)
        if ($this->useOldValue && $this->dotName !== '' && old($this->dotName) !== null) {
            $oldVal = old($this->dotName);
            $this->isChecked = (string) $oldVal === (string) $this->value || (bool) $oldVal;
        } elseif ($this->useOldValue && $this->nameField !== '' && old((string) $this->nameField) !== null) {
            $oldVal = old((string) $this->nameField);
            $this->isChecked = (string) $oldVal === (string) $this->value || (bool) $oldVal;
        } elseif (!is_null($this->checked)) {
            $this->isChecked = filter_var($this->checked, FILTER_VALIDATE_BOOLEAN);
        } else {
            $this->isChecked = false;
        }

        $this->isDisabled = $this->disabled || $this->readonly;
        if ($this->isDisabled) {
            $this->isRequired = false;
        }

        $this->inputClasses = trim($this->checkboxClass . ($this->hasError ? ' is-invalid' : ''));

        $wrapper = $this->wrapperClass;
        if ($this->size === 'sm') {
            $wrapper .= ' form-switch-sm';
        } elseif ($this->size === 'lg') {
            $wrapper .= ' form-switch-lg';
        }
        $this->finalWrapperClass = trim($wrapper);
    }

    /**
     * Prefixo para geração de ID HTML automático.
     */
    protected function getIdPrefix(): string
    {
        return 'switch';
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.switch');
    }
}
