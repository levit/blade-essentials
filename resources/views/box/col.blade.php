@php
    $attributes = $attributes ?? new \Illuminate\View\ComponentAttributeBag();
    $id = $id ?? ($component->id ?? null);
    $resolvedBottomSpace = $attributes->get('bottom_space') ?? ($attributes->get('bottom-space') ?? ($bottomSpace ?? ($bottom_space ?? ($component?->bottom_space ?? null))));
    $gridClasses = $gridClasses ?? ($component?->calculateGridClasses() ?? array_filter([
        'col',
        !empty($resolvedBottomSpace) ? $resolvedBottomSpace : null,
        isset($sizeXs) && $sizeXs !== '' ? ($sizeXs === 'auto' ? 'col-auto' : "col-{$sizeXs}") : null,
        isset($sizeSm) && $sizeSm !== '' ? ($sizeSm === 'auto' ? 'col-sm-auto' : "col-sm-{$sizeSm}") : null,
        isset($sizeMd) && $sizeMd !== '' ? ($sizeMd === 'auto' ? 'col-md-auto' : "col-md-{$sizeMd}") : null,
        isset($sizeLg) && $sizeLg !== '' ? ($sizeLg === 'auto' ? 'col-lg-auto' : "col-lg-{$sizeLg}") : null,
        isset($sizeXl) && $sizeXl !== '' ? ($sizeXl === 'auto' ? 'col-xl-auto' : "col-xl-{$sizeXl}") : null,
        isset($sizeXxl) && $sizeXxl !== '' ? ($sizeXxl === 'auto' ? 'col-xxl-auto' : "col-xxl-{$sizeXxl}") : null,
        $class ?? null,
    ]));
    if (!empty($resolvedBottomSpace) && !in_array($resolvedBottomSpace, $gridClasses, true)) {
        $gridClasses[] = $resolvedBottomSpace;
    }
@endphp

<div @if($id) id="{{ $id }}" @endif @class($gridClasses) {{ $attributes->except(['bottom_space', 'bottom-space', 'bottomSpace']) }}>
    {{ $slot }}
</div>