@php
    $attributes ??= new \Illuminate\View\ComponentAttributeBag;

    $baseAttrs = [
        'id' => $id,
        'name' => $name,
        'title' => $tooltipText,
        'alt' => $altText,
        'aria-label' => $tooltipText,
    ];

    if ($resolvedAccessKey) {
        $baseAttrs['accesskey'] = $resolvedAccessKey;
    }
    if ($resolvedShortcut) {
        $baseAttrs['data-shortcut'] = $resolvedShortcut;
    }

    $mergedAttributes = $attributes->merge($baseAttrs);
    if ($isDisabled) {
        $mergedAttributes = $mergedAttributes->merge([
            'tabindex' => '-1',
            'aria-disabled' => 'true',
        ]);
    }
@endphp

@if ($isButton)
    <button {{ $mergedAttributes->class([$closeClass, $class, $btnBaseClass, $sizeClass, $btnColor]) }}
        type="{{ $buttonType }}"
        @if ($route) formaction="{{ $route }}" @endif
        @if ($routeMethod) formmethod="{{ $routeMethod }}" @endif
        @disabled($isDisabled)>
@else
    <a href="{{ $route }}"
        {{ $mergedAttributes->class(['btn', $sizeClass, $btnColor, $class, 'disabled' => $isDisabled]) }}>
@endif

@if ($icon)
    <x-misc.icone :library="$iconLibrary" :icon="$icon" :color="$iconColor" :class="$finalIconClass" :size="$iconSize"
        :title="$tooltipText" :alt="$altText" />
@endif

@if ($label)
    <span class="btn-label">{{ $label }}</span>
@endif

{{ $slot }}

@if ($resolvedShortcutLabel && $showShortcut && !$isClose)
    <x-misc.kbd :keys="$resolvedShortcutLabel" variant="subtle" size="xs" class="btn-shortcut-kbd ms-2" />
@endif

@if ($isButton)
    </button>
@else
    </a>
@endif
