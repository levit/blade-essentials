@php
    $align = $align ?? 'middle';
    $class = $class ?? '';
    $alignmentClass = $alignmentClass ?? ($align !== '' ? 'align-' . $align : '');
@endphp

<tr {{ $attributes->class([$alignmentClass, $class]) }}>
    {{ $slot }}
</tr>