@php
    $isEmpty = $isEmpty ?? (empty($ip ?? $value) || ($ip ?? $value) === '-');
    $resolvedIp = $resolvedIp ?? ($isEmpty ? '-' : trim((string)($ip ?? $value)));
    $isLocal = $isLocal ?? false;
    $isPrivate = $isPrivate ?? false;
    $geoUrl = $geoUrl ?? "https://ipinfo.io/{$resolvedIp}";
    $resolvedIcon = $resolvedIcon ?? ($isLocal ? 'bi-server' : ($isPrivate ? 'bi-router' : 'bi-geo-alt-fill'));
    $resolvedTitle = $resolvedTitle ?? ($title ?? null);
    $serverSuffix = $serverSuffix ?? (($isLocal && filter_var($showServerLabel ?? true, FILTER_VALIDATE_BOOLEAN)) ? "(".($serverLabel ?? 'Servidor').")" : '');
@endphp

@if($isEmpty)
    <span {{ $attributes->class(['text-muted']) }}>-</span>
@elseif($as === 'badge')
    @if($isLocal)
        <x-misc.badge
            :title="$resolvedTitle"
            :color="$badgeColor"
            :icon="filter_var($showIcon, FILTER_VALIDATE_BOOLEAN) ? $resolvedIcon : null"
            soft
            {{ $attributes->merge(['class' => 'font-monospace']) }}
        >
            <span>{{ $resolvedIp }}</span>
            @if($serverSuffix)
                <span class="opacity-75 ms-1">{{ $serverSuffix }}</span>
            @endif
        </x-misc.badge>
    @else
        <x-misc.badge
            :href="$geoUrl"
            :target="$target"
            :rel="$rel"
            :title="$resolvedTitle"
            :color="$badgeColor"
            :icon="filter_var($showIcon, FILTER_VALIDATE_BOOLEAN) ? $resolvedIcon : null"
            soft
            {{ $attributes->merge(['class' => 'font-monospace lift-sm']) }}
        >
            <span>{{ $resolvedIp }}</span>
            <x-misc.icone icon="bi-box-arrow-up-right" class="opacity-75 ms-1" style="font-size: 0.7em;" />
        </x-misc.badge>
    @endif
@elseif($as === 'text' || $as === 'link')
    @if($isLocal)
        <span title="{{ $resolvedTitle }}"
              {{ $attributes->merge(['class' => 'ip-address-local font-monospace d-inline-flex align-items-center gap-1 text-secondary']) }}>
            @if(filter_var($showIcon, FILTER_VALIDATE_BOOLEAN))
                <x-misc.icone :icon="$resolvedIcon" class="text-secondary" style="font-size: 0.85em;" />
            @endif
            <span>{{ $resolvedIp }}</span>
            @if($serverSuffix)
                <span class="text-muted fw-normal">{{ $serverSuffix }}</span>
            @endif
        </span>
    @else
        <x-misc.link :href="$geoUrl"
           :target="$target"
           :rel="$rel"
           :title="$resolvedTitle"
           {{ $attributes->merge(['class' => 'ip-address-link font-monospace d-inline-flex align-items-center gap-1']) }}>
            @if(filter_var($showIcon, FILTER_VALIDATE_BOOLEAN))
                <x-misc.icone :icon="$resolvedIcon" class="{{ $isPrivate ? 'text-secondary' : 'text-primary' }}" style="font-size: 0.85em;" />
            @endif
            <span>{{ $resolvedIp }}</span>
            <x-misc.icone icon="bi-box-arrow-up-right" class="text-muted opacity-75 ms-0.5" style="font-size: 0.7em;" />
        </x-misc.link>
    @endif
@else
    {{-- Padrão 'code' --}}
    @if($isLocal)
        <span title="{{ $resolvedTitle }}"
              {{ $attributes->merge(['class' => 'ip-address-local d-inline-flex align-items-center font-monospace']) }}>
            <code class="px-2 py-0.5 rounded bg-light border border-light-subtle text-secondary fw-medium small d-inline-flex align-items-center gap-1">
                @if(filter_var($showIcon, FILTER_VALIDATE_BOOLEAN))
                    <x-misc.icone :icon="$resolvedIcon" class="text-secondary" style="font-size: 0.85em;" />
                @endif
                <span>{{ $resolvedIp }}</span>
                @if($serverSuffix)
                    <span class="text-muted fw-normal">{{ $serverSuffix }}</span>
                @endif
            </code>
        </span>
    @else
        <x-misc.link :href="$geoUrl"
           :target="$target"
           :rel="$rel"
           :title="$resolvedTitle"
           {{ $attributes->merge(['class' => 'ip-address-link d-inline-flex align-items-center font-monospace']) }}>
            <code class="px-2 py-0.5 rounded bg-light border border-light-subtle text-primary fw-medium small d-inline-flex align-items-center gap-1">
                @if(filter_var($showIcon, FILTER_VALIDATE_BOOLEAN))
                    <x-misc.icone :icon="$resolvedIcon" class="{{ $isPrivate ? 'text-secondary' : 'text-primary' }}" style="font-size: 0.85em;" />
                @endif
                <span>{{ $resolvedIp }}</span>
                <x-misc.icone icon="bi-box-arrow-up-right" class="text-muted opacity-75 ms-0.5" style="font-size: 0.7em;" />
            </code>
        </x-misc.link>
    @endif
@endif
