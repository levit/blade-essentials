@php
    $resolvedSrc = $resolvedSrc ?? $src;
    $inlineStyle = $inlineStyle ?? '';
@endphp

<img src="{{ $resolvedSrc }}"
     alt="{{ $alt }}"
     @if($width) width="{{ $width }}" @endif
     @if($height) height="{{ $height }}" @endif
     @if($lazy) loading="lazy" @endif
     @if($fallbackText)
         onerror="this.style.display='none'; if(this.nextElementSibling){this.nextElementSibling.classList.remove('d-none');}"
     @endif
     {{ $attributes->merge(['style' => $inlineStyle]) }} />
@if($fallbackText)
    <span class="d-none {{ $attributes->get('class') }}">{{ $fallbackText }}</span>
@endif
