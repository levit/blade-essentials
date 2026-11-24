{{--
/**
 * Componente de Campo de Formulário para Datepicker
 * 
 * Este componente renderiza um label, um input com máscara de data brasileira (dd/mm/aaaa) e as mensagens de erro do Laravel.
 * Suporta modo de visualização (browse) que desabilita o campo mas mantém o valor via hidden.
 * Possui suporte a ícone lateral clicável para alternar a exibição do calendário (Flatpickr com fallback nativo).
 * Totalmente compatível com Content Security Policy (CSP Nonce).
 *
 */
--}}

@php
    $cspNonceValue = $cspNonce ?? (request()->attributes->has('csp_nonce') ? request()->attributes->get('csp_nonce') : (app()->bound('view') && app('view')->shared('cspNonce') ? app('view')->shared('cspNonce') : null));
    $nonceAttr = !empty($cspNonceValue) ? 'nonce="' . e($cspNonceValue) . '"' : '';
@endphp

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
            placeholder="{{ $placeholder }}"
            maxlength="10"
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

        <input
            type="date"
            class="datepicker-native-fallback"
            tabindex="-1"
            aria-hidden="true"
            style="position: absolute; opacity: 0.001; width: 1px; height: 1px; pointer-events: none; border: none; padding: 0; margin: 0;"
            @if($minDate) min="{{ $minDate }}" @endif
            @if($maxDate) max="{{ $maxDate }}" @endif
            @disabled($disabled || $readonly)
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
        placeholder="{{ $placeholder }}"
        maxlength="10"
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

    <input
        type="date"
        class="datepicker-native-fallback"
        tabindex="-1"
        aria-hidden="true"
        style="position: absolute; opacity: 0.001; width: 1px; height: 1px; pointer-events: none; border: none; padding: 0; margin: 0;"
        @if($minDate) min="{{ $minDate }}" @endif
        @if($maxDate) max="{{ $maxDate }}" @endif
        @disabled($disabled || $readonly)
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" {!! $nonceAttr !!}>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr" {!! $nonceAttr !!}></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js" {!! $nonceAttr !!}></script>
        
        <script {!! $nonceAttr !!}>
            (function() {
                // Localização em Português autônoma (garante funcionamento mesmo se o pt.js externo falhar)
                const ptLocaleInline = {
                    weekdays: {
                        shorthand: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                        longhand: ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"],
                    },
                    months: {
                        shorthand: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                        longhand: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                    },
                    rangeSeparator: " até ",
                    time_24hr: true,
                };

                // Formatação com máscara de data brasileira dd/mm/aaaa
                function formatDateMask(val) {
                    if (!val) return '';
                    // Se for formato ISO yyyy-mm-dd, converte para dd/mm/yyyy
                    if (/^\d{4}-\d{2}-\d{2}/.test(val)) {
                        const parts = val.substring(0, 10).split('-');
                        return parts[2] + '/' + parts[1] + '/' + parts[0];
                    }
                    const digits = val.replace(/\D/g, '').substring(0, 8);
                    if (digits.length <= 2) {
                        return digits;
                    }
                    if (digits.length <= 4) {
                        return digits.substring(0, 2) + '/' + digits.substring(2);
                    }
                    return digits.substring(0, 2) + '/' + digits.substring(2, 4) + '/' + digits.substring(4, 8);
                }

                function initDatepickers() {
                    const datepickers = document.querySelectorAll('[data-datepicker="true"]');
                    
                    datepickers.forEach(function(input) {
                        if (input._datepickerInitialized) return;
                        input._datepickerInitialized = true;

                        const isDisabled = input.hasAttribute('disabled');
                        const isReadonly = input.hasAttribute('readonly');
                        const isIconOnly = input.hasAttribute('data-icon-only');

                        // Elemento de fallback nativo
                        const parentContainer = input.closest('.input-group') || input.parentElement;
                        const nativePicker = parentContainer ? parentContainer.querySelector('.datepicker-native-fallback') : null;

                        // Aplica máscara ao valor inicial se existir
                        if (input.value) {
                            input.value = formatDateMask(input.value);
                            if (nativePicker && /^\d{2}\/\d{2}\/\d{4}$/.test(input.value)) {
                                const p = input.value.split('/');
                                nativePicker.value = `${p[2]}-${p[1]}-${p[0]}`;
                            }
                        }

                        // Máscara dinâmica durante a digitação
                        input.addEventListener('input', function(e) {
                            const raw = input.value;
                            const formatted = formatDateMask(raw);

                            if (raw !== formatted) {
                                const pos = input.selectionEnd || 0;
                                input.value = formatted;
                                let newPos = pos;
                                if ((pos === 2 || pos === 5) && formatted.length > pos && formatted.charAt(pos) === '/') {
                                    newPos = pos + 1;
                                }
                                input.setSelectionRange(newPos, newPos);
                            }

                            // Quando a data estiver completa (10 caracteres), sincroniza com flatpickr e fallback nativo
                            if (formatted.length === 10) {
                                const parts = formatted.split('/');
                                const day = parseInt(parts[0], 10);
                                const month = parseInt(parts[1], 10) - 1;
                                const year = parseInt(parts[2], 10);

                                if (month >= 0 && month < 12 && day >= 1 && day <= 31 && year >= 1000 && year <= 2999) {
                                    const d = new Date(year, month, day);
                                    if (d.getFullYear() === year && d.getMonth() === month && d.getDate() === day) {
                                        if (input._flatpickrInstance) {
                                            input._flatpickrInstance.setDate(d, false, 'd/m/Y');
                                        }
                                        if (nativePicker) {
                                            nativePicker.value = `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
                                        }
                                    }
                                }
                            }
                        });

                        // Facilita remoção da barra com Backspace
                        input.addEventListener('keydown', function(e) {
                            if (e.key === 'Backspace') {
                                const pos = this.selectionStart;
                                const endPos = this.selectionEnd;
                                if (pos === endPos && (pos === 3 || pos === 6) && this.value.charAt(pos - 1) === '/') {
                                    e.preventDefault();
                                    const val = this.value;
                                    const newVal = val.slice(0, pos - 2) + val.slice(pos);
                                    this.value = formatDateMask(newVal);
                                    const newPos = Math.max(0, pos - 2);
                                    this.setSelectionRange(newPos, newPos);
                                    this.dispatchEvent(new Event('input', { bubbles: true }));
                                }
                            }
                        });

                        // Sincroniza seleção vinda do fallback nativo
                        if (nativePicker) {
                            nativePicker.addEventListener('change', function() {
                                if (this.value) {
                                    const p = this.value.split('-');
                                    if (p.length === 3) {
                                        const formatted = `${p[2]}/${p[1]}/${p[0]}`;
                                        input.value = formatted;
                                        if (input._flatpickrInstance) {
                                            input._flatpickrInstance.setDate(new Date(parseInt(p[0], 10), parseInt(p[1], 10) - 1, parseInt(p[2], 10)), false, 'd/m/Y');
                                        }
                                        input.dispatchEvent(new Event('input', { bubbles: true }));
                                        input.dispatchEvent(new Event('change', { bubbles: true }));
                                    }
                                }
                            });
                        }

                        // Inicialização do Flatpickr (se disponível no ambiente)
                        let fp = null;
                        if (typeof window.flatpickr !== 'undefined') {
                            const localeResolved = (window.flatpickr.l10ns && window.flatpickr.l10ns.pt) 
                                ? window.flatpickr.l10ns.pt 
                                : ptLocaleInline;

                            fp = flatpickr(input, {
                                locale: localeResolved,
                                dateFormat: 'd/m/Y',
                                altInput: false,
                                allowInput: !isReadonly && !isDisabled,
                                clickOpens: !isReadonly && !isDisabled && !isIconOnly,
                                minDate: input.dataset.minDate || null,
                                maxDate: input.dataset.maxDate || null,
                                disableMobile: true,
                                onChange: function(selectedDates, dateStr) {
                                    if (dateStr) {
                                        input.value = dateStr;
                                        if (nativePicker) {
                                            const p = dateStr.split('/');
                                            if (p.length === 3) {
                                                nativePicker.value = `${p[2]}-${p[1]}-${p[0]}`;
                                            }
                                        }
                                        input.dispatchEvent(new Event('input', { bubbles: true }));
                                        input.dispatchEvent(new Event('change', { bubbles: true }));
                                    }
                                },
                                onReady: function(selectedDates, dateStr, instance) {
                                    if (isDisabled || isReadonly) {
                                        instance.calendarContainer.style.pointerEvents = 'none';
                                    }
                                },
                            });

                            input._flatpickrInstance = fp;
                        }

                        // Suporte ao botão de acionamento do calendário
                        const toggleBtn = input.closest('.input-group')?.querySelector('[data-datepicker-toggle]')
                            || document.querySelector(`[data-datepicker-toggle="#${CSS.escape(input.id)}"]`);

                        if (toggleBtn) {
                            toggleBtn.addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation(); // Impede fechamento imediato por bubbling de click no document

                                if (isDisabled || isReadonly) return;

                                if (input._flatpickrInstance) {
                                    input._flatpickrInstance.toggle();
                                } else if (nativePicker) {
                                    try {
                                        if (typeof nativePicker.showPicker === 'function') {
                                            nativePicker.showPicker();
                                        } else {
                                            nativePicker.focus();
                                            nativePicker.click();
                                        }
                                    } catch (err) {
                                        console.warn('Fallback datepicker não suportado:', err);
                                        input.focus();
                                    }
                                }
                            });
                        }
                    });
                }

                // Inicializa independente do estado de carregamento do DOM
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initDatepickers);
                } else {
                    initDatepickers();
                }

                // Se o script do Flatpickr carregar de forma assíncrona, re-executa a inicialização
                window.addEventListener('load', initDatepickers);
                document.addEventListener('blade-essentials:init-datepicker', initDatepickers);
            })();
        </script>
    @endpush
@endonce
