<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Select\Components\Support\StringSupport;
use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Campo de Formulário com Data e Hora (DateTime).
 *
 * Renderiza input com máscara e formatação de data/hora, com suporte a Carbon/strings e modo somente leitura.
 */
class Datetime extends BaseFieldComponent
{
    /**
     * Cria uma nova instância do componente Datetime.
     *
     * @param string|null $nameField Nome do campo no formulário.
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado do elemento.
     * @param string|null $label Rótulo exibido para o campo.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $inputClass Classes CSS do input.
     * @param bool $useOldValue Se deve recuperar valor da sessão (old).
     * @param mixed $value Valor inicial do campo (Carbon, DateTimeInterface ou string).
     * @param bool|null $required Flag de obrigatoriedade explícita.
     * @param mixed $model Model Eloquent para inferência de regras.
     * @param mixed $form FormRequest para inferência de regras.
     * @param bool $readonly Se o campo é somente leitura.
     * @param bool $disabled Se o campo está desabilitado.
     * @param bool $onlyView Se o campo está em modo somente visualização.
     * @param string $format Formato de exibição da data e hora (padrão 'd/m/Y H:i').
     * @param string|null $minDate Data mínima permitida.
     * @param string|null $maxDate Data máxima permitida.
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
        public string $format = 'd/m/Y H:i',
        public ?string $minDate = null,
        public ?string $maxDate = null,
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

        $rawVal = $this->resolvedValue;
        if (StringSupport::hasValue($rawVal)) {
            try {
                if ($rawVal instanceof \DateTimeInterface) {
                    $this->displayValue = $rawVal->format($this->format);
                } else {
                    $dt = \Carbon\Carbon::parse((string) $rawVal);
                    $this->displayValue = $dt->format($this->format);
                }
            } catch (\Throwable) {
                $this->displayValue = (string) $rawVal;
            }
        }
    }

    /**
     * Valor formatado para exibição no input.
     */
    public string $displayValue = '';

    /**
     * Prefixo para geração de ID HTML automático.
     */
    protected function getIdPrefix(): string
    {
        return 'datetime';
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.datetime');
    }
}
