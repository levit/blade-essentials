<?php

declare(strict_types=1);

namespace Select\Components\Components\Misc;

use Select\Components\Support\UserAgentSupport;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Universal para Exibição de User-Agent.
 *
 * Efetua parse e renderização enriquecida do cabeçalho User-Agent, exibindo ícones
 * e nomes identificados de Sistema Operacional e Navegador Web.
 */
class UserAgent extends Component
{
    /**
     * Array associativo com dados extraídos: 'os', 'os_icon', 'browser', 'browser_icon', 'full'.
     *
     * @var array{os: string, os_icon: string, browser: string, browser_icon: string, full: string}
     */
    public array $parsed;

    /**
     * Cria uma nova instância do componente UserAgent.
     */
    public function __construct(
        public ?string $agent = null,
        public ?string $value = null,
        public string $as = 'inline',
        public bool $showIcons = true,
        public bool $showText = true,
    ) {
        $raw = $this->value ?? $this->agent;
        $this->parsed = UserAgentSupport::parse($raw);
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.misc.user-agent');
    }
}
