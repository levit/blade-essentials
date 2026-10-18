<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Campo de Formulário com Telefone (Fixo / Celular / Ambos).
 *
 * Renderiza input com máscara telefônica brasileira dinâmica ou fixa e validação client-side.
 */
class Phone extends BaseFieldComponent
{
    /**
     * Tipo do telefone ('mobile', 'phone' ou 'both').
     */
    public string $type;

    /**
     * Placeholder formatado conforme o tipo de telefone.
     */
    public string $placeholder;

    /**
     * Limite de caracteres do telefone.
     */
    public int $maxlength;

    /**
     * Cria uma nova instância do componente Phone.
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
     * @param string $type Tipo de telefone ('mobile', 'phone' ou 'both').
     * @param string|null $tipo Alias para $type.
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
        string $type = 'mobile',
        ?string $tipo = null,
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

        $this->type = $tipo ?? $type;

        $this->placeholder = match($this->type) {
            'phone' => '(00) 0000-0000',
            default => '(00) 00000-0000',
        };

        $this->maxlength = match($this->type) {
            'phone' => 14,
            default => 15,
        };
    }

    /**
     * Prefixo para geração de ID HTML automático.
     */
    protected function getIdPrefix(): string
    {
        return 'phone';
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.phone');
    }
}
