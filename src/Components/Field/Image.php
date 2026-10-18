<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Exibição e Preview de Imagem (Field Image).
 *
 * Renderiza quadro estilizado para imagens, moldura proporcional, placeholder e integração com x-field.file.
 */
class Image extends BaseFieldComponent
{
    /**
     * Cria uma nova instância do componente Image.
     *
     * @param string|null $id Identificador HTML que conecta com o input de arquivo (targetPreviewId).
     * @param string|null $nameField Nome do campo no formulário.
     * @param string|null $name Alias para nameField.
     * @param string|null $label Rótulo do campo.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $inputClass Classes CSS do elemento.
     * @param bool $useOldValue Se deve recuperar valor da sessão (old).
     * @param mixed $value Valor inicial do campo.
     * @param bool|null $required Flag de obrigatoriedade explícita.
     * @param mixed $model Model Eloquent para inferência de regras.
     * @param mixed $form FormRequest para inferência de regras.
     * @param bool $readonly Se o campo é somente leitura.
     * @param bool $disabled Se o campo está desabilitado.
     * @param bool $onlyView Se o campo está em modo somente visualização.
     * @param string|null $src URL da imagem atual para exibição.
     * @param string|null $alt Texto alternativo da tag img.
     * @param string|null $title Título no cabeçalho do box de visualização.
     * @param string $placeholderText Texto amigável exibido quando não há imagem.
     * @param string $maxWidth Largura máxima da imagem renderizada.
     * @param string $maxHeight Altura máxima da imagem renderizada.
     * @param string $minHeight Altura mínima do quadro/box.
     * @param bool $preview Indica se o modo preview reativo está ativo.
     */
    public function __construct(
        ?string $id = null,
        ?string $nameField = null,
        ?string $name = null,
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
        public ?string $src = null,
        public ?string $alt = null,
        public ?string $title = 'Visualização da Imagem',
        public string $placeholderText = 'Nenhuma imagem carregada',
        public string $maxWidth = '260px',
        public string $maxHeight = '120px',
        public string $minHeight = '160px',
        public bool $preview = true,
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

        $target = $id ?? ($nameField ?? ($name ?? \App\Support\StringSupport::nameRandom()));
        $this->targetId = (string) $target;
        $this->id_image = 'preview_img_' . $this->targetId;
        $this->id_container = 'preview_container_' . $this->targetId;
        $this->id_placeholder = 'preview_placeholder_' . $this->targetId;
    }

    /**
     * ID de destino que conecta com o componente de arquivo.
     */
    public string $targetId;

    /**
     * ID do elemento HTML da imagem (img).
     */
    public string $id_image;

    /**
     * ID do contêiner da imagem.
     */
    public string $id_container;

    /**
     * ID do placeholder da imagem.
     */
    public string $id_placeholder;

    /**
     * Prefixo para geração de ID HTML automático.
     */
    protected function getIdPrefix(): string
    {
        return 'preview_img';
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.image');
    }
}
