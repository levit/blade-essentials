@php
    $attributes ??= new \Illuminate\View\ComponentAttributeBag;
    $name = $name ?? ($nameField ?? null);
@endphp

<div class="{{ $finalWrapperClass ?? ($wrapperClass ?? 'form-check') }}">
    <input
        type="{{ $type ?? 'checkbox' }}"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value ?? '1' }}"
        @checked($isChecked ?? false)
        @disabled($isDisabled ?? false)
        {{ $attributes->merge(['class' => $classes ?? ($checkboxClass ?? 'form-check-input')]) }}
    />

    {{-- Se desabilitado mas marcado, preserva o valor via hidden input para submissão --}}
    @if(($isDisabled ?? false) && ($isChecked ?? false) && $name)
        <input type="hidden" name="{{ $name }}" value="{{ $value ?? '1' }}">
    @endif

    <x-misc.label :for="$id" :label="$label ?? ''" :required="$required ?? false" :labelClass="$labelClass ?? 'form-check-label'">{{ $slot ?? '' }}</x-misc.label>

    @if(($hasError ?? false) || (isset($errors) && $name && ($errors->has($name) || (!empty($dotName) && $errors->has($dotName)))))
        <div class="invalid-feedback d-block">
            {{ $errorMessage ?: ($errors->first($name) ?: $errors->first($dotName)) }}
        </div>
    @endif
</div>