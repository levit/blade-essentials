{{--
/**
 * Componente de Campo de Formulário para CEP
 * 
 * Este componente renderiza um label, um input com máscara de CEP e as mensagens de erro do Laravel.
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
    data-field-cep="true"
    data-autofill="{{ $autofill ? 'true' : 'false' }}"
    data-search-url="{{ $resolvedSearchUrl }}"
    @if($containerSelector) data-container-selector="{{ $containerSelector }}" @endif
    @required($isRequired)
    @readonly($readonly)
    @disabled($disabled)
    {{ $attributes->merge(['class' => $classes]) }}
/>

{{-- Alerta/aviso amigável quando CEP não for localizado ou for inválido, permitindo continuar normalmente --}}
<div class="cep-warning-feedback small text-warning mt-1 d-none" id="{{ $id }}_warning" role="status">
    <x-misc.icone icon="bi-exclamation-triangle-fill" class="me-1 text-warning" />
    <span class="cep-warning-message"></span>
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
        <script>
            (function() {
                'use strict';

                function formatCep(value) {
                    if (!value) return '';
                    return value.toString().replace(/\D/g, '').substring(0, 8).replace(/^(\d{5})(\d)/, '$1-$2');
                }

                function findFormElement(container, selectors) {
                    for (const sel of selectors) {
                        const el = container.querySelector(sel);
                        if (el) return el;
                    }
                    return null;
                }

                function applyFieldValue(element, value) {
                    if (!element || value === undefined || value === null) return;
                    
                    const strVal = String(value);
                    if (element.tagName === 'SELECT') {
                        let matched = false;
                        for (let i = 0; i < element.options.length; i++) {
                            if (String(element.options[i].value) === strVal || (strVal && element.options[i].text.trim().toLowerCase() === strVal.trim().toLowerCase())) {
                                element.selectedIndex = i;
                                matched = true;
                                break;
                            }
                        }
                        if (!matched) {
                            element.dataset.selected = strVal;
                        }
                    } else {
                        element.value = strVal;
                    }

                    element.dispatchEvent(new Event('input', { bubbles: true }));
                    element.dispatchEvent(new Event('change', { bubbles: true }));
                }

                function fillAddressFields(cepInput, data) {
                    const containerSelector = cepInput.dataset.containerSelector;
                    const container = containerSelector 
                        ? (document.querySelector(containerSelector) || cepInput.form || cepInput.closest('form') || document)
                        : (cepInput.form || cepInput.closest('form') || cepInput.closest('.card-body') || cepInput.closest('.card') || document);

                    if (!container) return;

                    // 1. Logradouro / Endereço
                    if (data.logradouro) {
                        const logradouroEl = findFormElement(container, [
                            '[name="endereco"]',
                            '[name="logradouro"]',
                            '#endereco',
                            '#logradouro',
                            '#crud_endereco'
                        ]);
                        applyFieldValue(logradouroEl, data.logradouro);
                    }

                    // 2. Bairro
                    if (data.bairro) {
                        const bairroEl = findFormElement(container, [
                            '[name="bairro"]',
                            '#bairro',
                            '#crud_bairro'
                        ]);
                        applyFieldValue(bairroEl, data.bairro);
                    }

                    // 3. Complemento (se presente e campo existir)
                    if (data.complemento) {
                        const complementoEl = findFormElement(container, [
                            '[name="complemento"]',
                            '[name="endereco_complemento"]',
                            '#complemento',
                            '#endereco_complemento',
                            '#crud_complemento'
                        ]);
                        applyFieldValue(complementoEl, data.complemento);
                    }

                    // 4. Código IBGE (se presente e campo existir)
                    if (data.codigo_ibge) {
                        const ibgeEl = findFormElement(container, [
                            '[name="codigo_ibge"]',
                            '#codigo_ibge',
                            '#crud_codigo_ibge'
                        ]);
                        applyFieldValue(ibgeEl, data.codigo_ibge);
                    }

                    // 5. Estado (UF) e Cidade
                    const estadoEl = findFormElement(container, [
                        '[name="estado_uf"]',
                        '[name="estado"]',
                        '[name="uf"]',
                        '#estado_uf',
                        '#inputEstado',
                        '#crud_estado_uf'
                    ]);

                    const cidadeEl = findFormElement(container, [
                        '[name="cidade_id"]',
                        '[name="cidade"]',
                        '#cidade_id',
                        '#inputCidade',
                        '#crud_cidade_id'
                    ]);

                    if (data.cidade_id && cidadeEl) {
                        cidadeEl.dataset.selected = String(data.cidade_id);
                    }

                    if (data.estado_uf && estadoEl) {
                        if (estadoEl.tagName === 'SELECT') {
                            for (let i = 0; i < estadoEl.options.length; i++) {
                                if (estadoEl.options[i].value === data.estado_uf) {
                                    estadoEl.selectedIndex = i;
                                    break;
                                }
                            }
                        } else {
                            estadoEl.value = data.estado_uf;
                        }
                        
                        // Dispara change no Estado para acionar cascata de cidades caso exista
                        estadoEl.dispatchEvent(new Event('change', { bubbles: true }));
                    }

                    // Sincroniza cidade caso o select já contenha as options ou seja input de texto
                    if (cidadeEl) {
                        if (cidadeEl.tagName === 'SELECT') {
                            const targetVal = String(data.cidade_id || '');
                            const targetName = String(data.cidade_nome || '').trim().toLowerCase();
                            let found = false;

                            for (let i = 0; i < cidadeEl.options.length; i++) {
                                const optVal = String(cidadeEl.options[i].value);
                                const optText = cidadeEl.options[i].text.trim().toLowerCase();
                                if ((targetVal && optVal === targetVal) || (targetName && optText === targetName)) {
                                    cidadeEl.selectedIndex = i;
                                    found = true;
                                    break;
                                }
                            }

                            if (found) {
                                cidadeEl.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        } else {
                            applyFieldValue(cidadeEl, data.cidade_nome || data.cidade_id);
                        }
                    }
                }

                function showCepWarning(cepInput, message) {
                    clearCepWarning(cepInput);
                    cepInput.classList.add('border-warning');
                    
                    const warningEl = cepInput.parentNode ? (cepInput.parentNode.querySelector('.cep-warning-feedback') || document.getElementById(cepInput.id + '_warning')) : null;
                    if (warningEl) {
                        const msgSpan = warningEl.querySelector('.cep-warning-message') || warningEl;
                        msgSpan.textContent = message;
                        warningEl.classList.remove('d-none');
                    }
                }

                function clearCepWarning(cepInput) {
                    cepInput.classList.remove('border-warning');
                    const warningEl = cepInput.parentNode ? (cepInput.parentNode.querySelector('.cep-warning-feedback') || document.getElementById(cepInput.id + '_warning')) : null;
                    if (warningEl) {
                        warningEl.classList.add('d-none');
                        const msgSpan = warningEl.querySelector('.cep-warning-message');
                        if (msgSpan) {
                            msgSpan.textContent = '';
                        }
                    }
                }

                function isKnownInvalidCep(cleanCep) {
                    if (!cleanCep || cleanCep.length !== 8) return true;
                    // Sequências de dígitos idênticos (ex: 00000000, 11111111)
                    if (/^(\d)\1{7}$/.test(cleanCep)) return true;
                    return false;
                }

                function fetchCepData(cepInput, cleanCep) {
                    if (cepInput.dataset.autofill === 'false' || cepInput.readOnly || cepInput.disabled) {
                        return;
                    }

                    // REGRA ESTRITA DE INAÇÃO: Se o usuário não preencher o CEP (vazio), NÃO FAZER NADA!
                    if (!cleanCep || cleanCep.length !== 8) {
                        return;
                    }

                    // Se já buscou esse CEP com sucesso, evita requisição repetida
                    if (cepInput.dataset.lastCepSuccess === cleanCep) {
                        clearCepWarning(cepInput);
                        return;
                    }

                    const searchUrlTemplate = cepInput.dataset.searchUrl || '';
                    if (!searchUrlTemplate) return;

                    const searchUrl = searchUrlTemplate.replace('__CEP__', encodeURIComponent(cleanCep));

                    cepInput.classList.add('is-searching-cep');
                    cepInput.style.cursor = 'wait';
                    clearCepWarning(cepInput);

                    fetch(searchUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                // CEP não localizado ou erro na busca: avisa amigavelmente mas deixa o usuário continuar
                                showCepWarning(cepInput, 'Aviso: CEP não encontrado na base de dados. Você pode preencher o endereço manualmente.');
                                return null;
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data && (data.logradouro || data.bairro || data.cidade_id || data.estado_uf)) {
                                cepInput.dataset.lastCepSuccess = cleanCep;
                                clearCepWarning(cepInput);
                                fillAddressFields(cepInput, data);
                            } else if (data !== null) {
                                showCepWarning(cepInput, 'Aviso: CEP não localizado. Você pode preencher o endereço manualmente.');
                            }
                        })
                        .catch(error => {
                            // Em caso de falha de conexão: avisa, sem travar nem limpar outros campos
                            console.warn('Consulta de CEP não retornou dados para o CEP:', cleanCep, error.message);
                            showCepWarning(cepInput, 'Aviso: Não foi possível consultar o CEP automaticamente. Você pode preencher o endereço manualmente.');
                        })
                        .finally(() => {
                            cepInput.classList.remove('is-searching-cep');
                            cepInput.style.cursor = '';
                        });
                }

                function initCepInput(input) {
                    if (input.dataset.cepInitialized === 'true') return;
                    input.dataset.cepInitialized = 'true';

                    // Formata valor inicial se houver
                    if (input.value) {
                        input.value = formatCep(input.value);
                    }

                    // REGRA: DURANTE A DIGITAÇÃO, APENAS FORMATA A MÁSCARA.
                    // Não gera requisições nem consultas ao banco durante a digitação!
                    input.addEventListener('input', function() {
                        const previousVal = this.value;
                        const formatted = formatCep(previousVal);
                        if (previousVal !== formatted) {
                            this.value = formatted;
                        }

                        const clean = this.value.replace(/\D/g, '');
                        if (this.dataset.lastCepSuccess && this.dataset.lastCepSuccess !== clean) {
                            this.dataset.lastCepSuccess = '';
                        }
                        clearCepWarning(this);
                    });

                    // REGRA: O PREENCHIMENTO E VALIDAÇÃO SÓ OCORREM AO SAIR DO CAMPO (BLUR)
                    input.addEventListener('blur', function() {
                        const clean = (this.value || '').replace(/\D/g, '');

                        // Se o campo estiver vazio, não faz nada e limpa avisos (regra de inação)
                        if (clean.length === 0) {
                            clearCepWarning(this);
                            this.dataset.lastCepSuccess = '';
                            return;
                        }

                        // Se preencheu valor incompleto ou CEP sabidamente inválido
                        if (clean.length < 8 || isKnownInvalidCep(clean)) {
                            showCepWarning(this, 'Aviso: CEP inválido ou incompleto. Você pode preencher o endereço manualmente.');
                            return;
                        }

                        // Se for um CEP com 8 dígitos válidos, dispara a pesquisa se ainda não foi efetuada
                        if (this.dataset.lastCepSuccess === clean) {
                            clearCepWarning(this);
                            return;
                        }

                        fetchCepData(this, clean);
                    });
                }

                function setupAllCepInputs() {
                    document.querySelectorAll('input[data-field-cep="true"]').forEach(initCepInput);
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', setupAllCepInputs);
                } else {
                    setupAllCepInputs();
                }

                window.initCepFields = setupAllCepInputs;
            })();
        </script>
    @endpush
@endonce