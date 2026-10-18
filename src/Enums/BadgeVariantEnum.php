<?php

declare(strict_types=1);

namespace Select\Components\Enums;

enum BadgeVariantEnum: string
{
    case SOFT = 'soft';
    case SOLID = 'solid';
    case OUTLINE = 'outline';

    public function label(): string
    {
        return match ($this) {
            self::SOFT => 'Suave (Soft)',
            self::SOLID => 'Sólido',
            self::OUTLINE => 'Contorno (Outline)',
        };
    }
}
