<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Campo de Upload de Arquivo (type="file").
 *
 * Encapsula seleção estilizada de arquivos, validação de tamanho e formatos, remoção e sincronização com preview.
 */
class File extends BaseFieldComponent
{
    /**
     * Cria uma nova instância do componente File.
     *
     * @param string|null $nameField Nome do campo no formulário.
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado do elemento.
     * @param string|null $label Rótulo exibido para o campo.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $fileClass Classes CSS do input file.
     * @param string|null $inputClass Alias para fileClass.
     * @param bool $useOldValue Se deve recuperar valor da sessão (old).
     * @param mixed $value Valor inicial associado ao arquivo.
     * @param string|null $accept Formatos e MIME-types aceitos (ex: 'image/*,application/pdf').
     * @param string $buttonText Texto do botão de seleção.
     * @param string $buttonIcon Ícone do botão de seleção.
     * @param int|string $maxSize Tamanho máximo em Megabytes.
     * @param bool $hasFile Se já existe arquivo previamente gravado.
     * @param bool $allowRemove Se exibe botão de exclusão de arquivo.
     * @param string $removeText Texto do botão de exclusão.
     * @param string $removeIcon Ícone do botão de exclusão.
     * @param string|null $targetPreviewId ID do elemento de preview associado.
     * @param bool $showFileName Se exibe o nome do arquivo selecionado.
     * @param bool $visibleButtons Se os botões de ação estão visíveis.
     * @param bool|null $required Flag de obrigatoriedade explícita.
     * @param mixed $model Model Eloquent para inferência de regras.
     * @param mixed $form FormRequest para inferência de regras.
     * @param bool $readonly Se o campo é somente leitura.
     * @param bool $disabled Se o campo está desabilitado.
     * @param bool $onlyView Se o campo está em modo somente visualização.
     */
    public function __construct(
        ?string $nameField = null,
        ?string $name = null,
        ?string $id = null,
        ?string $label = '',
        string $labelClass = 'small mb-1 fw-semibold text-dark',
        public string $fileClass = 'form-control',
        ?string $inputClass = null,
        bool $useOldValue = true,
        mixed $value = null,
        public ?string $accept = null,
        public string $buttonText = 'Selecionar arquivo',
        public string $buttonIcon = 'upload',
        public int|string $maxSize = '5',
        public bool $hasFile = false,
        public bool $allowRemove = true,
        public string $removeText = 'Remover arquivo',
        public string $removeIcon = 'trash',
        public ?string $targetPreviewId = null,
        public bool $showFileName = true,
        public bool $visibleButtons = true,
        ?bool $required = null,
        mixed $model = null,
        mixed $form = null,
        bool $readonly = false,
        bool $disabled = false,
        bool $onlyView = false,
    ) {
        parent::__construct(
            nameField: $nameField,
            name: $name,
            id: $id,
            label: $label,
            labelClass: $labelClass,
            inputClass: $inputClass ?? $fileClass,
            useOldValue: $useOldValue,
            value: $value,
            required: $required,
            model: $model,
            form: $form,
            readonly: $readonly,
            disabled: $disabled,
            onlyView: $onlyView,
        );

        $this->isDisabled = $this->disabled || $this->readonly;
        $this->accept ??= 'image/png,image/jpeg,image/webp,image/svg+xml';
        $this->targetPreviewId ??= (string) ($this->nameField ?? '');
        $this->visibleButtons = filter_var($this->visibleButtons, FILTER_VALIDATE_BOOLEAN);
        $this->allowRemove = filter_var($this->allowRemove, FILTER_VALIDATE_BOOLEAN);
        $this->hasFile = filter_var($this->hasFile, FILTER_VALIDATE_BOOLEAN);
        $this->showFileName = filter_var($this->showFileName, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Indica se o campo de arquivo está desabilitado ou somente leitura.
     */
    public bool $isDisabled = false;

    /**
     * Prefixo para geração de ID HTML automático.
     */
    protected function getIdPrefix(): string
    {
        return 'file';
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.file');
    }
}
