@php
    $name = $name ?? ($component->name ?? \Select\Components\Support\StringSupport::nameRandom());
    $id = $id ?? ($component->id ?? "windowHeader_{$name}_id");
    $hasIcon = $hasIcon ?? ($component->hasIcon ?? (!empty($icon) && trim((string)$icon) !== ''));
    $caption = $caption ?? ($component->caption ?? '');
    $subtitle = $subtitle ?? ($component->subtitle ?? null);
    $resolvedBreadcrumbs = $resolvedBreadcrumbs ?? ($component->resolvedBreadcrumbs ?? (isset($breadcrumbs) ? (new \Select\Components\Components\Window\Header())->resolveBreadcrumbs($breadcrumbs) : []));
@endphp

<header name="{{ $name }}" id="{{ $id }}" class="page-header page-header-compact page-header-light border-bottom bg-white mb-3" {{ $attributes ?? '' }}>
    <div class="container-fluid px-2">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-2 pb-1">
                <div class="col-auto mb-2">
                    <h1 class="page-header-title d-flex align-items-center mb-0">
                        @if($hasIcon)
                            <div class="page-header-icon me-2">
                                <x-misc.icone :icon="$icon" color="primary" class="mt-0" />
                            </div>
                        @endif
                        <span>{{ $caption }}</span>
                    </h1>
                    @if ($subtitle)
                        <div class="page-header-subtitle mt-1 text-muted small">{{ $subtitle }}</div>
                    @endif
                </div>

                @if ((isset($actions) && $actions) || (isset($slot) && $slot->isNotEmpty()))
                    <div class="col-12 col-xl-auto mb-2 d-flex align-items-center gap-2">
                        {{ $actions ?? $slot }}
                    </div>
                @endif
            </div>

            @if (!empty($resolvedBreadcrumbs))
                <nav aria-label="breadcrumb" class="mt-n1 mb-2">
                    <ol class="breadcrumb mb-0 py-1 small">
                        @foreach ($resolvedBreadcrumbs as $breadcrumb)
                            @if (!empty($breadcrumb['url']))
                                <li class="breadcrumb-item">
                                    <x-misc.link :href="$breadcrumb['url']">{{ $breadcrumb['label'] }}</x-misc.link>
                                </li>
                            @else
                                <li class="breadcrumb-item active text-muted" aria-current="page">
                                    {{ $breadcrumb['label'] }}
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            @endif
        </div>
    </div>
</header>