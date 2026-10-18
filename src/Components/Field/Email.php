<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Campo de Formulário com E-mail (type="email").
 *
 * Renderiza label, input estilizado do tipo email com validador básico integrado,
 * sanitização e mensagens de validação do Laravel e client-side.
 */
class Email extends BaseFieldComponent
{
    /**
     * Placeholder para exibição no campo.
     */
    public string $placeholder;

    /**
     * Mensagem customizada de erro de validação.
     */
    public ?string $errorMessageCustom;

    /**
     * Se deve exibir caixa interativa com sugestão de correção em caso de erro comum.
     */
    public bool $enableSuggestion;

    /**
     * Se deve aplicar validação rigorosa de extensões de domínio (TLD) conhecidas.
     */
    public bool $strictTld;

    /**
     * Cria uma nova instância do componente Email.
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
     * @param string|null $placeholder Texto de exemplo exibido no campo.
     * @param string|null $errorMessageCustom Mensagem customizada de validação de formato.
     * @param bool $enableSuggestion Ativa sugestão interativa em 1 clique ("Você quis dizer...?").
     * @param bool $strictTld Ativa detecção estrita de TLDs com erro (ex: .coml, .bre).
     */
    public function __construct(
        ?string $nameField = null,
        ?string $name = null,
        ?string $id = null,
        ?string $label = 'E-mail',
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
        ?string $placeholder = 'exemplo@dominio.com',
        ?string $errorMessageCustom = null,
        bool $enableSuggestion = true,
        bool $strictTld = true,
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

        $this->placeholder = $placeholder ?? 'exemplo@dominio.com';
        $this->errorMessageCustom = $errorMessageCustom;
        $this->enableSuggestion = $enableSuggestion;
        $this->strictTld = $strictTld;
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.email');
    }
}
