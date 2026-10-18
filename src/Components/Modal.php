<?php

declare(strict_types=1);

namespace Select\Components\Components;

use Select\Components\Components\Modal\Modal as BaseModal;

/**
 * Componente Blade para Janela Modal (Raiz de Components).
 *
 * Estende App\View\Components\Modal\Modal para garantir compatibilidade
 * direta com resolução automática do Laravel Blade para a tag <x-modal>.
 */
class Modal extends BaseModal
{
}
