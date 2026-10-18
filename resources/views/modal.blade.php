@php
    $id = $id ?? ($component->id ?? ($name ? "modal_{$name}" : 'modal_' . \Select\Components\Support\StringSupport::nameRandom()));
    $dialogId = $dialogId ?? ($component->dialogId ?? "{$id}Dialog");
    $contentId = $contentId ?? ($component->contentId ?? "{$id}Content");
    $labelId = $labelId ?? ($component->labelId ?? "{$id}Label");

    $sizeClass = $sizeClass ?? ($component->sizeClass ?? match ($size ?? null) {
        'sm' => 'modal-sm',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        'fullscreen' => 'modal-fullscreen',
        'fullscreen-sm-down' => 'modal-fullscreen-sm-down',
        'fullscreen-md-down' => 'modal-fullscreen-md-down',
        'fullscreen-lg-down' => 'modal-fullscreen-lg-down',
        'fullscreen-xl-down' => 'modal-fullscreen-xl-down',
        'fullscreen-xxl-down' => 'modal-fullscreen-xxl-down',
        default => ($size ?? null) ? (str_starts_with((string)$size, 'modal-') ? $size : "modal-{$size}") : '',
    });

    $bsBackdrop = $bsBackdrop ?? ($component->bsBackdrop ?? (isset($backdrop) ? (is_bool($backdrop) ? ($backdrop ? 'true' : 'false') : (string)$backdrop) : (isset($static) && $static ? 'static' : null)));
    $bsKeyboard = $bsKeyboard ?? ($component->bsKeyboard ?? (isset($keyboard) ? (is_bool($keyboard) ? ($keyboard ? 'true' : 'false') : (string)$keyboard) : (isset($static) && $static ? 'false' : null)));

    $fade = $fade ?? ($component->fade ?? true);
    $centered = $centered ?? ($component->centered ?? true);
    $scrollable = $scrollable ?? ($component->scrollable ?? true);
    $dialogClass = $dialogClass ?? ($component->dialogClass ?? '');

    $title = $title ?? ($component->title ?? '');
    $titleClass = $titleClass ?? ($component->titleClass ?? '');
    $icon = $icon ?? ($component->icon ?? null);
    $iconColor = $iconColor ?? ($component->iconColor ?? null);
    $iconClass = $iconClass ?? ($component->iconClass ?? 'me-2 fs-5');
    $color = $color ?? ($component->color ?? 'primary');
    $headerClass = $headerClass ?? ($component->headerClass ?? null);
    $bodyClass = $bodyClass ?? ($component->bodyClass ?? '');
    $footerClass = $footerClass ?? ($component->footerClass ?? 'bg-light border-top');
    $contentClass = $contentClass ?? ($component->contentClass ?? 'border shadow-lg');
    $showHeader = $showHeader ?? ($component->showHeader ?? true);
    $dismissible = $dismissible ?? ($component->dismissible ?? true);
    $closeLabel = $closeLabel ?? ($component->closeLabel ?? 'Fechar');
    $rawBody = $rawBody ?? ($component->rawBody ?? false);
    $formAction = $formAction ?? ($component->formAction ?? null);
    $formMethod = $formMethod ?? ($component->formMethod ?? 'POST');
    $formEnctype = $formEnctype ?? ($component->formEnctype ?? null);
    $cancelLabel = $cancelLabel ?? ($component->cancelLabel ?? null);
    $cancelColor = $cancelColor ?? ($component->cancelColor ?? 'secondary');
    $cancelIcon = $cancelIcon ?? ($component->cancelIcon ?? null);
    $submitLabel = $submitLabel ?? ($component->submitLabel ?? null);
    $submitColor = $submitColor ?? ($component->submitColor ?? 'primary');
    $submitIcon = $submitIcon ?? ($component->submitIcon ?? null);
@endphp

<div
    @class(['modal', 'fade' => $fade])
    id="{{ $id }}"
    tabindex="-1"
    aria-labelledby="{{ $labelId }}"
    aria-hidden="true"
    @if(!is_null($bsBackdrop)) data-bs-backdrop="{{ $bsBackdrop }}" @endif
    @if(!is_null($bsKeyboard)) data-bs-keyboard="{{ $bsKeyboard }}" @endif
    {{ $attributes }}
>
    <div @class([
        'modal-dialog',
        $sizeClass => !empty($sizeClass),
        'modal-dialog-centered' => $centered,
        'modal-dialog-scrollable' => $scrollable,
        $dialogClass => !empty($dialogClass),
    ]) id="{{ $dialogId }}">
        <x-modal.content
            :id="$contentId"
            :labelId="$labelId"
            :title="$title"
            :titleClass="$titleClass"
            :icon="$icon"
            :iconColor="$iconColor"
            :iconClass="$iconClass"
            :color="$color"
            :headerClass="$headerClass"
            :bodyClass="$bodyClass"
            :footerClass="$footerClass"
            :contentClass="$contentClass"
            :showHeader="$showHeader"
            :dismissible="$dismissible"
            :closeLabel="$closeLabel"
            :rawBody="$rawBody"
            :formAction="$formAction"
            :formMethod="$formMethod"
            :formEnctype="$formEnctype"
            :cancelLabel="$cancelLabel"
            :cancelColor="$cancelColor"
            :cancelIcon="$cancelIcon"
            :submitLabel="$submitLabel"
            :submitColor="$submitColor"
            :submitIcon="$submitIcon"
        >
            @if(isset($header) && $header->isNotEmpty())
                <x-slot:header>{{ $header }}</x-slot:header>
            @endif

            @if(isset($titleSlot) && $titleSlot->isNotEmpty())
                <x-slot:titleSlot>{{ $titleSlot }}</x-slot:titleSlot>
            @endif

            @if(isset($actions) && $actions->isNotEmpty())
                <x-slot:actions>{{ $actions }}</x-slot:actions>
            @endif

            {{ $slot }}

            @if(isset($footer) && $footer->isNotEmpty())
                <x-slot:footer>{{ $footer }}</x-slot:footer>
            @endif
        </x-modal.content>
    </div>
</div>
