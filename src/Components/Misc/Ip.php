<?php

declare(strict_types=1);

namespace Select\Components\Components\Misc;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Universal de Endereço IP com Geolocalização.
 *
 * Renderiza endereços IP (IPv4 e IPv6) com formatação monoespaçada, detecção inteligente
 * de redes locais (127..., ::1, localhost) como Servidor, redes privadas (RFC 1918)
 * e link seguro para serviços de geolocalização para IPs públicos.
 */
class Ip extends Component
{
    public string $resolvedIp;

    public bool $isEmpty;

    public bool $isLocal;

    public bool $isPrivate;

    public string $geoUrl;

    public string $resolvedIcon;

    public ?string $resolvedTitle;

    public string $serverSuffix;

    /**
     * Cria uma nova instância do componente Ip.
     */
    public function __construct(
        public ?string $ip = null,
        public ?string $value = null,
        public string $provider = 'ipinfo',
        public string $as = 'code',
        public bool|string $showIcon = true,
        public ?string $icon = null,
        public string $target = '_blank',
        public string $rel = 'noopener noreferrer',
        public ?string $title = null,
        public string $badgeColor = 'secondary',
        public bool|string $showServerLabel = true,
        public string $serverLabel = 'Servidor',
    ) {
        $this->showIcon = filter_var($this->showIcon, FILTER_VALIDATE_BOOLEAN);
        $this->showServerLabel = filter_var($this->showServerLabel, FILTER_VALIDATE_BOOLEAN);

        $rawIp = $this->ip ?? $this->value;
        $ipStr = trim((string) ($rawIp ?? ''));
        $this->isEmpty = $ipStr === '' || $ipStr === '-';
        $this->resolvedIp = $this->isEmpty ? '-' : $ipStr;

        $this->isLocal = false;
        $this->isPrivate = false;

        if (! $this->isEmpty) {
            $this->isLocal = in_array($this->resolvedIp, ['127.0.0.1', '::1', 'localhost', '0.0.0.0', '::', '0:0:0:0:0:0:0:1'], true)
                || str_starts_with($this->resolvedIp, '127.')
                || str_starts_with($this->resolvedIp, '::1');

            $this->isPrivate = $this->isLocal || ! filter_var($this->resolvedIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
        }

        $this->geoUrl = match (strtolower($this->provider)) {
            'ip-api' => "https://ip-api.com/#{$this->resolvedIp}",
            'ipapi' => "https://ipapi.co/{$this->resolvedIp}/",
            default => "https://ipinfo.io/{$this->resolvedIp}",
        };

        if ($this->icon !== null) {
            $this->resolvedIcon = $this->icon;
        } elseif ($this->isLocal) {
            $this->resolvedIcon = 'bi-server';
        } elseif ($this->isPrivate) {
            $this->resolvedIcon = 'bi-router';
        } else {
            $this->resolvedIcon = 'bi-geo-alt-fill';
        }

        if ($this->title !== null) {
            $this->resolvedTitle = $this->title;
        } elseif ($this->isEmpty) {
            $this->resolvedTitle = null;
        } elseif ($this->isLocal) {
            $this->resolvedTitle = "Endereço Local / Servidor ({$this->resolvedIp})";
        } elseif ($this->isPrivate) {
            $this->resolvedTitle = "IP de Rede Privada / Local ({$this->resolvedIp}) - Consultar detalhes no ipinfo.io";
        } else {
            $this->resolvedTitle = "Consultar geolocalização do IP {$this->resolvedIp} (ipinfo.io)";
        }

        $this->serverSuffix = ($this->isLocal && $this->showServerLabel && ! empty($this->serverLabel)) ? "({$this->serverLabel})" : '';
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.misc.ip');
    }
}
