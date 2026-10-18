@php
    $attributes ??= new \Illuminate\View\ComponentAttributeBag;
    $id ??= 'input_' . \Illuminate\Support\Str::slug($nameField ?? ($name ?? 'user_agent'), '_') . '_id';
    $nameField = $nameField ?? ($name ?? 'user_agent');
    $label ??= 'Navegador / Cliente (User-Agent)';
    $labelClass ??= 'small mb-1';
    $inputClass ??= '';
    $isRequired ??= ($required ?? false);
    $readonly ??= true;
    $disabled ??= false;
    $fullText ??= ($value ?? ($agent ?? ''));
    if (!isset($parsed)) {
        $parsed = \Select\Components\Support\UserAgentSupport::parse($fullText);
    }
    $hasError ??= false;
    $errorMessage ??= null;
@endphp

<x-misc.label :for="$id" :label="$label" :required="$isRequired" :labelClass="$labelClass" />

<div class="input-group">
    <span class="input-group-text bg-light text-secondary border-end-0 px-2" title="Sistema Operacional: {{ $parsed['os'] }}" data-bs-toggle="tooltip">
        <x-misc.icone :icon="$parsed['os_icon']" class="fs-6" />
    </span>
    <span class="input-group-text bg-light text-secondary border-start-0 border-end-0 px-2" title="Navegador / Cliente: {{ $parsed['browser'] }}" data-bs-toggle="tooltip">
        <x-misc.icone :icon="$parsed['browser_icon']" class="fs-6" />
    </span>
    <input
        type="text"
        class="form-control bg-light text-dark {{ $inputClass }} {{ $hasError ? 'is-invalid' : '' }}"
        id="{{ $id }}"
        name="{{ $nameField }}"
        value="{{ $fullText }}"
        @readonly($readonly)
        @disabled($disabled)
        title="{{ $fullText }}"
        {{ $attributes }}
    />
</div>

@if($hasError)
    <div class="invalid-feedback d-block">{{ $errorMessage }}</div>
@endif
