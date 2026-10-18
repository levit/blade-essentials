<?php

declare(strict_types=1);

namespace Select\Components\Enums;

enum BoletoStatusEnum: string
{
    case PAGO = 'pago';
    case PENDENTE = 'pendente';
    case CANCELADO = 'cancelado';
    case VENCIDO = 'vencido';

    public function label(): string
    {
        return match ($this) {
            self::PAGO => 'Pago / Baixado',
            self::PENDENTE => 'Pendente',
            self::CANCELADO => 'Cancelado',
            self::VENCIDO => 'Vencido',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PAGO => 'bg-success',
            self::PENDENTE => 'bg-warning',
            self::CANCELADO => 'bg-danger',
            self::VENCIDO => 'bg-secondary',
        };
    }
}

