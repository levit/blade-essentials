@php
    $attributes ??= new \Illuminate\View\ComponentAttributeBag;
    $id ??= 'input_' . \Illuminate\Support\Str::slug($nameField ?? ($name ?? 'ip_address'), '_') . '_id';
    $nameField = $nameField ?? ($name ?? 'ip_address');
    $label ??= 'Endereço IP de Origem';
    $labelClass ??= 'small mb-1';
    $inputClass ??= '';
    $isRequired ??= ($required ?? false);
    $readonly ??= true;
    $disabled ??= false;
    $displayIp ??= ($value ?? ($ip ?? 'N/A'));
    $icon ??= 'bi-hdd-network';
    $title ??= null;
    $isLocal ??= false;
    $geoUrl ??= null;
    $hasError ??= false;
    $errorMessage ??= null;
@endphp

<x-misc.label :for="$id" :label="$label" :required="$isRequired" :labelClass="$labelClass" />

<div class="input-group">
    <span class="input-group-text bg-light text-secondary border-end-0 px-2" title="{{ $title }}" data-bs-toggle="tooltip">
        <x-misc.icone :icon="$icon" class="fs-6" />
    </span>
    <input
        type="text"
        class="form-control bg-light text-dark font-monospace {{ $inputClass }} {{ $hasError ? 'is-invalid' : '' }}"
        id="{{ $id }}"
        name="{{ $nameField }}"
        value="{{ $displayIp }}"
        @readonly($readonly)
        @disabled($disabled)
        title="{{ $title }}"
        {{ $attributes }}
    />
    @if($isLocal)
        <span class="input-group-text bg-light text-muted small px-2 border-start-0" title="Origem Local (Servidor)">
            Servidor
        </span>
    @elseif(!empty($geoUrl))
        <x-misc.link :href="$geoUrl" target="_blank" rel="noopener noreferrer" class="input-group-text bg-light text-muted px-2 border-start-0" title="Consultar geolocalização no ipinfo.io" data-bs-toggle="tooltip">
            <x-misc.icone icon="bi-box-arrow-up-right" style="font-size: 0.8em;" />
        </x-misc.link>
    @endif
</div>

@if($hasError)
    <div class="invalid-feedback d-block">{{ $errorMessage }}</div>
@endif
