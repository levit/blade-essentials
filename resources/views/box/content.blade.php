@php
    $name = $name ?? ($component->name ?? \Select\Components\Support\StringSupport::nameRandom());
    $id = $id ?? ($component->id ?? "box_{$name}_id");
    $collapseId = $collapseId ?? ($component->collapseId ?? "collapse_{$name}_body_id");
    $hasIcon = $hasIcon ?? ($component->hasIcon ?? (!empty($icon)));
    $isLightHeader = $isLightHeader ?? ($component->isLightHeader ?? (!str_contains($bgTitleColor ?? '', 'gradient') && !str_contains($bgTitleColor ?? '', 'bg-primary') && !str_contains($bgTitleColor ?? '', 'bg-dark')));

    $sizeXs = $sizeXs ?? ($component->sizeXs ?? null);
    $sizeSm = $sizeSm ?? ($component->sizeSm ?? null);
    $sizeMd = $sizeMd ?? ($component->sizeMd ?? null);
    $sizeLg = $sizeLg ?? ($component->sizeLg ?? null);
    $sizeXl = $sizeXl ?? ($component->sizeXl ?? null);
    $sizeXxl = $sizeXxl ?? ($component->sizeXxl ?? null);

    $hasCustomSizes = $hasCustomSizes ?? ($component->hasCustomSizes ?? (!empty($sizeXs) || !empty($sizeSm) || !empty($sizeMd) || !empty($sizeLg) || !empty($sizeXl) || !empty($sizeXxl)));
    $renderRow = $renderRow ?? ($component->renderRow ?? ($hasRow ?? !$hasCustomSizes));
    $resolvedIconColor = $resolvedIconColor ?? ($component->resolvedIconColor ?? ($iconColor ?? ($isLightHeader ? 'primary' : 'white')));
    $collapseButtonClass = $collapseButtonClass ?? ($component->collapseButtonClass ?? ($isLightHeader ? 'btn-transparent-dark text-muted' : 'text-white'));
    $collapseIconColor = $collapseIconColor ?? ($component->collapseIconColor ?? ($isLightHeader ? 'muted' : 'white'));
    $titleColor = $titleColor ?? ($component->titleColor ?? 'dark');
    $bgTitleColor = $bgTitleColor ?? ($component->bgTitleColor ?? 'bg-white border-bottom py-3');
    $title = $title ?? ($component->title ?? '');
    $bottom_space = $bottom_space ?? ($component->bottom_space ?? 'mb-3');
    $collapse = $collapse ?? ($component->collapse ?? true);
    $cardClass = $cardClass ?? ($component->cardClass ?? '');
    $bodyClass = $bodyClass ?? ($component->bodyClass ?? '');
    $style = $style ?? ($component->style ?? '');
    $class = $class ?? ($component->class ?? '');
@endphp

@if($renderRow)
    <div class="row {{ $bottom_space }}">
@endif

    <div name="{{ $name }}" id="{{ $id }}" @class([
        'col-12' => !$hasCustomSizes,
        "col-{$sizeXs}" => !empty($sizeXs) && $sizeXs !== 'auto',
        "col-sm-{$sizeSm}" => !empty($sizeSm) && $sizeSm !== 'auto',
        "col-md-{$sizeMd}" => !empty($sizeMd) && $sizeMd !== 'auto',
        "col-lg-{$sizeLg}" => !empty($sizeLg) && $sizeLg !== 'auto',
        "col-xl-{$sizeXl}" => !empty($sizeXl) && $sizeXl !== 'auto',
        "col-xxl-{$sizeXxl}" => !empty($sizeXxl) && $sizeXxl !== 'auto',
        $bottom_space => !$renderRow && !empty($bottom_space),
        $class => !empty($class),
    ]) @style([$style => !empty($style)]) {{ $attributes ?? '' }}>

        <div @class(['card shadow-sm', $cardClass => !empty($cardClass)])>
            @if ($title)
                <div class="card-header d-flex justify-content-between align-items-center {{ $bgTitleColor }}">
                    <div class="d-flex align-items-center">
                        @if ($hasIcon)
                            <x-misc.icone :icon="$icon" :color="$resolvedIconColor" class="me-2" />
                        @endif

                        <span @class(["text-{$titleColor}" => !empty($titleColor), 'fw-semibold' => true])>
                            {{ $title }}
                        </span>
                    </div>
                    
                    <div class="d-flex align-items-center gap-2">
                        @if (isset($actions) && $actions)
                            <div>{{ $actions }}</div>
                        @endif

                        @if ($collapse)
                            <button class="btn btn-sm btn-icon box-collapse-btn {{ $collapseButtonClass }}" 
                                type="button" 
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $collapseId }}"
                                aria-expanded="true" 
                                aria-controls="{{ $collapseId }}" 
                                title="Minimizar / Expandir">
                                <x-misc.icone icon="bi-dash-lg" class="me-0 collapse-icon-expanded" :color="$collapseIconColor" size="sm" />
                                <x-misc.icone icon="bi-plus-lg" class="me-0 collapse-icon-collapsed" :color="$collapseIconColor" size="sm" />
                            </button>
                        @endif
                    </div>
                </div>
            @endif
            <div id="{{ $collapseId }}" class="collapse show flex-grow-1">
                <div @class([
                    'card-body',
                    $bodyClass => !empty($bodyClass),
                    'h-100 flex-grow-1 d-flex flex-column' => !empty($cardClass) && str_contains((string)$cardClass, 'h-100'),
                ])>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

@if($renderRow)
    </div>
@endif

@once
    @push('styles')
        <style>
            .box-collapse-btn.collapsed .collapse-icon-expanded {
                display: none !important;
            }
            .box-collapse-btn:not(.collapsed) .collapse-icon-collapsed {
                display: none !important;
            }
        </style>
    @endpush
@endonce