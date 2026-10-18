@php
    $labelText = $labelText ?? ($label ?? $value);
    $defaultClass = $defaultClass ?? ($labelClass ?? 'small mb-1');
    $hasContent = \Select\Components\Support\StringSupport::hasValue($labelText) || (isset($slot) && $slot->isNotEmpty());
@endphp

@if ($hasContent)
    <label @if($for) for="{{ $for }}" @endif {{ $attributes->merge(['class' => $defaultClass]) }}>
        {{ (isset($slot) && $slot->isNotEmpty()) ? $slot : $labelText }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
@endif
