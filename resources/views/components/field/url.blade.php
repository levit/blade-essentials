{{--
/**
 * Componente de Campo de Formulário para URL
 * 
 * Este componente renderiza um label, um input do tipo url e as mensagens de erro do Laravel.
 * Suporta modo de visualização (browse) que desabilita o campo mas mantém o valor via hidden.
 *
 */
--}}

@php
    $attributes ??= new \Illuminate\View\ComponentAttributeBag;
    $id ??= 'input_' . \Illuminate\Support\Str::slug($nameField ?? ($name ?? 'url'), '_') . '_id';
    $nameField = $nameField ?? ($name ?? 'url');
    $label ??= '';
    $labelClass ??= 'small mb-1';
    $resolvedValue ??= ($value ?? null);
    $isRequired ??= ($required ?? false);
    $readonly ??= false;
    $disabled ??= false;
    $hasError ??= false;
    $errorMessage ??= null;
    $classes ??= ($inputClass ?? 'form-control');
@endphp

<x-misc.label :for="$id" :label="$label" :required="$isRequired" :labelClass="$labelClass" />

@if($disabled && $resolvedValue)
    <x-field.hidden :nameField="$nameField" :value="$resolvedValue" />
@endif

<input
    class="{{ $classes }}"
    id="{{ $id }}"
    name="{{ $nameField }}"
    type="url"
    value="{{ $resolvedValue }}"
    @readonly($readonly)
    @disabled($disabled)    
    @required($isRequired)
    {{ $attributes }}
/>

@if($hasError)
    <div class="invalid-feedback d-block">
        {{ $errorMessage }}
    </div>
@endif