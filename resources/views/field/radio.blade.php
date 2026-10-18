@php
    $attributes ??= new \Illuminate\View\ComponentAttributeBag;
@endphp

<x-misc.label :label="$label" :required="$required" :labelClass="$labelClass" />

@if($disabled && $value)
    <input type="hidden" name="{{ $nameField ?? $name }}" value="{{ $value }}">
@endif

@foreach($options as $optionValue => $text)
    <div class="{{ $wrapperClasses ?? 'form-check' }}">
        <input
            type="radio"
            id="{{ $id }}_{{ $optionValue }}"
            name="{{ $nameField ?? $name }}"
            value="{{ $optionValue }}"
            @checked((string) $selected === (string) $optionValue)
            @disabled($isDisabled)
            @if($required) required @endif
            {{ $attributes->merge(['class' => $baseRadioClasses ?? 'form-check-input']) }}
        />

        <label class="{{ $labelClass }}" for="{{ $id }}_{{ $optionValue }}">
            {{ $text }}
        </label>
    </div>
@endforeach

@if(($hasError ?? false) || (isset($errors) && ($errors->has($nameField ?? $name) || (!empty($dotName) && $errors->has($dotName)))))
    <div class="text-danger small mt-1">
        {{ $errorMessage ?: ($errors->first($nameField ?? $name) ?: $errors->first($dotName)) }}
    </div>
@endif