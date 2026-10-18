<?php

declare(strict_types=1);

namespace Select\Components\Components\Misc;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Teclas de Atalho e Combinações de Teclado (Kbd).
 *
 * Renderiza elementos <kbd> semânticos e estilizados, com suporte a combinações
 * múltiplas (ex: 'Alt+N', ['Ctrl', 'Shift', 'F']), variantes visuais (default, subtle, light, dark),
 * tamanhos adaptáveis e contraste refinado para botões e cabeçalhos.
 */
class Kbd extends Component
{
    /**
     * Lista de teclas resolvidas e formatadas para exibição.
     *
     * @var array<string>
     */
    public array $resolvedKeys = [];

    /**
     * Texto unificado de atalho para exibição inline.
     */
    public string $displayShortcut = '';

    /**
     * Classes CSS finais resolvidas para o componente.
     */
    public string $kbdClasses = '';

    /**
     * Cria uma nova instância do componente Kbd.
     *
     * @param string|array<string>|null $keys Combinação de teclas (ex: 'Alt+N', ['Alt', 'N']).
     * @param string|null $key Tecla única ou atalho direto.
     * @param string|null $modifier Modificador padrão caso não informado (ex: 'Alt', 'Ctrl').
     * @param string $variant Variante visual ('default', 'subtle', 'light', 'dark').
     * @param string $size Tamanho do elemento ('xs', 'sm', 'md').
     * @param bool $segmented Se deve renderizar teclas separadas com sinal de adição (+).
     * @param string $class Classes CSS adicionais.
     */
    public function __construct(
        public string|array|null $keys = null,
        public ?string $key = null,
        public ?string $modifier = null,
        public string $variant = 'default',
        public string $size = 'sm',
        public bool $segmented = false,
        public string $class = '',
    ) {
        $rawKeys = $this->keys ?? $this->key;

        if (is_array($rawKeys)) {
            $this->resolvedKeys = array_values(array_filter(array_map('trim', $rawKeys)));
        } elseif (is_string($rawKeys) && trim($rawKeys) !== '') {
            $trimmed = trim($rawKeys);
            if (str_contains($trimmed, '+')) {
                $this->resolvedKeys = array_values(array_filter(array_map('trim', explode('+', $trimmed))));
            } else {
                $this->resolvedKeys = [$trimmed];
            }
        } else {
            $this->resolvedKeys = [];
        }

        if ($this->modifier !== null && $this->modifier !== '' && count($this->resolvedKeys) === 1) {
            $modUpper = ucfirst(trim($this->modifier));
            if (!in_array(strtolower($modUpper), array_map('strtolower', $this->resolvedKeys), true)) {
                array_unshift($this->resolvedKeys, $modUpper);
            }
        }

        // Formatação das teclas para maiúsculo adequado (ex: alt -> Alt, n -> N, ctrl -> Ctrl)
        $this->resolvedKeys = array_map(function (string $k): string {
            $kLower = strtolower($k);

            return match ($kLower) {
                'alt' => 'Alt',
                'ctrl', 'control' => 'Ctrl',
                'shift' => 'Shift',
                'cmd', 'meta', 'command' => 'Cmd',
                'esc', 'escape' => 'Esc',
                'enter' => 'Enter',
                'tab' => 'Tab',
                'space' => 'Space',
                'backspace' => 'Backspace',
                'del', 'delete' => 'Del',
                default => strtoupper($k),
            };
        }, $this->resolvedKeys);

        $this->displayShortcut = implode('+', $this->resolvedKeys);

        $variantClass = match ($this->variant) {
            'subtle' => 'kbd-subtle',
            'light' => 'kbd-light',
            'dark' => 'kbd-dark',
            default => 'kbd-default',
        };

        $sizeClass = match ($this->size) {
            'xs' => 'kbd-xs',
            'md' => 'kbd-md',
            default => 'kbd-sm',
        };

        $this->kbdClasses = trim("kbd {$sizeClass} {$variantClass} {$this->class}");
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.misc.kbd');
    }
}

