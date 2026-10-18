@php
    $name = $name ?? ($component->name ?? \Select\Components\Support\StringSupport::nameRandom());
    $id = $id ?? ($component->id ?? "form_{$name}_id");

    $rawAction = $rawAction ?? ($component->rawAction ?? (($action ?? null) === '' || ($action ?? null) === '#' ? null : ($action ?? null)));
    $rawMethod = $rawMethod ?? ($component->rawMethod ?? strtoupper((string)($method ?? 'POST')));
    $htmlMethod = $htmlMethod ?? ($component->htmlMethod ?? (in_array($rawMethod, ['GET', 'POST'], true) ? $rawMethod : 'POST'));

    $hasCsrf = $hasCsrf ?? ($component->hasCsrf ?? ($csrf ?? ($needsCsrf ?? true)));
    $isSpoofed = $isSpoofed ?? ($component->isSpoofed ?? !in_array($rawMethod, ['GET', 'POST'], true));
@endphp

<form name="{{ $name }}" id="{{ $id }}" @if($rawAction) action="{{ $rawAction }}" @endif method="{{ $htmlMethod }}" @if(!empty($enctype)) enctype="{{ $enctype }}" @endif {{ $attributes ?? '' }}>
    @if($hasCsrf && $htmlMethod !== 'GET')
        @csrf
    @endif

    @if($isSpoofed)
        @method($rawMethod)
    @endif

    {{ $slot }}
</form>