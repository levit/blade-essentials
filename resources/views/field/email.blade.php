{{--
/**
 * Componente de Campo de Formulário para E-mail
 *
 * Renderiza label acessível, input type="email" com validador semântico inteligente client-side,
 * diagnóstico explicativo de erros (ausência de @, múltiplos @, espaços, TLDs corrompidos como .coml e .bre),
 * sugestão interativa de correção em 1 clique ("Você quis dizer...? [Corrigir]") e integração
 * com mensagens de erro do Laravel e Bootstrap.
 *
 */
--}}

@php
    $id = $id ?? 'input_' . \Illuminate\Support\Str::slug($nameField ?? ($name ?? 'email'), '_') . '_id';
    $nameField = $nameField ?? ($name ?? 'email');
    $label = $label ?? 'E-mail';
    $labelClass = $labelClass ?? 'small mb-1';
    $resolvedValue = $resolvedValue ?? ($value ?? null);
    $isRequired = $isRequired ?? ($required ?? false);
    $readonly = $readonly ?? false;
    $disabled = $disabled ?? false;
    $hasError = $hasError ?? false;
    $errorMessage = $errorMessage ?? null;
    $classes = $classes ?? ($inputClass ?? 'form-control');
    $placeholder = $placeholder ?? 'exemplo@dominio.com';
    $enableSuggestion = $enableSuggestion ?? true;
    $strictTld = $strictTld ?? true;
    $errorMsg = $errorMessageCustom ?? 'Por favor, informe um endereço de e-mail válido.';
    $requiredMsg = 'O endereço de e-mail é obrigatório.';
@endphp

<x-misc.label :for="$id" :label="$label" :required="$isRequired" :labelClass="$labelClass" />

@if ($disabled && $resolvedValue)
    <x-field.hidden :nameField="$nameField" :value="$resolvedValue" />
@endif

<input type="email" id="{{ $id }}" name="{{ $nameField }}" value="{{ $resolvedValue }}"
    placeholder="{{ $placeholder }}" autocomplete="email" spellcheck="false" data-field-email="true"
    data-suggest="{{ $enableSuggestion ? 'true' : 'false' }}"
    data-strict-tld="{{ $strictTld ? 'true' : 'false' }}"
    data-error-msg="{{ $errorMsg }}" data-required-msg="{{ $requiredMsg }}" @required($isRequired) @readonly($readonly)
    @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }} />

<div id="{{ $id }}_feedback" class="invalid-feedback @if ($hasError) d-block @endif"
    data-feedback-for="{{ $id }}">
    @if ($hasError)
        {{ $errorMessage }}
    @elseif(isset($errors) && ($errors->has($nameField) || ($dotName && $errors->has($dotName))))
        {{ $errors->first($nameField) ?: $errors->first($dotName) }}
    @endif
</div>

@if ($enableSuggestion)
    <div id="{{ $id }}_suggestion" class="email-suggestion-box mt-1 d-none" data-suggestion-for="{{ $id }}">
        <div class="alert alert-warning py-1 px-2 mb-0 d-inline-flex align-items-center gap-1 small rounded-1 shadow-sm border-warning-subtle">
            <x-misc.icone icone="bi-lightbulb" />
            <span>Você quis dizer <strong class="suggestion-text"></strong>?</span>
            <x-field.button type="button" size="sm" color="warning" class="py-0 px-2 btn-xs suggestion-apply-btn" label="Corrigir" />
        </div>
    </div>
@endif

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const emailRegex =
                    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/;

                const dominiosTypos = {
                    'gnail.com': 'gmail.com',
                    'gamil.com': 'gmail.com',
                    'gmial.com': 'gmail.com',
                    'gmai.com': 'gmail.com',
                    'gmaill.com': 'gmail.com',
                    'gmeil.com': 'gmail.com',
                    'gmail.com.br': 'gmail.com',
                    'gmail.coml': 'gmail.com',
                    'gmail.con': 'gmail.com',
                    'gmail.comm': 'gmail.com',
                    'gmail.coom': 'gmail.com',
                    'gmail.co': 'gmail.com',
                    'hotmial.com': 'hotmail.com',
                    'hotmai.com': 'hotmail.com',
                    'hotmeil.com': 'hotmail.com',
                    'hotmil.com': 'hotmail.com',
                    'hotmal.com': 'hotmail.com',
                    'hotmail.coml': 'hotmail.com',
                    'hotmail.con': 'hotmail.com',
                    'hotmail.co': 'hotmail.com',
                    'outlok.com': 'outlook.com',
                    'outllok.com': 'outlook.com',
                    'outlock.com': 'outlook.com',
                    'otlook.com': 'outlook.com',
                    'outlook.coml': 'outlook.com',
                    'outlook.con': 'outlook.com',
                    'yaho.com': 'yahoo.com',
                    'yahooo.com': 'yahoo.com',
                    'yaho.com.br': 'yahoo.com.br',
                    'yahoo.coml': 'yahoo.com',
                    'yahoo.con': 'yahoo.com',
                    'iclou.com': 'icloud.com',
                    'icoud.com': 'icloud.com',
                    'bol.com.b': 'bol.com.br',
                    'bol.coml': 'bol.com.br',
                    'bol.con.br': 'bol.com.br',
                    'uol.com.b': 'uol.com.br',
                    'uol.coml': 'uol.com.br',
                    'uol.con.br': 'uol.com.br',
                    'live.con': 'live.com',
                    'live.coml': 'live.com',
                };

                const tldTypos = {
                    'coml': 'com',
                    'con': 'com',
                    'comm': 'com',
                    'cpm': 'com',
                    'coom': 'com',
                    'cm': 'com',
                    'comn': 'com',
                    'bre': 'com.br',
                    'brr': 'br',
                    'brl': 'com.br',
                    'ogr': 'org',
                    'orgg': 'org',
                    'ney': 'net',
                    'nett': 'net',
                    'gvo': 'gov.br',
                    'gove': 'gov.br',
                    'eddu': 'edu.br',
                };

                const provedoresPopulares = [
                    'gmail.com', 'hotmail.com', 'outlook.com', 'yahoo.com',
                    'yahoo.com.br', 'icloud.com', 'live.com', 'bol.com.br',
                    'uol.com.br', 'terra.com.br'
                ];

                function calcLevenshtein(a, b) {
                    const matrix = [];
                    for (let i = 0; i <= b.length; i++) matrix[i] = [i];
                    for (let j = 0; j <= a.length; j++) matrix[0][j] = j;
                    for (let i = 1; i <= b.length; i++) {
                        for (let j = 1; j <= a.length; j++) {
                            if (b.charAt(i - 1) === a.charAt(j - 1)) {
                                matrix[i][j] = matrix[i - 1][j - 1];
                            } else {
                                matrix[i][j] = Math.min(
                                    matrix[i - 1][j - 1] + 1,
                                    matrix[i][j - 1] + 1,
                                    matrix[i - 1][j] + 1
                                );
                            }
                        }
                    }
                    return matrix[b.length][a.length];
                }

                function obterSugestao(email) {
                    if (!email || !email.includes('@')) return null;
                    const partes = email.trim().split('@');
                    if (partes.length !== 2 || !partes[0] || !partes[1]) return null;

                    const usuario = partes[0];
                    const dominio = partes[1].toLowerCase();

                    if (dominiosTypos[dominio]) {
                        return usuario + '@' + dominiosTypos[dominio];
                    }

                    const partesDominio = dominio.split('.');
                    const tld = partesDominio[partesDominio.length - 1];
                    if (tldTypos[tld]) {
                        const sugestaoTld = tldTypos[tld];
                        partesDominio.pop();
                        return usuario + '@' + partesDominio.concat(sugestaoTld.split('.')).join('.');
                    }

                    let menorDist = 999;
                    let melhorProv = null;
                    for (const prov of provedoresPopulares) {
                        const dist = calcLevenshtein(dominio, prov);
                        if (dist <= 2 && dist > 0 && dist < menorDist) {
                            menorDist = dist;
                            melhorProv = prov;
                        }
                    }
                    if (melhorProv) {
                        return usuario + '@' + melhorProv;
                    }

                    return null;
                }

                function getFeedbackEl(input) {
                    if (!input || !input.id) return null;
                    return document.querySelector(`[data-feedback-for="${input.id}"]`) ||
                        document.getElementById(`${input.id}_feedback`) ||
                        (input.nextElementSibling && input.nextElementSibling.classList.contains('invalid-feedback') ?
                            input.nextElementSibling : null);
                }

                function getSuggestionEl(input) {
                    if (!input || !input.id) return null;
                    return document.querySelector(`[data-suggestion-for="${input.id}"]`) ||
                        document.getElementById(`${input.id}_suggestion`);
                }

                function analisarEmail(input, valor, isRequired) {
                    const customErrorMsg = input.getAttribute('data-error-msg');
                    const requiredMsg = input.getAttribute('data-required-msg') || 'O endereço de e-mail é obrigatório.';

                    if (valor === '') {
                        if (isRequired) {
                            return { valido: false, erro: requiredMsg, vazio: true };
                        }
                        return { valido: true, vazio: true };
                    }

                    if (valor.includes(' ')) {
                        return { valido: false, erro: 'O e-mail não pode conter espaços.' };
                    }

                    if (!valor.includes('@')) {
                        return { valido: false, erro: "O endereço de e-mail deve conter o caractere '@'." };
                    }

                    const partes = valor.split('@');
                    if (partes.length > 2) {
                        return { valido: false, erro: "O e-mail deve conter apenas um caractere '@'." };
                    }

                    const usuario = partes[0];
                    const dominio = (partes[1] || '').toLowerCase();

                    if (usuario === '') {
                        return { valido: false, erro: "Informe o nome de usuário antes do '@'." };
                    }

                    if (dominio === '') {
                        return { valido: false, erro: "Informe o domínio após o '@' (ex: gmail.com)." };
                    }

                    if (valor.includes('..')) {
                        return { valido: false, erro: 'O e-mail não pode conter pontos consecutivos (..).' };
                    }

                    if (dominio.startsWith('.') || dominio.endsWith('.')) {
                        return { valido: false, erro: 'O domínio não pode iniciar ou terminar com ponto.' };
                    }

                    if (!dominio.includes('.')) {
                        return { valido: false, erro: 'O domínio deve possuir uma extensão (ex: .com ou .com.br).' };
                    }

                    if (dominiosTypos[dominio]) {
                        return {
                            valido: false,
                            erro: `O domínio '${dominio}' parece incorreto. Você quis dizer '${dominiosTypos[dominio]}' ?`,
                            sugestao: usuario + '@' + dominiosTypos[dominio]
                        };
                    }

                    const partesDominio = dominio.split('.');
                    const tld = partesDominio[partesDominio.length - 1];

                    if (tldTypos[tld]) {
                        return {
                            valido: false,
                            erro: `A extensão '.${tld}' é inválida. Você quis dizer '.${tldTypos[tld]}' ?`,
                            sugestao: obterSugestao(valor)
                        };
                    }

                    if (tld.length < 2) {
                        return { valido: false, erro: `A extensão '.${tld}' é muito curta e inválida.` };
                    }

                    const passesRegex = emailRegex.test(valor);
                    const passesCheckValidity = (typeof input.checkValidity === 'function') ? input.checkValidity() : true;

                    if (!passesRegex || !passesCheckValidity) {
                        return { valido: false, erro: customErrorMsg || 'Por favor, informe um endereço de e-mail válido.' };
                    }

                    return { valido: true, sugestao: obterSugestao(valor) };
                }

                function validarEmailInput(input, forcarValidacao = false) {
                    if (!input || input.readOnly || input.disabled) {
                        return true;
                    }

                    const valor = (input.value || '').trim();
                    const isRequired = input.required || input.hasAttribute('required');
                    const feedbackEl = getFeedbackEl(input);
                    const suggestionEl = getSuggestionEl(input);
                    const allowSuggest = input.getAttribute('data-suggest') !== 'false';

                    const analise = analisarEmail(input, valor, isRequired);

                    if (analise.vazio) {
                        if (forcarValidacao && !analise.valido) {
                            input.classList.add('is-invalid');
                            input.classList.remove('is-valid');
                            if (feedbackEl) {
                                feedbackEl.textContent = analise.erro;
                                feedbackEl.classList.add('d-block');
                            }
                            if (suggestionEl) suggestionEl.classList.add('d-none');
                            return false;
                        }

                        input.classList.remove('is-invalid', 'is-valid');
                        if (feedbackEl) {
                            feedbackEl.textContent = '';
                            feedbackEl.classList.remove('d-block');
                        }
                        if (suggestionEl) suggestionEl.classList.add('d-none');
                        return !isRequired;
                    }

                    if (!analise.valido) {
                        input.classList.add('is-invalid');
                        input.classList.remove('is-valid');
                        if (feedbackEl) {
                            feedbackEl.textContent = analise.erro;
                            feedbackEl.classList.add('d-block');
                        }

                        // Sugestão interativa
                        const sugestao = analise.sugestao || (allowSuggest ? obterSugestao(valor) : null);
                        if (sugestao && suggestionEl && allowSuggest) {
                            const textEl = suggestionEl.querySelector('.suggestion-text');
                            if (textEl) textEl.textContent = sugestao;
                            suggestionEl.dataset.suggestedEmail = sugestao;
                            suggestionEl.classList.remove('d-none');
                        } else if (suggestionEl) {
                            suggestionEl.classList.add('d-none');
                        }

                        return false;
                    }

                    // Válido
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                    if (feedbackEl) {
                        feedbackEl.textContent = '';
                        feedbackEl.classList.remove('d-block');
                    }

                    const sugestao = analise.sugestao;
                    if (sugestao && suggestionEl && allowSuggest && sugestao !== valor) {
                        const textEl = suggestionEl.querySelector('.suggestion-text');
                        if (textEl) textEl.textContent = sugestao;
                        suggestionEl.dataset.suggestedEmail = sugestao;
                        suggestionEl.classList.remove('d-none');
                    } else if (suggestionEl) {
                        suggestionEl.classList.add('d-none');
                    }

                    return true;
                }

                // Ligação de cliques para aplicação da sugestão
                document.querySelectorAll('.email-suggestion-box').forEach(function(box) {
                    const btn = box.querySelector('.suggestion-apply-btn');
                    if (btn && !btn.dataset.bound) {
                        btn.dataset.bound = 'true';
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            const targetId = box.getAttribute('data-suggestion-for');
                            const input = document.getElementById(targetId);
                            const suggested = box.dataset.suggestedEmail;
                            if (input && suggested) {
                                input.value = suggested;
                                box.classList.add('d-none');
                                validarEmailInput(input, false);
                                input.dispatchEvent(new Event('input', { bubbles: true }));
                                input.dispatchEvent(new Event('change', { bubbles: true }));
                                input.focus();
                            }
                        });
                    }
                });

                const emailInputs = document.querySelectorAll('input[data-field-email="true"]');

                emailInputs.forEach(function(input) {
                    let touched = false;

                    input.addEventListener('blur', function() {
                        touched = true;
                        validarEmailInput(input, true);
                    });

                    input.addEventListener('input', function() {
                        if (touched || input.classList.contains('is-invalid')) {
                            validarEmailInput(input, false);
                        }
                    });

                    // Integração com o formulário pai no evento submit
                    const form = input.closest('form');
                    if (form && !form.dataset.emailValidatorBound) {
                        form.dataset.emailValidatorBound = 'true';
                        form.addEventListener('submit', function(e) {
                            let formValido = true;
                            let primeiroInvalido = null;

                            const inputsDoForm = form.querySelectorAll(
                                'input[data-field-email="true"]');
                            inputsDoForm.forEach(function(campo) {
                                const ok = validarEmailInput(campo, true);
                                if (!ok && formValido) {
                                    formValido = false;
                                    primeiroInvalido = campo;
                                }
                            });

                            if (!formValido) {
                                e.preventDefault();
                                e.stopPropagation();
                                if (primeiroInvalido) {
                                    primeiroInvalido.focus();
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endonce
