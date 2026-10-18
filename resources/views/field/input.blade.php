<x-misc.label :for="$id" :label="$label" :required="$required" :labelClass="$labelClass" />

@if($disabled && $value)
    <x-field.hidden :nameField="$nameField ?? $name" :value="$value" />
@endif

<input
    id="{{ $id }}"
    name="{{ $nameField ?? $name }}"  
    type="{{ $inputType }}"
    value="{{ $value }}"
    @if($required) required @endif
    @readonly($readonly)
    @disabled($disabled)
    {{ $attributes->merge(['class' => $classes]) }}
/>

@if(($showError ?? true) && (($hasError ?? false) || (isset($errors) && ($errors->has($nameField ?? $name) || ($dotName && $errors->has($dotName))))))
    <div class="invalid-feedback d-block">{{ $errorMessage ?: ($errors->first($nameField ?? $name) ?: $errors->first($dotName)) }}</div>
@endif