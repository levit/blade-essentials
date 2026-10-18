<?php

declare(strict_types=1);

namespace Select\Components\Enums;

enum FormModeEnum: string
{
    case CREATE = 'create';
    case EDIT = 'edit';
    case SHOW = 'show';
    case DELETE = 'delete';

    public function formMethod(): string
    {
        return match ($this) {
            self::CREATE => 'POST',
            self::EDIT => 'PUT',
            self::SHOW => 'GET',
            self::DELETE => 'DELETE',
        };
    }
}

