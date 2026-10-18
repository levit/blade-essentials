@php
    $attributes ??= new \Illuminate\View\ComponentAttributeBag;
@endphp

@if($wrapperClass)
    <div class="{{ $wrapperClass }}">
@endif

@if($readonly)
    @if(!empty($label))
        <x-misc.label :for="$id" :label="$label" :required="$required" :labelClass="$labelClass" />
    @endif

    <input
        type="text"
        id="{{ $id }}"
        value="{{ $displayValue }}"
        readonly
        {{ $attributes->merge(['class' => $readonlyClass]) }}
    />

    @if($resolvedName)
        <input type="hidden" name="{{ $resolvedName }}" value="{{ $hiddenValue }}">
    @endif

    @if(($hasError ?? false) || (isset($errors) && $cleanName && $errors->has($cleanName)))
        <div class="invalid-feedback">
            {{ $errorMessage ?: $errors->first($cleanName) }}
        </div>
    @endif
@else
    @if(!empty($label))
        <x-misc.label :for="$id" :label="$label" :required="$required" :labelClass="$labelClass" />
    @endif

    @if($disabled && $selectedValue && $resolvedName)
        <input type="hidden" name="{{ $resolvedName }}" value="{{ $selectedValue }}">
    @endif

    <select
        id="{{ $id }}"
        @if($resolvedName) name="{{ $resolvedName }}" @endif

        data-dynamic-select="{{ ($hasUrl || $targetUpdate || $dependsOn) ? 'true' : 'false' }}"
        @if($hasUrl || $targetUpdate || $dependsOn)
            data-selected="{{ $selectedValue }}"
            data-placeholder="{{ $placeholder }}"
            data-load-on-init="{{ $loadOnInit ? 'true' : 'false' }}"
            @if($apiUrl) data-api-url="{{ $apiUrl }}" @endif
            @if($urlParam) data-url-param="{{ $urlParam }}" @endif
            @if($dependsOn) data-depends-on="{{ $dependsOn }}" @endif
            @if($targetUpdate) data-target-update="{{ $targetUpdate }}" @endif
        @endif

        @if($required) required @endif
        @disabled($isDisabled)
        @readonly($readonly)
        @if($isHtmlSize) size="{{ $size }}" @endif
        
        {{ $attributes->merge(['class' => $classes]) }}>

        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @if($hasOptions)
            @foreach($options as $optionValue => $text)
                <option value="{{ $optionValue }}" @selected((string)$value === (string)$optionValue)>
                    {{ $text }}
                </option>
            @endforeach
        @endif
        {{ $slot }}
    </select>

    @if(($hasError ?? false) || (isset($errors) && $cleanName && $errors->has($cleanName)))
        <div class="invalid-feedback">
            {{ $errorMessage ?: $errors->first($cleanName) }}
        </div>
    @endif
@endif

@if($wrapperClass)
    </div>
@endif

@if($hasUrl || $targetUpdate || $dependsOn)
    @once
        @push('scripts')
        <script>
            (function() {
                'use strict';

                /**
                 * Monta a URL da API substituindo :param, :id, :uf ou anexando ao final
                 */
                function buildApiUrl(baseUrl, paramValue, urlParamName) {
                    if (!baseUrl) return '';
                    const encoded = encodeURIComponent(paramValue);
                    
                    if (baseUrl.includes(':param')) {
                        return baseUrl.replace(':param', encoded);
                    }
                    if (urlParamName && baseUrl.includes(':' + urlParamName)) {
                        return baseUrl.replace(':' + urlParamName, encoded);
                    }
                    if (baseUrl.includes(':id')) {
                        return baseUrl.replace(':id', encoded);
                    }
                    if (baseUrl.includes(':uf')) {
                        return baseUrl.replace(':uf', encoded);
                    }
                    if (baseUrl.includes(':modulo')) {
                        return baseUrl.replace(':modulo', encoded);
                    }

                    // Se não tiver placeholder na URL, anexa ao final se for padrão REST
                    return baseUrl.endsWith('/') ? `${baseUrl}${encoded}` : `${baseUrl}/${encoded}`;
                }

                /**
                 * Limpa recursivamente os selects descendentes em cascata
                 */
                function resetCascade(select) {
                    const targetId = select.dataset.targetUpdate;
                    if (!targetId) return;

                    const targetSelect = document.getElementById(targetId);
                    if (!targetSelect) return;

                    const placeholder = targetSelect.dataset.placeholder || 'Selecione uma opção';
                    targetSelect.innerHTML = `<option value="">${placeholder}</option>`;
                    targetSelect.value = '';
                    targetSelect.dataset.selected = '';

                    // Propaga o reset recursivo para os próximos dependentes na cadeia
                    resetCascade(targetSelect);
                }

                /**
                 * Carrega as opções de um select via API
                 */
                function loadOptions(select, url, selectedValue = null) {
                    const placeholder = select.dataset.placeholder || 'Selecione uma opção';
                    
                    select.innerHTML = `<option value="">Carregando...</option>`;
                    select.disabled = true;

                    fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            if (!response.ok) throw new Error(`HTTP ${response.status}`);
                            return response.json();
                        })
                        .then(data => {
                            select.innerHTML = `<option value="">${placeholder}</option>`;
                            
                            // Popula as opções retornadas
                            Object.entries(data).forEach(([key, value]) => {
                                const isSelected = selectedValue !== null && selectedValue !== '' && String(key) === String(selectedValue);
                                const option = new Option(value, key, false, isSelected);
                                select.add(option);
                            });

                            select.disabled = false;

                            // Se houver valor selecionado após preencher e tiver dependente, notifica
                            if (select.value && select.dataset.targetUpdate) {
                                notifyDependent(select);
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao carregar opções do campo ' + (select.name || select.id) + ':', error);
                            select.innerHTML = `<option value="">${placeholder}</option>`;
                            select.disabled = false;
                        });
                }

                /**
                 * Notifica um select dependente que o valor do pai mudou
                 */
                function notifyDependent(sourceSelect) {
                    const targetId = sourceSelect.dataset.targetUpdate;
                    if (!targetId) return;

                    const targetSelect = document.getElementById(targetId);
                    if (!targetSelect) return;

                    const value = sourceSelect.value;
                    
                    if (!value) {
                        resetCascade(sourceSelect);
                        return;
                    }

                    const baseUrl = targetSelect.dataset.apiUrl;
                    if (!baseUrl) return;

                    const url = buildApiUrl(baseUrl, value, targetSelect.dataset.urlParam);
                    const targetVal = targetSelect.dataset.selected || targetSelect.value;
                    loadOptions(targetSelect, url, targetVal);
                    
                    targetSelect.dataset.selected = '';
                }

                /**
                 * Inicializa um select dinâmico
                 */
                function initDynamicSelect(select) {
                    if (select.dataset.initialized === 'true') return;
                    select.dataset.initialized = 'true';

                    const loadOnInit = select.dataset.loadOnInit === 'true';
                    const dependsOn = select.dataset.dependsOn;

                    // Se não depende de outro select e deve carregar ao iniciar via API
                    if (!dependsOn && loadOnInit && select.dataset.apiUrl && select.options.length <= 1) {
                        const apiUrl = select.dataset.apiUrl;
                        const selected = select.dataset.selected || select.value;
                        loadOptions(select, apiUrl, selected);
                    }

                    // Se depende de outro select e este select ainda não tem options carregados
                    if (dependsOn && select.dataset.apiUrl && select.options.length <= 1) {
                        const parentSelect = document.getElementById(dependsOn);
                        if (parentSelect && parentSelect.value) {
                            const url = buildApiUrl(select.dataset.apiUrl, parentSelect.value, select.dataset.urlParam);
                            const selected = select.dataset.selected || select.value;
                            loadOptions(select, url, selected);
                        }
                    }

                    // Listener para mudanças quando este select atualiza outro
                    if (select.dataset.targetUpdate) {
                        select.addEventListener('change', function() {
                            notifyDependent(this);
                        });
                    }
                }

                // Inicializa quando o DOM estiver pronto
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelectorAll('[data-dynamic-select="true"]').forEach(initDynamicSelect);
                    });
                } else {
                    document.querySelectorAll('[data-dynamic-select="true"]').forEach(initDynamicSelect);
                }

                window.initDynamicSelects = function() {
                    document.querySelectorAll('[data-dynamic-select="true"]').forEach(initDynamicSelect);
                };
            })();
        </script>
        @endpush
    @endonce
@endif