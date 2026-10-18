<?php

declare(strict_types=1);

namespace Select\Components\Enums;

enum ButtonTypeEnum: string
{
    case BUTTON = 'button';
    case SUBMIT = 'submit';
    case RESET = 'reset';
    case LINK = 'link';
}

