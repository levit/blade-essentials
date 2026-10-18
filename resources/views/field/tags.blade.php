{{--
/**
 * Componente de Campo de Formulário para Tags / Palavras-chave Dinâmicas
 * 
 * Permite digitação com separação imediata por vírgula (,), ponto e vírgula (;) ou Enter em badges/tags,
 * com suporte a remoção individual, colagem de texto múltiplo e sincronização em input oculto.
 */
--}}

@php
    $attributes ??= new \Illuminate\View\ComponentAttributeBag;
    $id ??= 'tags_' . \Illuminate\Support\Str::slug($nameField ?? ($name ?? 'tags'), '_') . '_id';
    $nameField = $nameField ?? ($name ?? 'tags');
    $label ??= null;
    $labelClass ??= 'small mb-1';
    $placeholder ??= 'Digite uma palavra-chave e tecle vírgula (,) ou ponto e vírgula (;)...';
    $helpText ??= 'Digite e pressione vírgula (,) ou ponto e vírgula (;) para separar as palavras-chave.';
    $maxTags ??= null;
    $isRequired ??= ($required ?? false);
    $isDisabled ??= ($disabled ?? ($readonly ?? false));

    if (!isset($tagsList)) {
        $rawVal = $value ?? null;
        if (is_array($rawVal)) {
            $tList = $rawVal;
        } elseif (is_string($rawVal) && trim($rawVal) !== '') {
            $tList = preg_split('/[,;]+/', $rawVal, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        } else {
            $tList = [];
        }
        $tagsList = array_values(array_unique(array_map('trim', $tList)));
    }
    $initialValueString ??= implode(', ', $tagsList);
    $hasError ??= false;
    $errorMessage ??= null;
@endphp

<x-misc.label :for="$id . '_input'" :label="$label" :required="$isRequired" :labelClass="$labelClass" />

<div class="tags-field-wrapper" id="{{ $id }}_wrapper" {{ $attributes }}>
    {{-- Container com aparência visual de form-control --}}
    <div class="form-control tags-input-container d-flex flex-wrap align-items-center gap-1 p-2 {{ $hasError ? 'is-invalid' : '' }}"
         id="{{ $id }}_container"
         data-tags-container="true"
         data-field-id="{{ $id }}"
         data-max-tags="{{ $maxTags ?? '' }}"
         style="min-height: calc(1.5em + 0.75rem + 2px); cursor: {{ $isDisabled ? 'default' : 'text' }}; background-color: {{ $isDisabled ? '#e9ecef' : '#fff' }};">
        
        {{-- Área onde as badges são renderizadas --}}
        <div class="tags-badges-list d-inline-flex flex-wrap align-items-center gap-1" id="{{ $id }}_badges">
            @foreach ($tagsList as $tag)
                <span class="badge bg-primary text-white d-inline-flex align-items-center gap-1 px-2 py-1 fs-7 fw-semibold shadow-none me-1 mb-1" data-tag-value="{{ $tag }}">
                    <span>{{ $tag }}</span>
                    @if (!$isDisabled)
                        <button type="button" class="btn-close btn-close-white p-0 ms-1 btn-remove-tag" style="font-size: 0.55rem; cursor: pointer;" aria-label="Remover"></button>
                    @endif
                </span>
            @endforeach
        </div>

        @if (!$isDisabled)
            {{-- Input inline sem bordas para digitação contínua --}}
            <input type="text"
                   id="{{ $id }}_input"
                   class="tags-inline-input border-0 bg-transparent flex-grow-1 p-0 m-0 text-dark"
                   style="outline: none; box-shadow: none; min-width: 160px; font-size: 0.875rem;"
                   placeholder="{{ empty($tagsList) ? $placeholder : '' }}"
                   data-placeholder="{{ $placeholder }}"
                   autocomplete="off"
                   @if($isRequired && empty($tagsList)) required @endif />
        @elseif (empty($tagsList))
            <span class="text-muted small fst-italic">Nenhuma palavra-chave informada.</span>
        @endif
    </div>

    {{-- Input hidden que envia o valor serializado para o backend --}}
    <input type="hidden"
           name="{{ $nameField }}"
           id="{{ $id }}_hidden"
           value="{{ $initialValueString }}"
           @if($isRequired) required @endif />

    @if ($helpText && !$isDisabled)
        <div class="form-text text-muted small mt-1">
            <x-misc.icone icon="bi-info-circle" class="me-1" />{{ $helpText }}
        </div>
    @endif

    @if($hasError)
        <div class="invalid-feedback d-block">{{ $errorMessage }}</div>
    @endif
</div>

@once
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        initAllTagsContainers();
    });

    if (document.readyState === 'interactive' || document.readyState === 'complete') {
        initAllTagsContainers();
    }

    function initAllTagsContainers() {
        const containers = document.querySelectorAll('[data-tags-container="true"]');
        containers.forEach(function(container) {
            if (container.dataset.initialized === 'true') return;
            container.dataset.initialized = 'true';

            const fieldId = container.dataset.fieldId;
            const input = document.getElementById(fieldId + '_input');
            const hidden = document.getElementById(fieldId + '_hidden');
            const badgesList = document.getElementById(fieldId + '_badges');
            const maxTags = container.dataset.maxTags ? parseInt(container.dataset.maxTags) : null;
            const placeholder = input ? input.dataset.placeholder : '';

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-tag')) return;
                if (input) input.focus();
            });

            badgesList.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-remove-tag');
                if (!btn) return;
                e.stopPropagation();
                const badge = btn.closest('.badge');
                if (badge) {
                    badge.remove();
                    syncHidden();
                    if (input) input.focus();
                }
            });

            function getTagsArray() {
                const badges = badgesList.querySelectorAll('.badge');
                const arr = [];
                badges.forEach(function(b) {
                    const val = b.dataset.tagValue || b.querySelector('span')?.textContent?.trim();
                    if (val) arr.push(val);
                });
                return arr;
            }

            function syncHidden() {
                const tags = getTagsArray();
                if (hidden) {
                    hidden.value = tags.join(', ');
                }
                if (input) {
                    input.placeholder = tags.length === 0 ? placeholder : '';
                }
            }

            function addTag(rawText) {
                if (!rawText) return;
                const parts = rawText.split(/[,;]+/);
                const currentTags = getTagsArray();

                parts.forEach(function(part) {
                    const clean = part.trim();
                    if (clean.length === 0) return;
                    if (maxTags !== null && currentTags.length >= maxTags) return;

                    const exists = currentTags.some(function(t) {
                        return t.toLowerCase() === clean.toLowerCase();
                    });

                    if (!exists) {
                        currentTags.push(clean);

                        const badge = document.createElement('span');
                        badge.className = 'badge bg-primary text-white d-inline-flex align-items-center gap-1 px-2 py-1 fs-7 fw-semibold shadow-none me-1 mb-1';
                        badge.dataset.tagValue = clean;

                        const span = document.createElement('span');
                        span.textContent = clean;
                        badge.appendChild(span);

                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'btn-close btn-close-white p-0 ms-1 btn-remove-tag';
                        btn.style.fontSize = '0.55rem';
                        btn.style.cursor = 'pointer';
                        btn.setAttribute('aria-label', 'Remover');
                        badge.appendChild(btn);

                        badgesList.appendChild(badge);
                    }
                });

                syncHidden();
            }

            if (input) {
                input.addEventListener('keydown', function(e) {
                    if (e.key === ',' || e.key === ';' || e.key === 'Enter') {
                        e.preventDefault();
                        addTag(this.value);
                        this.value = '';
                    } else if (e.key === 'Backspace' && this.value === '') {
                        const badges = badgesList.querySelectorAll('.badge');
                        if (badges.length > 0) {
                            badges[badges.length - 1].remove();
                            syncHidden();
                        }
                    }
                });

                input.addEventListener('input', function() {
                    if (this.value.includes(',') || this.value.includes(';')) {
                        addTag(this.value);
                        this.value = '';
                    }
                });

                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const text = (e.clipboardData || window.clipboardData).getData('text');
                    if (text) {
                        addTag(text);
                        this.value = '';
                    }
                });

                input.addEventListener('blur', function() {
                    if (this.value.trim() !== '') {
                        addTag(this.value);
                        this.value = '';
                    }
                });
            }
        });
    }
    </script>
    @endpush
@endonce

