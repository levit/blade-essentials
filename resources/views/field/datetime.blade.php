{{--
/**
 * Componente de Campo de Formulário para DateTime
 * 
 * Este componente renderiza um label, um input com máscara de data e hora e as mensagens de erro do Laravel.
 * Suporta modo de visualização (browse) que desabilita o campo mas mantém o valor via hidden.
 *
 */
--}}

<x-misc.label :for="$id" :label="$label" :required="$isRequired" :labelClass="$labelClass" />

@if($disabled && $resolvedValue)
    <input type="hidden" name="{{ $nameField }}" value="{{ $resolvedValue }}">
@endif

<div class="d-flex align-items-center">
    <input
        type="text"
        id="{{ $id }}"
        name="{{ $nameField }}"
        value="{{ $displayValue }}"
        @required($isRequired)
        @readonly($readonly)
        @disabled($disabled)
        {{ $attributes->merge(['class' => $classes]) }}
    />
</div>

@if($hasError)
    <div class="invalid-feedback d-block">
        {{ $errorMessage }}
    </div>
@elseif(isset($errors) && ($errors->has($nameField) || ($dotName && $errors->has($dotName))))
    <div class="invalid-feedback d-block">
        {{ $errors->first($nameField) ?: $errors->first($dotName) }}
    </div>
@endif