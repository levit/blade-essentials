@php
    // Fallback defensivo para View::make direto
    $resolvedLabel = $resolvedLabel ?? ($text ?? ($label ?? $value));
    $badgeClasses = $badgeClasses ?? "badge bg-secondary-soft text-secondary border border-secondary-soft rounded px-2 py-1 fw-semibold d-inline-flex align-items-center";
    $resolvedIcon = $resolvedIcon ?? $icon;
    $resolvedDotColor = $resolvedDotColor ?? ($dotColor ?? 'secondary');
    $iconPosition = $iconPosition ?? 'left';
    $role = $role ?? null;
@endphp

@if($href)
    <a href="{{ $href }}"
       @if($target) target="{{ $target }}" @endif
       @if($target === '_blank') rel="noopener noreferrer" @endif
       @if($title) title="{{ $title }}" @endif
       @if($role) role="{{ $role }}" @endif
       {{ $attributes->class([$badgeClasses]) }}>
        @if($dot)
            <span class="badge-dot rounded-circle me-1 @if($pulse) spinner-grow spinner-grow-sm @else bg-{{ $resolvedDotColor }} @endif"
                  style="width: 0.45rem; height: 0.45rem; display: inline-block; vertical-align: middle;"></span>
        @endif

        @if($resolvedIcon && $iconPosition === 'left')
            <x-misc.icone :icon="$resolvedIcon" class="me-1" />
        @endif

        <span>{{ $resolvedLabel ?? $slot }}</span>

        @if($resolvedIcon && $iconPosition === 'right')
            <x-misc.icone :icon="$resolvedIcon" class="ms-1" />
        @endif

        @if($dismissible)
            <button type="button" class="btn-close ms-1 p-0" style="font-size: 0.5rem;" aria-label="Remover"></button>
        @endif
    </a>
@else
    <span @if($title) title="{{ $title }}" @endif
          @if($role) role="{{ $role }}" @endif
          {{ $attributes->class([$badgeClasses]) }}>
        @if($dot)
            <span class="badge-dot rounded-circle me-1 @if($pulse) spinner-grow spinner-grow-sm @else bg-{{ $resolvedDotColor }} @endif"
                  style="width: 0.45rem; height: 0.45rem; display: inline-block; vertical-align: middle;"></span>
        @endif

        @if($resolvedIcon && $iconPosition === 'left')
            <x-misc.icone :icon="$resolvedIcon" class="me-1" />
        @endif

        <span>{{ $resolvedLabel ?? $slot }}</span>

        @if($resolvedIcon && $iconPosition === 'right')
            <x-misc.icone :icon="$resolvedIcon" class="ms-1" />
        @endif

        @if($dismissible)
            <button type="button" class="btn-close ms-1 p-0" style="font-size: 0.5rem;" aria-label="Remover"></button>
        @endif
    </span>
@endif
