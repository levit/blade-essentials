@php
    $rel = $rel ?? ($target === '_blank' ? 'noopener noreferrer' : null);
@endphp

<a href="{{ $href }}" id="{{ $id }}" @if($target && $target !== '_self') target="{{ $target }}" @endif title="{{ $title }}" @if($rel) rel="{{ $rel }}" @endif {{ $attributes->merge(['class' => 'text-decoration-none']) }}>
    {{ $label ?? $slot }}
</a>