<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Campo de Formulário de Valor Monetário (Moeda / BRL).
 *
 * Renderiza input com formatação de valores em reais, prefixo visual e separadores configuráveis.
 */
class Money extends BaseFieldComponent
{
    /**
     * Cria uma nova instância do componente Money.
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
     * @param string $prefix Prefixo da moeda exibido no input-group (padrão 'R$ ').
     * @param int $decimals Quantidade de casas decimais (padrão 2).
     * @param string $thousandsSeparator Separador de milhares (padrão '.').
     * @param string $decimalSeparator Separador de decimais (padrão ',').
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
        public string $prefix = 'R$',
        public int $decimals = 2,
        public string $thousandsSeparator = '.',
        public string $decimalSeparator = ',',
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
    }

    /**
     * Prefixo para geração de ID HTML automático.
     */
    protected function getIdPrefix(): string
    {
        return 'money';
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.money');
    }
}
