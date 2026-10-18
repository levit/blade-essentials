<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Input Group Reutilizável (Bootstrap 5 / SB Admin Pro).
 *
 * Permite composição de inputs com botões, ícones, textos e seletores via prepend, append e slot padrão.
 */
class InputGroup extends BaseFieldComponent
{
    /**
     * Cria uma nova instância do componente InputGroup.
     *
     * @param string|null $nameField Nome do campo associado para inferência de erros e regras.
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado do elemento.
     * @param string|null $label Rótulo exibido para o grupo.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $inputClass Classes CSS do elemento de entrada.
     * @param bool $useOldValue Se deve recuperar valor da sessão (old).
     * @param mixed $value Valor inicial associado.
     * @param bool|null $required Flag de obrigatoriedade explícita.
     * @param mixed $model Model Eloquent para inferência de regras.
     * @param mixed $form FormRequest para inferência de regras.
     * @param bool $readonly Se o campo é somente leitura.
     * @param bool $disabled Se o campo está desabilitado.
     * @param bool $onlyView Se o campo está em modo somente visualização.
     * @param string|null $for Identificador HTML do campo referenciado pelo label.
     * @param string $class Classes CSS adicionais para o contêiner input-group.
     * @param string|null $size Tamanho do grupo ('sm', 'lg' ou null).
     * @param bool $joined Se deve utilizar o estilo unificado .input-group-joined.
     * @param bool $solid Se deve utilizar o estilo sólido .input-group-solid.
     * @param mixed $prepend Conteúdo ou slot renderizado antes do input.
     * @param mixed $append Conteúdo ou slot renderizado depois do input.
     */
    public function __construct(
        ?string $nameField = null,
        ?string $name = null,
        ?string $id = null,
        ?string $label = null,
        string $labelClass = 'small mb-1',
        string $inputClass = '',
        bool $useOldValue = true,
        mixed $value = null,
        ?bool $required = null,
        mixed $model = null,
        mixed $form = null,
        bool $readonly = false,
        bool $disabled = false,
        bool $onlyView = false,
        public ?string $for = null,
        public string $class = '',
        public ?string $size = null,
        public bool $joined = false,
        public bool $solid = false,
        public mixed $prepend = null,
        public mixed $append = null,
    ) {
        $resolvedFor = $id ?? $for;
        $this->for = $resolvedFor;

        parent::__construct(
            nameField: $nameField,
            name: $name,
            id: $resolvedFor,
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

        $classes = ['input-group'];

        if ($this->joined) {
            $classes[] = 'input-group-joined';
        }

        if ($this->solid) {
            $classes[] = 'input-group-solid';
        }

        if ($this->size) {
            $classes[] = "input-group-{$this->size}";
        }

        if ($this->hasError) {
            $classes[] = 'has-validation';
            $classes[] = 'is-invalid';
        }

        if ($this->class !== '') {
            $classes[] = $this->class;
        }

        $this->groupClasses = trim(implode(' ', $classes));
    }

    /**
     * Classes CSS completas resolvidas do contêiner input-group.
     */
    public string $groupClasses = 'input-group';

    /**
     * Prefixo para geração de ID HTML automático.
     */
    protected function getIdPrefix(): string
    {
        return 'input_group';
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.input-group');
    }
}
