<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Campo de Endereço IP (IPv4 / IPv6).
 *
 * Renderiza exibição auditável de endereços de rede, badges de versão (IPv4/IPv6/Localhost) e consulta de geolocalização.
 */
class Ip extends BaseFieldComponent
{
    /**
     * Cria uma nova instância do componente Ip.
     *
     * @param string|null $nameField Nome do campo no formulário (padrão 'ip_address').
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado do elemento.
     * @param string|null $label Rótulo exibido para o campo.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $inputClass Classes CSS do input.
     * @param bool $useOldValue Se deve recuperar valor da sessão (old).
     * @param mixed $value Valor inicial do endereço IP.
     * @param string|null $ip Alias para o valor do IP.
     * @param string $provider Provedor de geolocalização externa (ex: 'ipinfo').
     * @param bool|null $required Flag de obrigatoriedade explícita.
     * @param mixed $model Model Eloquent para inferência de regras.
     * @param mixed $form FormRequest para inferência de regras.
     * @param bool $readonly Se o campo é somente leitura (padrão true para auditoria).
     * @param bool $disabled Se o campo está desabilitado.
     * @param bool $onlyView Se o campo está em modo somente visualização.
     */
    public function __construct(
        ?string $nameField = 'ip_address',
        ?string $name = null,
        ?string $id = null,
        ?string $label = 'Endereço IP de Origem',
        string $labelClass = 'small mb-1',
        string $inputClass = '',
        bool $useOldValue = true,
        mixed $value = null,
        public ?string $ip = null,
        public string $provider = 'ipinfo',
        ?bool $required = null,
        mixed $model = null,
        mixed $form = null,
        bool $readonly = true,
        bool $disabled = false,
        bool $onlyView = false,
    ) {
        parent::__construct(
            nameField: $nameField,
            name: $name,
            id: $id,
            label: $label,
            labelClass: $labelClass,
            inputClass: $inputClass,
            useOldValue: $useOldValue,
            value: $value ?? $ip,
            required: $required,
            model: $model,
            form: $form,
            readonly: $readonly,
            disabled: $disabled,
            onlyView: $onlyView,
        );

        $rawIp = $this->resolvedValue ?? $this->ip;
        $resolvedIp = trim((string) $rawIp);
        $isEmpty = empty($resolvedIp) || $resolvedIp === '-';

        if ($isEmpty) {
            $this->displayIp = 'N/A';
            $this->isLocal = false;
            $this->isPrivate = false;
            $this->icon = 'bi-hdd-network';
            $this->title = 'Endereço IP não informado';
            $this->geoUrl = null;
        } else {
            $this->displayIp = $resolvedIp;
            $this->isLocal = in_array($resolvedIp, ['127.0.0.1', '::1', 'localhost', '0.0.0.0', '::', '0:0:0:0:0:0:0:1'], true)
                || str_starts_with($resolvedIp, '127.')
                || str_starts_with($resolvedIp, '::1');

            $this->isPrivate = $this->isLocal || !filter_var($resolvedIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);

            if ($this->isLocal) {
                $this->icon = 'bi-server';
                $this->title = "Endereço Local / Servidor ({$resolvedIp})";
                $this->geoUrl = null;
            } elseif ($this->isPrivate) {
                $this->icon = 'bi-router';
                $this->title = "IP de Rede Privada / Local ({$resolvedIp})";
                $this->geoUrl = null;
            } else {
                $this->icon = 'bi-geo-alt-fill';
                $this->title = "IP Público ({$resolvedIp}) - Consultar geolocalização no ipinfo.io";
                $this->geoUrl = match (strtolower($this->provider)) {
                    'ip-api' => "https://ip-api.com/#{$resolvedIp}",
                    'ipapi'  => "https://ipapi.co/{$resolvedIp}/",
                    default  => "https://ipinfo.io/{$resolvedIp}",
                };
            }
        }
    }

    /**
     * IP formatado para exibição visual.
     */
    public string $displayIp = '';

    /**
     * Indica se o IP é de origem local / servidor (localhost / loopback).
     */
    public bool $isLocal = false;

    /**
     * Indica se o IP pertence à rede privada.
     */
    public bool $isPrivate = false;

    /**
     * Ícone contextual do tipo de IP.
     */
    public string $icon = 'bi-hdd-network';

    /**
     * Título descritivo para tooltip do IP.
     */
    public ?string $title = null;

    /**
     * URL de geolocalização externa do IP (se público).
     */
    public ?string $geoUrl = null;

    /**
     * Prefixo para geração de ID HTML automático.
     */
    protected function getIdPrefix(): string
    {
        return 'input';
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.ip');
    }
}
