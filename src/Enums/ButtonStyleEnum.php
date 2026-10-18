<?php

declare(strict_types=1);

namespace Select\Components\Enums;

enum ButtonStyleEnum: string
{
    case PADRAO = 'primary';
    case SECUNDARIO = 'secondary';
    case SUCESSO = 'success';
    case PERIGO = 'danger';
    case AVISO = 'warning';
    case INFO = 'info';
    case CLARO = 'light';
    case ESCURO = 'dark';
    case LINK = 'link';

    public function getClassButton(): string
    {
        return $this->value;
    }
}
