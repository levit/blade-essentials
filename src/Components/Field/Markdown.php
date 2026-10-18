<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Campo de Formulário com Editor Markdown / LaTeX (EasyMDE / KaTeX).
 *
 * Oferece barra de ferramentas rica, renderização em tempo real de fórmulas matemáticas, upload e badges informativas.
 */
class Markdown extends BaseFieldComponent
{
    /**
     * Cria uma nova instância do componente Markdown.
     *
     * @param string|null $nameField Nome do campo no formulário.
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado do elemento.
     * @param string|null $label Rótulo exibido para o campo.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $inputClass Classes CSS do elemento de entrada.
     * @param string|null $placeholder Texto do placeholder no editor.
     * @param int|string $rows Quantidade de linhas visuais padrão do editor.
     * @param bool $useOldValue Se deve recuperar valor da sessão (old).
     * @param mixed $value Valor inicial do texto em markdown.
     * @param bool|null $required Flag de obrigatoriedade explícita.
     * @param mixed $model Model Eloquent para inferência de regras.
     * @param mixed $form FormRequest para inferência de regras.
     * @param bool $readonly Se o campo é somente leitura.
     * @param bool $disabled Se o campo está desabilitado.
     * @param bool $onlyView Se o campo está em modo somente visualização.
     * @param string|null $helpText Texto informativo de ajuda.
     * @param bool $showMdBadge Se exibe o badge informativo de Markdown.
     * @param bool $showMathBadge Se exibe o badge informativo de LaTeX / fórmulas matemáticas.
     * @param string $height Altura do editor ou preview (padrão '300px').
     */
    public function __construct(
        ?string $nameField = null,
        ?string $name = null,
        ?string $id = null,
        ?string $label = '',
        string $labelClass = 'small mb-1 fw-bold',
        string $inputClass = '',
        public ?string $placeholder = 'Digite o texto em markdown ou insira fórmulas matemáticas...',
        public int|string $rows = 12,
        bool $useOldValue = true,
        mixed $value = null,
        ?bool $required = null,
        mixed $model = null,
        mixed $form = null,
        bool $readonly = false,
        bool $disabled = false,
        bool $onlyView = false,
        public ?string $helpText = '',
        public bool $showMdBadge = false,
        public bool $showMathBadge = false,
        public string $height = '300px',
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

        $this->isReadOnlyOrDisabled = $this->readonly || $this->disabled;
    }

    /**
     * Indica se o editor está em modo somente leitura ou desabilitado.
     */
    public bool $isReadOnlyOrDisabled = false;

    /**
     * Prefixo para geração de ID HTML automático.
     */
    protected function getIdPrefix(): string
    {
        return 'markdown';
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.markdown');
    }
}
