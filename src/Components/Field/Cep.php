<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Campo de Formulário com CEP (Código de Endereçamento Postal).
 *
 * Renderiza label, input com máscara postal brasileira (00000-000) e suporte a preenchimento automático.
 */
class Cep extends BaseFieldComponent
{
    /**
     * Cria uma nova instância do componente Cep.
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
     * @param int|string|null $maxlength Limite de caracteres do input (padrão 9).
     * @param string $placeholder Placeholder padrão do input.
     * @param bool $autofill Flag para ativar o autopreenchimento inteligente dos dados de endereço.
     * @param string|null $searchUrl URL customizada para busca do CEP via API.
     * @param string|null $containerSelector Seletor CSS customizado do container ou formulário alvo.
     */
    public function __construct(
        ?string $nameField = 'cep',
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
        public int|string|null $maxlength = 9,
        public string $placeholder = '00000-000',
        public bool $autofill = true,
        public ?string $searchUrl = null,
        public ?string $containerSelector = null,
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

        $this->maxlength = $this->maxlength ?? 9;
    }

    /**
     * Retorna a URL resolvida para busca do CEP.
     */
    public function getResolvedSearchUrl(): string
    {
        if (! empty($this->searchUrl)) {
            return $this->searchUrl;
        }

        if (\Illuminate\Support\Facades\Route::has('ceps.buscar')) {
            return route('ceps.buscar', ['cep' => '__CEP__']);
        }

        return url('/api/cep/__CEP__');
    }

    /**
     * Prefixo para geração de ID HTML automático.
     */
    protected function getIdPrefix(): string
    {
        return 'cep';
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.cep', [
            'resolvedSearchUrl' => $this->getResolvedSearchUrl(),
        ]);
    }
}
