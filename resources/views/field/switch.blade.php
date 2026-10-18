{{--
/**
 * Componente de Campo de Formulário para Switch Toggle (Field Switch)
 *
 * Renderiza um switch toggle padronizado do Bootstrap 5 com role="switch",
 * suporte a label (prop ou slot), resolução inteligente de estado checado
 * (suportando notação de array tanto em colchetes quanto em ponto para old()),
 * estados disabled/onlyView e feedback de erros de validação.
 */
--}}

<div class="{{ $finalWrapperClass }}">
    <input
        type="checkbox"
        role="switch"
        id="{{ $id }}"
        @if($nameField) name="{{ $nameField }}" @endif
        value="{{ $value }}"
        @checked($isChecked)
        @disabled($isDisabled)
        {{ $attributes->merge(['class' => $inputClasses]) }}
    />

    {{-- Se desabilitado mas marcado em formulário editável, preserva o valor via hidden input --}}
    @if($isDisabled && $isChecked && $nameField && !$onlyView)
        <input type="hidden" name="{{ $nameField }}" value="{{ $value }}">
    @endif

    <x-misc.label :for="$id" :label="$label" :required="$isRequired" :labelClass="$labelClass">{{ $slot }}</x-misc.label>

    @if($hasError)
        <div class="invalid-feedback d-block">
            {{ $errorMessage }}
        </div>
    @elseif(isset($errors) && $nameField && ($errors->has($nameField) || ($dotName && $errors->has($dotName))))
        <div class="invalid-feedback d-block">
            {{ $errors->first($nameField) ?: $errors->first($dotName) }}
        </div>
    @endif
</div>
