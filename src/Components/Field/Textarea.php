<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Illuminate\Contracts\View\View;

/**
 * Componente de Campo de Área de Texto (Field Textarea).
 *
 * Renderiza um elemento HTML <textarea> com rótulo descritivo, controle de linhas (rows),
 * classes estilizadas, tratamento de validação e suporte a modo de visualização.
 */
class Textarea extends BaseFieldComponent
{
    /**
     * Classes CSS resolvidas para o elemento textarea.
     */
    public string $classes;

    /**
     * Construtor do componente Textarea.
     *
     * @param string|null $nameField Nome do campo (atributo name).
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado.
     * @param string|null $label Rótulo exibido para o campo.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $textareaClass Classes CSS base do textarea.
     * @param string|null $inputClass Alias para textareaClass.
     * @param int|string $rows Quantidade de linhas verticais visíveis (altura padrão 3).
     * @param bool $useOldValue Se deve recuperar valor da sessão via old().
     * @param mixed $value Valor inicial ou pré-preenchido do campo.
     * @param bool|null $required Flag explícita de obrigatoriedade.
     * @param mixed $model Model Eloquent para inferência de regras.
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
        public string $textareaClass = 'form-control',
        ?string $inputClass = null,
        public int|string $rows = 3,
        bool $useOldValue = true,
        mixed $value = null,
        ?bool $required = null,
        mixed $model = null,
        mixed $form = null,
        bool $readonly = false,
        bool $disabled = false,
        bool $onlyView = false,
    ) {
        $resolvedInputClass = $inputClass ?? $this->textareaClass;

        parent::__construct(
            nameField: $nameField,
            name: $name,
            id: $id,
            label: $label,
            labelClass: $labelClass,
            inputClass: $resolvedInputClass,
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
        $this->required = $this->isRequired;
        $this->value = $this->resolvedValue;
        $this->textareaClass = $this->inputClass;

        $baseClass = str_contains($this->textareaClass, 'form-control')
            ? $this->textareaClass
            : 'form-control ' . $this->textareaClass;

        $this->classes = trim($baseClass . ($this->hasError ? ' is-invalid' : ''));
    }

    /**
     * Retorna o prefixo padrão para o ID HTML do componente.
     */
    protected function getIdPrefix(): string
    {
        return ComponentIdPrefixEnum::TEXTAREA->value;
    }

    /**
     * Retorna a view para renderização do componente.
     */
    public function render(): View
    {
        return view('components.field.textarea');
    }
}
