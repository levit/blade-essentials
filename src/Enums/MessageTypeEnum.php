<?php

namespace Select\Components\Enums;

enum MessageTypeEnum: string
{
    case MESSAGE = 'primary';
    case DISABLED = 'secondary';
    case SUCCESS = 'success';
    case ERROR = 'danger';
    case WARNING = 'warning';
    case INFO = 'info';
    case LIGHT = 'light';
    case DARK = 'dark';

    public function label(): string
    {
        return match ($this) {
            self::MESSAGE => 'Primary',
            self::DISABLED => 'Secondary',
            self::SUCCESS => 'Success',
            self::ERROR => 'Error',
            self::WARNING => 'Warning',
            self::INFO => 'Info',
            self::LIGHT => 'Light',
            self::DARK => 'Dark',
            default => 'Unknown'
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::MESSAGE => 'award',
            self::DISABLED => 'bookmark',
            self::SUCCESS => 'check-circle',
            self::ERROR => 'x-circle',
            self::WARNING => 'alert-triangle',
            self::INFO => 'info',
            self::LIGHT => 'sun',
            self::DARK => 'cloud',
            default => 'star'
        };
    }

    public function getClass(): string
    {
        return match ($this) {
            self::MESSAGE => 'primary',
            self::DISABLED => 'secondary',
            self::SUCCESS => 'success',
            self::ERROR => 'danger',
            self::WARNING => 'warning',
            self::INFO => 'info',
            self::LIGHT => 'light',
            self::DARK => 'dark',
            default => 'primary'
        };
    }

    public static function values(): array
    {
        return array_map(fn (self $status) => $status->value, self::cases());
    }

    public static function labels(): array
    {
        return array_map(fn (self $status) => $status->label(), self::cases());
    }
}
