@php
    $id = $id ?? ($component->id ?? ($name ? "modal_{$name}" : 'modal_' . \Select\Components\Support\StringSupport::nameRandom()));
    $dialogId = $dialogId ?? ($component->dialogId ?? "{$id}Dialog");
    $contentId = $contentId ?? ($component->contentId ?? "{$id}Content");
    $labelId = $labelId ?? ($component->labelId ?? "{$id}Label");
    $size = $size ?? ($component->size ?? null);
    $centered = $centered ?? ($component->centered ?? true);
    $scrollable = $scrollable ?? ($component->scrollable ?? true);
    $fade = $fade ?? ($component->fade ?? true);
    $static = $static ?? ($component->static ?? false);
    $backdrop = $backdrop ?? ($component->backdrop ?? null);
    $keyboard = $keyboard ?? ($component->keyboard ?? null);
    $color = $color ?? ($component->color ?? 'primary');
    $headerClass = $headerClass ?? ($component->headerClass ?? null);
    $bodyClass = $bodyClass ?? ($component->bodyClass ?? '');
    $footerClass = $footerClass ?? ($component->footerClass ?? 'bg-light border-top');
    $contentClass = $contentClass ?? ($component->contentClass ?? 'border shadow-lg');
    $dialogClass = $dialogClass ?? ($component->dialogClass ?? '');
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
    $title = $title ?? ($component->title ?? '');
    $titleClass = $titleClass ?? ($component->titleClass ?? '');
    $icon = $icon ?? ($component->icon ?? null);
    $iconColor = $iconColor ?? ($component->iconColor ?? null);
    $iconClass = $iconClass ?? ($component->iconClass ?? 'me-2 fs-5');
@endphp

<x-modal
    :name="$name ?? null"
    :id="$id"
    :dialogId="$dialogId"
    :contentId="$contentId"
    :labelId="$labelId"
    :title="$title"
    :titleClass="$titleClass"
    :icon="$icon"
    :iconColor="$iconColor"
    :iconClass="$iconClass"
    :size="$size"
    :centered="$centered"
    :scrollable="$scrollable"
    :fade="$fade"
    :static="$static"
    :backdrop="$backdrop"
    :keyboard="$keyboard"
    :color="$color"
    :headerClass="$headerClass"
    :bodyClass="$bodyClass"
    :footerClass="$footerClass"
    :contentClass="$contentClass"
    :dialogClass="$dialogClass"
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
    {{ $attributes }}
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
</x-modal>
