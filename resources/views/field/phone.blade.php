{{--
/**
 * Componente de Campo de Formulário para Telefone
 * 
 * Este componente renderiza um label, um input com máscara de telefone e as mensagens de erro do Laravel.
 * Suporta modo de visualização (browse) que desabilita o campo mas mantém o valor via hidden.
 *
 */
--}}

<x-misc.label :for="$id" :label="$label" :required="$isRequired" :labelClass="$labelClass" />

@if($disabled && $resolvedValue)
    <input type="hidden" name="{{ $nameField }}" value="{{ $resolvedValue }}">
@endif

<input
    type="tel"
    id="{{ $id }}"
    name="{{ $nameField }}"
    value="{{ $resolvedValue }}"
    placeholder="{{ $placeholder }}"
    autocomplete="off"
    maxlength="{{ $maxlength }}"
    data-phone="true"
    data-phone-type="{{ $type }}"
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
    @push('scripts')
    {{-- Máscara de telefone brasileiro --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInputs = document.querySelectorAll('[data-phone="true"]');
            
            phoneInputs.forEach(function(input) {
                const type = input.dataset.phoneType || 'both';
                
                // Formata ao carregar se já tiver valor
                if (input.value) {
                    input.value = formatPhone(input.value, type);
                }
                
                // Formata enquanto digita
                input.addEventListener('input', function(e) {
                    let value = e.target.value;
                    e.target.value = formatPhone(value, type);
                });
            });
            
            function formatPhone(value, type) {
                // Remove tudo que não é número
                value = value.replace(/\D/g, '');
                
                // Limita o tamanho baseado no tipo
                if (type === 'phone') {
                    value = value.substring(0, 10); // Fixo: (00) 0000-0000
                } else if (type === 'mobile') {
                    value = value.substring(0, 11); // Celular: (00) 00000-0000
                } else {
                    value = value.substring(0, 11); // Both: permite até 11 dígitos
                }
                
                // Aplica a máscara baseado no tamanho
                if (value.length <= 2) {
                    // Apenas DDD
                    value = value.replace(/^(\d{0,2})/, '($1');
                } else if (value.length <= 6) {
                    // DDD + primeiros dígitos
                    value = value.replace(/^(\d{2})(\d{0,4})/, '($1) $2');
                } else if (value.length <= 10) {
                    // Formato fixo: (00) 0000-0000
                    value = value.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                } else {
                    // Formato celular: (00) 00000-0000
                    value = value.replace(/^(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
                }
                
                return value;
            }
        });
    </script>
    @endpush
@endonce