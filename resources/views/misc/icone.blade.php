@php
    // Fallback defensivo para View::make direto
    $enumLib = $enumLib ?? \Select\Components\Enums\IconeEnum::resolveLibrary($icon ?? '', $library ?? null);
    $hasIcon = $hasIcon ?? (!empty($icon) && trim((string)$icon) !== '');
    $iconName = $iconName ?? \Select\Components\Enums\IconeEnum::getNameWithoutPrefix($icon ?? '');
    $cssFile = $cssFile ?? $enumLib->cssFile();
    $jsFile = $jsFile ?? $enumLib->jsFile();
    $name = $name ?? \Select\Components\Support\StringSupport::nameRandom();
    $id = $id ?? "ico_{$name}_id";
    $tooltipText = $tooltipText ?? ($title ?? \Select\Components\Enums\IconeEnum::translateAction($iconName));
    $altText = $altText ?? ($alt ?? $tooltipText);
    $computedClasses = $computedClasses ?? trim((empty($color) ? '' : "text-{$color}") . ' ' . ($size ? "icon-{$size}" : '') . ' ' . ($class ?? ''));
@endphp

@if($hasIcon)
    @if($enumLib === Select\Components\Enums\IconeEnum::FEATHER)
        <i name="{{ $name }}" id="{{ $id }}" title="{{ $tooltipText }}" alt="{{ $altText }}" aria-label="{{ $altText }}" data-feather="{{ $iconName }}" @class([$computedClasses])></i>
    @elseif($enumLib === Select\Components\Enums\IconeEnum::BOOTSTRAP)
        <i name="{{ $name }}" id="{{ $id }}" title="{{ $tooltipText }}" alt="{{ $altText }}" aria-label="{{ $altText }}" @class(["bi", "bi-$iconName", $computedClasses])></i>
    @elseif($enumLib === Select\Components\Enums\IconeEnum::FONTAWESOME)
        <i name="{{ $name }}" id="{{ $id }}" title="{{ $tooltipText }}" alt="{{ $altText }}" aria-label="{{ $altText }}" @class(["fa-solid", "fa-$iconName", $computedClasses])></i>
    @elseif($enumLib === Select\Components\Enums\IconeEnum::MATERIAL)
        <i name="{{ $name }}" id="{{ $id }}" title="{{ $tooltipText }}" alt="{{ $altText }}" aria-label="{{ $altText }}" @class(["material-icons", $computedClasses])>{{ $iconName }}</i>
    @elseif($enumLib === Select\Components\Enums\IconeEnum::IONICONS)
        <ion-icon name="{{ $name }}" id="{{ $id }}" title="{{ $tooltipText }}" alt="{{ $altText }}" aria-label="{{ $altText }}" @class(["ionicons","ion-$iconName", $computedClasses])></ion-icon>
    @elseif($enumLib === Select\Components\Enums\IconeEnum::REMIXICON)
        <i name="{{ $name }}" id="{{ $id }}" title="{{ $tooltipText }}" alt="{{ $altText }}" aria-label="{{ $altText }}" @class(["ri-$iconName", $computedClasses])></i>
    @elseif($enumLib === Select\Components\Enums\IconeEnum::HEROICONS)
        <i name="{{ $name }}" id="{{ $id }}" title="{{ $tooltipText }}" alt="{{ $altText }}" aria-label="{{ $altText }}" @class(["heroicon", "heroicon-$iconName", $computedClasses])></i>
    @endif
@endif

@once('icon-lib-' . $enumLib->value)
    @if (!empty($cssFile))
        @push('styles')
            <link rel="stylesheet" href="{{ $cssFile }}" @if(!empty($enumLib->integrity())) integrity="{{ $enumLib->integrity() }}" @endif crossorigin="anonymous">
        @endpush
    @endif

    @if(!empty($jsFile))
        @push('scripts')
            <script src="{{ $jsFile }}" @if(!empty($enumLib->integrity())) integrity="{{ $enumLib->integrity() }}" @endif crossorigin="anonymous" data-search-pseudo-elements defer></script>
        @endpush
    @endif
@endonce