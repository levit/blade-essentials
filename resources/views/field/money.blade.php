{{--
/**
 * Componente de Campo de Formulário para Valores Monetários
 * 
 * Este componente renderiza um label, um input com máscara de dinheiro e as mensagens de erro do Laravel.
 * Suporta modo de visualização (browse) que desabilita o campo mas mantém o valor via hidden.
 *
 */
--}}

<x-misc.label :for="$id" :label="$label" :required="$isRequired" :labelClass="$labelClass" />

@if($disabled && $resolvedValue)
    <input type="hidden" name="{{ $nameField }}" value="{{ $resolvedValue }}">
@endif

<div class="input-group">
    @if($prefix)
        <span class="input-group-text">{{ $prefix }}</span>
    @endif
    
    <input
        type="text"
        id="{{ $id }}"
        name="{{ $nameField }}"
        value="{{ $resolvedValue }}"
        placeholder="0,00"
        autocomplete="off"
        data-money="true"
        data-decimals="{{ $decimals }}"
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

@once
    @push('scripts')
    {{-- Máscara de dinheiro usando vanilla JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const moneyInputs = document.querySelectorAll('[data-money="true"]');
            
            moneyInputs.forEach(function(input) {
                const decimals = parseInt(input.dataset.decimals) || 2;
                
                // Formata ao carregar se já tiver valor
                if (input.value) {
                    input.value = formatMoney(input.value, decimals);
                }
                
                // Formata enquanto digita
                input.addEventListener('input', function(e) {
                    let value = e.target.value;
                    
                    // Remove tudo que não é número
                    value = value.replace(/\D/g, '');
                    
                    // Converte para número com decimais
                    value = (parseInt(value) || 0) / Math.pow(10, decimals);
                    
                    // Formata
                    e.target.value = formatMoney(value, decimals);
                });
            });
            
            function formatMoney(value, decimals) {
                // Se já estiver formatado (com vírgula/ponto), limpa primeiro
                if (typeof value === 'string') {
                    value = value.replace(/\./g, '').replace(',', '.');
                }
                
                value = parseFloat(value) || 0;
                
                return value.toLocaleString('pt-BR', {
                    minimumFractionDigits: decimals,
                    maximumFractionDigits: decimals,
                });
            }
        });
    </script>
    @endpush
@endonce