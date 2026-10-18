<?php

declare(strict_types=1);

namespace Select\Components\Components\Window;

use Select\Components\Components\Modal\Modal as BaseModal;
use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Janela Modal de Sistema (Window Modal).
 *
 * Envelopa janelas modais reaproveitando a infraestrutura padronizada
 * de modal com personalização visual completa.
 */
class Modal extends BaseModal
{
    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.window.modal');
    }
}
