<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Campo de Formulário de Documento (CPF / CNPJ / Ambos).
 *
 * Renderiza input com máscara dinâmica e validação matemática de dígitos verificadores client-side.
 */
class CpfCnpj extends BaseFieldComponent
{
    /**
     * Tipo de documento ('cpf', 'cnpj' ou 'both').
     */
    public string $tipo;

    /**
     * Alias para compatibilidade com templates ($type).
     */
    public string $type;

    /**
     * Placeholder formatado conforme o tipo de documento.
     */
    public string $placeholder;

    /**
     * Limite de caracteres do documento.
     */
    public int $maxlength;

    /**
     * Cria uma nova instância do componente CpfCnpj.
     *
     * @param string|null $nameField Nome do campo no formulário.
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado do elemento.
     * @param string|null $label Rótulo exibido para o campo.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $inputClass Classes CSS do input.
     * @param bool $useOldValue Se deve recuperar valor da sessão (old).
     * @param mixed $value Valor inicial do campo.
     * @param bool|null $required Flag de obrigatoriedade explícita.
     * @param mixed $model Model Eloquent para inferência de regras.
     * @param mixed $form FormRequest para inferência de regras.
     * @param bool $readonly Se o campo é somente leitura.
     * @param bool $disabled Se o campo está desabilitado.
     * @param bool $onlyView Se o campo está em modo somente visualização.
     * @param string|null $tipo Tipo de documento: 'cpf', 'cnpj' ou 'both'.
     * @param string|null $type Alias para o tipo de documento.
     */
    public function __construct(
        ?string $nameField = null,
        ?string $name = null,
        ?string $id = null,
        ?string $label = '',
        string $labelClass = 'small mb-1',
        string $inputClass = 'form-control',
        bool $useOldValue = true,
        mixed $value = null,
        ?bool $required = null,
        mixed $model = null,
        mixed $form = null,
        bool $readonly = false,
        bool $disabled = false,
        bool $onlyView = false,
        ?string $tipo = null,
        ?string $type = null,
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

        $resolvedTipo = $tipo ?? $type ?? 'both';
        $this->tipo = $resolvedTipo;
        $this->type = $resolvedTipo;

        [$this->placeholder, $this->maxlength] = match($resolvedTipo) {
            'cpf'   => ['000.000.000-00', 14],
            'cnpj'  => ['00.000.000/0000-00', 18],
            default => ['CPF ou CNPJ', 18],
        };
    }

    /**
     * Prefixo para geração de ID HTML automático.
     */
    protected function getIdPrefix(): string
    {
        return 'input';
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.cpf-cnpj');
    }
}
