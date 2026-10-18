<?php

declare(strict_types=1);

namespace Select\Components\Enums;

enum ConvocacaoBlocoStatusEnum: string
{
    case PUBLICADO = 'publicado';
    case CONCLUIDO = 'concluido';
    case RASCUNHO = 'rascunho';
    case CANCELADO = 'cancelado';

    public function label(): string
    {
        return match ($this) {
            self::PUBLICADO => 'Publicado',
            self::CONCLUIDO => 'Concluído',
            self::RASCUNHO => 'Rascunho',
            self::CANCELADO => 'Cancelado',
        };
    }
}

