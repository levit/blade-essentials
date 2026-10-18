{{--
/**
 * Componente de Input Group Reutilizável (SOLID / Bootstrap 5 / SB Admin Pro)
 * 
 * Permite composição flexível de inputs com botões, ícones, textos e seletores
 * tanto via prepend/append quanto diretamente via composição de elementos no slot padrão.
 *
 */
--}}

@php
    $attributes ??= new \Illuminate\View\ComponentAttributeBag;
    $for ??= null;
    $label ??= null;
    $labelClass ??= 'small mb-1';
    $isRequired ??= ($required ?? false);
    $groupClasses ??= ('input-group ' . ($class ?? ''));
    $hasError ??= false;
    $errorMessage ??= null;
@endphp

<x-misc.label :for="$for" :label="$label" :required="$isRequired" :labelClass="$labelClass" />

<div class="{{ $groupClasses }}" {{ $attributes }}>
    @if(!empty($prepend) && (!is_object($prepend) || !method_exists($prepend, 'isNotEmpty') || $prepend->isNotEmpty()))
        {{ $prepend }}
    @endif

    {{ $slot }}

    @if(!empty($append) && (!is_object($append) || !method_exists($append, 'isNotEmpty') || $append->isNotEmpty()))
        {{ $append }}
    @endif
</div>

@if($hasError)
    <div class="invalid-feedback d-block">{{ $errorMessage }}</div>
@endif
