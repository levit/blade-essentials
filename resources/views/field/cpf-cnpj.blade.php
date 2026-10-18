{{--
/**
 * Componente de Campo de Formulário para CPF ou CNPJ
 * 
 * Este componente renderiza um label, um input com máscara e validação algorítmica
 * (dígitos verificadores oficiais) client-side e as mensagens de erro do Laravel.
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
    placeholder="{{ $placeholder }}"
    maxlength="{{ $maxlength }}"
    data-cpf-cnpj="true"
    data-doc-type="{{ $type }}"
    @required($isRequired)
    @readonly($readonly)
    @disabled($disabled)
    {{ $attributes->merge(['class' => $classes]) }}
/>

<div id="{{ $id }}_feedback" class="invalid-feedback" data-feedback-for="{{ $id }}">
    @if($hasError)
        {{ $errorMessage }}
    @elseif(isset($errors) && ($errors->has($nameField) || ($dotName && $errors->has($dotName))))
        {{ $errors->first($nameField) ?: $errors->first($dotName) }}
    @endif
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function validarCpf(cpf) {
                    const digits = (cpf || '').replace(/\D/g, '');
                    if (digits.length !== 11 || /^(\d)\1{10}$/.test(digits)) return false;
                    for (let t = 9; t < 11; t++) {
                        let d = 0;
                        for (let i = 0; i < t; i++) {
                            d += parseInt(digits.charAt(i), 10) * ((t + 1) - i);
                        }
                        d = ((10 * d) % 11) % 10;
                        if (parseInt(digits.charAt(t), 10) !== d) return false;
                    }
                    return true;
                }

                function validarCnpj(cnpj) {
                    const digits = (cnpj || '').replace(/\D/g, '');
                    if (digits.length !== 14 || /^(\d)\1{13}$/.test(digits)) return false;
                    const pesos1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
                    let soma1 = 0;
                    for (let i = 0; i < 12; i++) {
                        soma1 += parseInt(digits.charAt(i), 10) * pesos1[i];
                    }
                    let resto1 = soma1 % 11;
                    let d1 = resto1 < 2 ? 0 : 11 - resto1;
                    if (parseInt(digits.charAt(12), 10) !== d1) return false;

                    const pesos2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
                    let soma2 = 0;
                    for (let i = 0; i < 13; i++) {
                        soma2 += parseInt(digits.charAt(i), 10) * pesos2[i];
                    }
                    let resto2 = soma2 % 11;
                    let d2 = resto2 < 2 ? 0 : 11 - resto2;
                    if (parseInt(digits.charAt(13), 10) !== d2) return false;

                    return true;
                }

                function testarDocumento(type, value) {
                    const digits = (value || '').replace(/\D/g, '');
                    if (!digits) {
                        return { valid: true, message: '' };
                    }

                    if (type === 'cpf') {
                        if (digits.length !== 11) {
                            return { valid: false, message: 'O CPF deve conter 11 dígitos.' };
                        }
                        if (!validarCpf(digits)) {
                            return { valid: false, message: 'O CPF informado é inválido.' };
                        }
                        return { valid: true, message: '' };
                    }

                    if (type === 'cnpj') {
                        if (digits.length !== 14) {
                            return { valid: false, message: 'O CNPJ deve conter 14 dígitos.' };
                        }
                        if (!validarCnpj(digits)) {
                            return { valid: false, message: 'O CNPJ informado é inválido.' };
                        }
                        return { valid: true, message: '' };
                    }

                    // type === 'both'
                    if (digits.length <= 11) {
                        if (digits.length !== 11) {
                            return { valid: false, message: 'CPF incompleto.' };
                        }
                        if (!validarCpf(digits)) {
                            return { valid: false, message: 'O CPF informado é inválido.' };
                        }
                        return { valid: true, message: '' };
                    } else {
                        if (digits.length !== 14) {
                            return { valid: false, message: 'O CNPJ deve conter 14 dígitos.' };
                        }
                        if (!validarCnpj(digits)) {
                            return { valid: false, message: 'O CNPJ informado é inválido.' };
                        }
                        return { valid: true, message: '' };
                    }
                }

                function format(type, value) {
                    value = (value || '').replace(/\D/g, '');
                    if (type === 'cpf' || (type === 'both' && value.length <= 11)) {
                        return formatCpf(value);
                    }
                    if (type === 'cnpj' || (type === 'both' && value.length > 11)) {
                        return formatCnpj(value);
                    }
                    return value;
                }

                function formatCnpj(value) {
                    value = value.replace(/\D/g, '').substring(0, 14);
                    value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                    value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                    return value;
                }

                function formatCpf(value) {
                    value = value.replace(/\D/g, '').substring(0, 11);
                    value = value.replace(/^(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
                    value = value.replace(/\.(\d{3})(\d)/, '.$1-$2');
                    return value;
                }

                function getFeedbackElement(input) {
                    if (input.id) {
                        const el = document.getElementById(input.id + '_feedback');
                        if (el) return el;
                    }
                    return input.parentNode.querySelector('.invalid-feedback');
                }

                function marcarInvalido(input, mensagem) {
                    input.classList.add('is-invalid');
                    input.classList.remove('is-valid');
                    input.setCustomValidity(mensagem);
                    const feedback = getFeedbackElement(input);
                    if (feedback) {
                        feedback.textContent = mensagem;
                    }
                }

                function marcarValido(input) {
                    input.classList.remove('is-invalid');
                    input.setCustomValidity('');
                    const feedback = getFeedbackElement(input);
                    if (feedback) {
                        feedback.textContent = '';
                    }
                }

                function verificarInput(input, evento) {
                    if (input.disabled || input.readOnly) return true;
                    const type = input.dataset.docType || 'both';
                    const digits = (input.value || '').replace(/\D/g, '');

                    if (!digits) {
                        marcarValido(input);
                        return true;
                    }

                    const maxDigits = type === 'cpf' ? 11 : (type === 'cnpj' ? 14 : (digits.length <= 11 ? 11 : 14));

                    if (evento === 'input' && digits.length < maxDigits) {
                        input.classList.remove('is-invalid');
                        input.setCustomValidity('Documento incompleto');
                        const feedback = getFeedbackElement(input);
                        if (feedback) feedback.textContent = '';
                        return false;
                    }

                    const res = testarDocumento(type, input.value);
                    if (!res.valid) {
                        marcarInvalido(input, res.message);
                        return false;
                    } else {
                        marcarValido(input);
                        return true;
                    }
                }

                const docInputs = document.querySelectorAll('[data-cpf-cnpj="true"]');

                docInputs.forEach(function(input) {
                    if (input.value) {
                        input.value = format(input.dataset.docType, input.value);
                    }

                    input.addEventListener('input', function() {
                        this.value = format(this.dataset.docType, this.value);
                        verificarInput(this, 'input');
                    });

                    input.addEventListener('blur', function() {
                        verificarInput(this, 'blur');
                    });

                    input.addEventListener('change', function() {
                        verificarInput(this, 'change');
                    });

                    const form = input.closest('form');
                    if (form && !form.dataset.cpfCnpjListenerAttached) {
                        form.dataset.cpfCnpjListenerAttached = 'true';
                        form.addEventListener('submit', function(e) {
                            let hasInvalid = false;
                            const formInputs = form.querySelectorAll('[data-cpf-cnpj="true"]');
                            formInputs.forEach(function(fInput) {
                                if (fInput.disabled || fInput.readOnly) return;
                                const isOk = verificarInput(fInput, 'submit');
                                if (!isOk) {
                                    hasInvalid = true;
                                }
                            });

                            if (hasInvalid) {
                                e.preventDefault();
                                e.stopPropagation();
                                const firstInvalid = form.querySelector('[data-cpf-cnpj="true"].is-invalid');
                                if (firstInvalid) {
                                    firstInvalid.focus();
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endonce