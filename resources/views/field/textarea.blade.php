<x-misc.label :for="$id" :label="$label" :required="$required" :labelClass="$labelClass" />

@if($disabled && $value)
    <x-field.hidden :nameField="$nameField ?? $name" :value="$value" />
@endif

<textarea
    id="{{ $id }}"
    name="{{ $nameField ?? $name }}"
    rows="{{ $rows }}"
    @readonly($readonly)
    @disabled($disabled)
    @if($required) required @endif
    {{ $attributes->merge(['class' => $classes]) }}
>{{ $value }}</textarea>

@if(($hasError ?? false) || (isset($errors) && ($errors->has($nameField ?? $name) || ($dotName && $errors->has($dotName)))))
    <div class="invalid-feedback d-block">
        {{ $errorMessage ?: ($errors->first($nameField ?? $name) ?: $errors->first($dotName)) }}
    </div>
@endif