{{--
/**
 * Componente de Campo de Formulário para Datepicker
 * 
 * Este componente renderiza um label, um input com máscara de data e as mensagens de erro do Laravel.
 * Suporta modo de visualização (browse) que desabilita o campo mas mantém o valor via hidden.
 *
 */
--}}

<x-misc.label :for="$id" :label="$label" :required="$isRequired" :labelClass="$labelClass" />

@if($disabled && $resolvedValue)
    <input type="hidden" name="{{ $nameField }}" value="{{ $resolvedValue }}">
@endif

<input
    type="text"
    id="{{ $id }}"
    name="{{ $nameField }}"
    value="{{ $resolvedValue }}"
    autocomplete="off"
    data-datepicker="true"
    data-format="{{ $format }}"
    @if($minDate) data-min-date="{{ $minDate }}" @endif
    @if($maxDate) data-max-date="{{ $maxDate }}" @endif
    @required($isRequired)
    @readonly($readonly)
    @disabled($disabled)
    {{ $attributes->merge(['class' => $classes]) }}
/>

@if($hasError)
    <div class="invalid-feedback d-block">
        {{ $errorMessage }}
    </div>
@elseif(isset($errors) && ($errors->has($nameField) || ($dotName && $errors->has($dotName))))
    <div class="invalid-feedback d-block">
        {{ $errors->first($nameField) ?: $errors->first($dotName) }}
    </div>
@endif

@once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Inicializa todos os datepickers
                const datepickers = document.querySelectorAll('[data-datepicker="true"]');
                
                datepickers.forEach(function(input) {
                    // Se o input está disabled ou readonly, não inicializa o flatpickr
                    // (ou inicializa com clickOpens: false para modo show)
                    const isDisabled = input.hasAttribute('disabled');
                    const isReadonly = input.hasAttribute('readonly');
                    
                    flatpickr(input, {
                        locale: 'pt',
                        dateFormat: 'd/m/Y',
                        altInput: false,
                        allowInput: !isReadonly && !isDisabled,
                        clickOpens: !isReadonly && !isDisabled,
                        minDate: input.dataset.minDate || null,
                        maxDate: input.dataset.maxDate || null,
                        
                        // Se estiver readonly/disabled, desabilita interação
                        onReady: function(selectedDates, dateStr, instance) {
                            if (isDisabled || isReadonly) {
                                instance.calendarContainer.style.pointerEvents = 'none';
                            }
                        },
                    });
                });
            });
        </script>

    @endpush
@endonce