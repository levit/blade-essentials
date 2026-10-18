<?php

declare(strict_types=1);

namespace Select\Components\Enums;

use Select\Components\Support\StringSupport;

/**
 * Enum que define os prefixos padronizados de identificadores HTML (id) dos componentes da interface.
 */
enum ComponentIdPrefixEnum: string
{
    // Campos de Formulário (Fields)
    case INPUT = 'input';
    case SELECT = 'select';
    case SELECT_CHECKBOX = 'selectChk';
    case RADIO = 'radio';
    case CHECKBOX = 'check';
    case TEXTAREA = 'textarea';
    case HIDDEN = 'hidden';
    case FILE = 'file';
    case DATEPICKER = 'datepicker';
    case DATETIME = 'datetime';
    case CEP = 'cep';
    case MONEY = 'money';
    case NUMBER = 'number';
    case PHONE = 'phone';
    case SWITCH = 'switch';
    case TAGS = 'tags';
    case MARKDOWN = 'markdown';
    case INPUT_GROUP = 'inputGroup';
    case PREVIEW_IMAGE = 'previewImg';

    // Ações e Botões
    case BUTTON = 'btn';
    case LINK = 'link';

    // Layout e Estrutura
    case COL = 'col';
    case ROW = 'row';
    case BOX = 'box';
    case FORM = 'form';
    case MODAL = 'modal';
    case MODAL_CONTENT = 'content';
    case WINDOW_HEADER = 'windowHeader';
    case MAPS = 'maps';
    case ICON = 'ico';
    case DATATABLE = 'datatable';

    /**
     * Gera um ID HTML padronizado combinando o prefixo, um token aleatório e sufixo.
     *
     * Se um ID explícito for informado e possuir valor, ele tem precedência total.
     *
     * @param  string|null  $explicitId  ID explicitamente definido pelo desenvolvedor.
     * @param  int  $randomLength  Tamanho do token randômico (padrão: 8).
     * @return string ID HTML resolvido.
     */
    public function generateId(?string $explicitId = null, int $randomLength = 8): string
    {
        if (StringSupport::hasValue($explicitId)) {
            return $explicitId;
        }

        return "{$this->value}_".StringSupport::nameRandom($randomLength).'_id';
    }
}
