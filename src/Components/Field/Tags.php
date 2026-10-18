<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Campo de Formulário com Tags / Palavras-chave Dinâmicas.
 *
 * Permite inserção interativa com separação por vírgula/ponto e vírgula/Enter, badges visuais e sincronização em hidden.
 */
class Tags extends BaseFieldComponent
{
    /**
     * Cria uma nova instância do componente Tags.
     *
     * @param string|null $nameField Nome do campo no formulário.
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado do elemento.
     * @param string|null $label Rótulo exibido para o campo.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $inputClass Classes CSS do elemento de entrada.
     * @param bool $useOldValue Se deve recuperar valor da sessão (old).
     * @param mixed $value Valor inicial do campo (string delimitada ou array).
     * @param bool|null $required Flag de obrigatoriedade explícita.
     * @param mixed $model Model Eloquent para inferência de regras.
     * @param mixed $form FormRequest para inferência de regras.
     * @param bool $readonly Se o campo é somente leitura.
     * @param bool $disabled Se o campo está desabilitado.
     * @param bool $onlyView Se o campo está em modo somente visualização.
     * @param string|null $placeholder Texto do placeholder no input de digitação.
     * @param int|string|null $maxTags Quantidade máxima permitida de tags.
     * @param string|null $helpText Texto informativo de ajuda abaixo do campo.
     */
    public function __construct(
        ?string $nameField = null,
        ?string $name = null,
        ?string $id = null,
        ?string $label = null,
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
        public ?string $placeholder = 'Digite uma palavra-chave e tecle vírgula (,) ou ponto e vírgula (;)...',
        public int|string|null $maxTags = null,
        public ?string $helpText = 'Digite e pressione vírgula (,) ou ponto e vírgula (;) para separar as palavras-chave.',
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

        if (is_array($rawVal)) {
            $list = $rawVal;
        } elseif (is_string($rawVal) && trim($rawVal) !== '') {
            $list = preg_split('/[,;]+/', $rawVal, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        } else {
            $list = [];
        }

        $this->tagsList = array_values(array_unique(array_map('trim', $list)));
        $this->initialValueString = implode(', ', $this->tagsList);
        $this->isDisabled = $this->disabled || $this->readonly;
    }

    /**
     * Lista de tags / palavras-chave ativas.
     *
     * @var array<int, string>
     */
    public array $tagsList = [];

    /**
     * Valor serializado inicial para sincronização no input hidden.
     */
    public string $initialValueString = '';

    /**
     * Indica se o campo está desabilitado ou em modo somente leitura.
     */
    public bool $isDisabled = false;

    /**
     * Prefixo para geração de ID HTML automático.
     */
    protected function getIdPrefix(): string
    {
        return 'tags';
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.tags');
    }
}
