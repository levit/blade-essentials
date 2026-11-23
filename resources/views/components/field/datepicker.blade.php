{{--
/**
 * Componente de Campo de Formulário para Datepicker
 * 
 * Este componente renderiza um label, um input com máscara de data e as mensagens de erro do Laravel.
 * Suporta modo de visualização (browse) que desabilita o campo mas mantém o valor via hidden.
 * Possui suporte a ícone lateral clicável para alternar a exibição do mini calendário (Flatpickr).
 *
 */
--}}

<x-misc.label :for="$id" :label="$label" :required="$isRequired" :labelClass="$labelClass" />

@if($disabled && $resolvedValue)
    <input type="hidden" name="{{ $nameField }}" value="{{ $resolvedValue }}">
@endif

@if($showIcon)
    <div class="input-group">
        @if($iconPosition === 'prepend')
            <button
                type="button"
                class="{{ $buttonClass }} datepicker-toggle"
                data-datepicker-toggle="#{{ $id }}"
                title="Abrir calendário"
                aria-label="Abrir calendário"
                tabindex="-1"
                @disabled($disabled || $readonly)
            >
                <x-misc.icone :icon="$icon" :library="$iconLibrary" />
            </button>
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
            @if($iconOnly) data-icon-only="true" @endif
            @required($isRequired)
            @readonly($readonly)
            @disabled($disabled)
            {{ $attributes->merge(['class' => $classes]) }}
        />

        @if($iconPosition === 'append')
            <button
                type="button"
                class="{{ $buttonClass }} datepicker-toggle"
                data-datepicker-toggle="#{{ $id }}"
                title="Abrir calendário"
                aria-label="Abrir calendário"
                tabindex="-1"
                @disabled($disabled || $readonly)
            >
                <x-misc.icone :icon="$icon" :library="$iconLibrary" />
            </button>
        @endif
    </div>
@else
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
        @if($iconOnly) data-icon-only="true" @endif
        @required($isRequired)
        @readonly($readonly)
        @disabled($disabled)
        {{ $attributes->merge(['class' => $classes]) }}
    />
@endif

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
                    const isIconOnly = input.hasAttribute('data-icon-only');
                    
                    const fp = flatpickr(input, {
                        locale: 'pt',
                        dateFormat: 'd/m/Y',
                        altInput: false,
                        allowInput: !isReadonly && !isDisabled,
                        clickOpens: !isReadonly && !isDisabled && !isIconOnly,
                        minDate: input.dataset.minDate || null,
                        maxDate: input.dataset.maxDate || null,
                        
                        // Se estiver readonly/disabled, desabilita interação
                        onReady: function(selectedDates, dateStr, instance) {
                            if (isDisabled || isReadonly) {
                                instance.calendarContainer.style.pointerEvents = 'none';
                            }
                        },
                    });

                    // Suporte ao botão de acionamento do calendário
                    const toggleBtn = input.closest('.input-group')?.querySelector('[data-datepicker-toggle]')
                        || document.querySelector(`[data-datepicker-toggle="#${CSS.escape(input.id)}"]`);

                    if (toggleBtn) {
                        toggleBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            if (!isDisabled && !isReadonly && fp) {
                                fp.toggle();
                            }
                        });
                    }
                });
            });
        </script>

    @endpush
@endonce