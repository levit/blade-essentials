<?php

declare(strict_types=1);

namespace Select\Components\Components\Misc;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Mensagens e Alertas do Sistema.
 *
 * Renderiza notificações de sessão (flash messages baseadas em MessageTypeEnum)
 * e o bloco de erros de validação ($errors) com auto-dismiss configurável.
 */
class Messages extends Component
{
    /**
     * Tempo em milissegundos para auto-fechamento dos alertas.
     */
    public int $timeoutMs;

    /**
     * Cria uma nova instância do componente Messages.
     *
     * @param string|null $name Atributo name identificador.
     * @param string|null $id Atributo id identificador.
     * @param int $timeout Tempo em segundos para encerramento automático dos alertas.
     */
    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public int $timeout = 7,
    ) {
        $this->timeoutMs = max(1, $this->timeout) * 1000;
    }

    /**
     * Retorna as mensagens flash ativas da sessão mapeadas com tipos e metadados.
     *
     * @return array<int, array{type: \Select\Components\Enums\MessageTypeEnum, message: string, class: string, icon: string}>
     */
    public function flashMessages(): array
    {
        $messages = [];

        foreach (\Select\Components\Enums\MessageTypeEnum::cases() as $type) {
            $flashMessage = session($type->value);

            if (! $flashMessage && $type === \Select\Components\Enums\MessageTypeEnum::ERROR) {
                $flashMessage = session('error');
            }

            if (! $flashMessage && $type === \Select\Components\Enums\MessageTypeEnum::SUCCESS) {
                $flashMessage = session('status');
            }

            if ($flashMessage) {
                $messages[] = [
                    'type' => $type,
                    'message' => (string) $flashMessage,
                    'class' => $type->getClass(),
                    'icon' => $type->getIcon(),
                ];
            }
        }

        return $messages;
    }

    /**
     * Verifica se há mensagens flash ativas na sessão.
     */
    public function hasMessages(): bool
    {
        return ! empty($this->flashMessages());
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.misc.messages');
    }
}
