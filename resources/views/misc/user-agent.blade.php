@php
    $parsed = $parsed ?? \Select\Components\Support\UserAgentSupport::parse($value ?? $agent ?? trim((string)$slot));
@endphp

@if($as === 'badge')
    <span class="d-inline-flex gap-1 align-items-center">
        <x-misc.badge color="secondary" soft :icon="$parsed['os_icon']" :title="'SO: ' . $parsed['os']">
            {{ $parsed['os'] }}
        </x-misc.badge>
        <x-misc.badge color="primary" soft :icon="$parsed['browser_icon']" :title="'Navegador: ' . $parsed['browser']">
            {{ $parsed['browser'] }}
        </x-misc.badge>
    </span>
@else
    <span {{ $attributes->class(['d-inline-flex align-items-center flex-wrap gap-1']) }} title="{{ $parsed['full'] }}">
        @if($showIcons)
            <x-misc.icone :icon="$parsed['os_icon']" class="text-secondary" title="SO: {{ $parsed['os'] }}" />
            <x-misc.icone :icon="$parsed['browser_icon']" class="text-secondary me-1" title="Navegador: {{ $parsed['browser'] }}" />
        @endif
        @if($showText)
            <span class="text-break">{{ $parsed['full'] }}</span>
        @endif
    </span>
@endif
