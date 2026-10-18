@php
    $resolvedRecordId = $resolvedRecordId ?? ($recordId ?? $id ?? null);
    $resolvedLib = $resolvedLib ?? (\Select\Components\Enums\IconeEnum::resolveLibrary('', $iconLibrary ?? 'bootstrap')->value);
    $showView = $showView ?? true;
    $showEdit = $showEdit ?? true;
    $showDelete = $showDelete ?? true;
    $isDisabled = $isDisabled ?? false;
    $iconView = $iconView ?? 'eye';
    $iconEdit = $iconEdit ?? 'pencil-square';
    $iconDelete = $iconDelete ?? 'trash';
    $tooltipView = $tooltipView ?? 'Visualizar';
    $tooltipEdit = $tooltipEdit ?? 'Editar';
    $tooltipDelete = $tooltipDelete ?? 'Excluir';

    $hasPrefixAndId = (!empty($routePrefix)) && $resolvedRecordId !== null;
    $urlView = $urlView ?? ($viewRoute ?: ($hasPrefixAndId ? route($routePrefix . '.show', $resolvedRecordId) : null));
    $urlEdit = $urlEdit ?? ($editRoute ?: ($hasPrefixAndId ? route($routePrefix . '.edit', $resolvedRecordId) : null));
    $urlDelete = $urlDelete ?? ($deleteRoute ?: ($hasPrefixAndId ? route($routePrefix . '.delete', $resolvedRecordId) : null));
    $hasActions = (bool) ($resolvedRecordId || $urlView || $urlEdit || $urlDelete);
@endphp

@if($hasActions)
<div class="d-flex gap-1 justify-content-end text-nowrap">
    @if($showView && $urlView)
        <x-field.button 
            :nameButton="'showItemButton-' . \Select\Components\Support\StringSupport::nameRandom()"
            :icon="$iconView"
            :iconLibrary="$resolvedLib" 
            class="btn btn-sm border-0 btn-outline-secondary p-1"
            :route="$urlView"
            :tooltip="$tooltipView"
            :title="$tooltipView"
            :alt="$tooltipView"
            type="link"
            :isDisabled="$isDisabled" />
    @endif

    @if($showEdit && $urlEdit)
        <x-field.button 
            :nameButton="'editItemButton-' . \Select\Components\Support\StringSupport::nameRandom()"
            :icon="$iconEdit"
            :iconLibrary="$resolvedLib"
            class="btn btn-sm border-0 btn-outline-secondary p-1"
            :route="$urlEdit"
            :tooltip="$tooltipEdit"
            :title="$tooltipEdit"
            :alt="$tooltipEdit"
            type="link"
            :isDisabled="$isDisabled" />
    @endif

    @if($showDelete && $urlDelete)
        <x-field.button 
            :nameButton="'deleteItemButton-' . \Select\Components\Support\StringSupport::nameRandom()"
            :icon="$iconDelete"
            :iconLibrary="$resolvedLib"
            iconColor="danger"
            class="btn btn-sm border-0 btn-outline-secondary p-1"
            :route="$urlDelete"
            :tooltip="$tooltipDelete"
            :title="$tooltipDelete"
            :alt="$tooltipDelete"
            type="link"
            :isDisabled="$isDisabled" />
    @endif
</div>
@endif