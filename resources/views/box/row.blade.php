@php
    $attributes = $attributes ?? new \Illuminate\View\ComponentAttributeBag();
    $id = $id ?? ($component->id ?? null);
    $bottom_space = $attributes->get('bottom_space') ?? ($attributes->get('bottom-space') ?? ($bottomSpace ?? ($bottom_space ?? ($component->bottom_space ?? 'mb-3'))));
    $class = $class ?? ($component->class ?? null);
@endphp

<div @if($id) id="{{ $id }}" @endif @class([
    'row',
    $bottom_space => !empty($bottom_space),
    $class => !empty($class),
]) {{ $attributes->except(['bottom_space', 'bottom-space', 'bottomSpace']) }}>
    {{ $slot }}
</div>