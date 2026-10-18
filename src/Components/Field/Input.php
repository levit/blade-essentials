<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Select\Components\Support\StringSupport;
use Illuminate\Contracts\View\View;

/**
 * Componente de Campo de Entrada de Texto/Genérico (Field Input).
 *
 * Renderiza um input HTML padrão com label associado, suporte a tipagens
 * (text, password, email, etc.), resolução de classes de validação e compatibilidade
 * com dados de sessão e persistência de formulário.
 */
class Input extends BaseFieldComponent
{
    /**
     * Classes CSS resolvidas para o elemento input.
     */
    public string $classes;

    /**
     * Construtor do componente Input.
     *
     * @param string|null $nameField Nome do campo (atributo name).
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado (se nulo, gerado automaticamente).
     * @param string|null $label Rótulo exibido para o campo.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $inputClass Classes CSS base do input.
     * @param string $inputType Tipo HTML do input (text, email, password, etc.).
     * @param string|null $type Alias de conveniência para inputType.
     * @param bool $useOldValue Se deve recuperar valor da sessão via old().
     * @param mixed $value Valor inicial ou pré-preenchido do campo.
     * @param bool|null $required Flag explícita de obrigatoriedade.
     * @param mixed $model Model Eloquent para inferência de validação.
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
        string $inputClass = 'form-control',
        public string $inputType = 'text',
        ?string $type = null,
        bool $useOldValue = true,
        mixed $value = null,
        ?bool $required = null,
        mixed $model = null,
        mixed $form = null,
        bool $readonly = false,
        bool $disabled = false,
        bool $onlyView = false,
        bool $showError = true,
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
            showError: $showError,
        );

        if (StringSupport::hasValue($type)) {
            $this->inputType = $type;
        }

        $this->name = $this->nameField;
        $this->required = $this->isRequired;
        $this->value = $this->resolvedValue;

        $baseClass = str_contains($this->inputClass, 'form-control')
            ? $this->inputClass
            : 'form-control ' . $this->inputClass;

        $this->classes = trim($baseClass . ($this->hasError ? ' is-invalid' : ''));
    }

    /**
     * Retorna o prefixo padrão para o ID HTML do componente.
     */
    protected function getIdPrefix(): string
    {
        return 'input';
    }

    /**
     * Retorna a view para renderização do componente.
     */
    public function render(): View
    {
        return view('components.field.input');
    }
}
