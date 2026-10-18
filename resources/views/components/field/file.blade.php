{{--
/**
 * Componente de Campo de Arquivo (File) - Princípio de Responsabilidade Única (SOLID)
 *
 * Responsável estritamente pelo controle de envio de arquivos:
 * - Botão estilizado de seleção e input file
 * - Validação client-side de tamanho (maxSize) e formatos (accept)
 * - Botão de remoção e sinalização de exclusão (nameField_remove)
 * - Sincronização reativa com o componente externo de visualização (x-field.image ou tag img)
 *
 * A responsabilidade de renderizar molduras, placeholders ou imagens cabe exclusivamente
 * ao componente <x-field.image>.
 */
--}}

@php
    $attributes ??= new \Illuminate\View\ComponentAttributeBag;
    $id ??= 'file_' . \Illuminate\Support\Str::slug($nameField ?? ($name ?? 'file'), '_') . '_id';
    $nameField = $nameField ?? ($name ?? 'file');
    $label ??= '';
    $labelClass ??= 'small mb-1 fw-semibold text-dark';
    $isRequired ??= ($required ?? false);
    $isDisabled ??= ($disabled ?? ($readonly ?? false));
    $accept ??= 'image/png,image/jpeg,image/webp,image/svg+xml';
    $visibleButtons ??= true;
    $allowRemove ??= true;
    $hasFile ??= false;
    $showFileName ??= true;
    $buttonIcon ??= 'upload';
    $buttonText ??= 'Selecionar arquivo';
    $removeIcon ??= 'trash';
    $removeText ??= 'Remover arquivo';
    $maxSize ??= '5';
    $targetPreviewId ??= $nameField;
    $hasError ??= false;
    $errorMessage ??= null;
@endphp

<div {{ $attributes->merge(['class' => 'field-file-wrapper']) }}>
    <x-misc.label :for="$id" :label="$label" :required="$isRequired" :labelClass="$labelClass" />

    <div class="file-input-wrapper mb-2">
        <input
            type="file"
            id="{{ $id }}"
            name="{{ $nameField }}"
            accept="{{ $accept }}"
            class="d-none {{ ($visibleButtons && !$isDisabled) ? '' : 'd-none' }}"
            @required($required)
            @disabled($isDisabled)
            data-max-size="{{ $maxSize }}"
            onchange="handleUnifiedFileChange_{{ $id }}(this);"
        />

        <div class="d-flex flex-wrap align-items-center gap-2">
            <label for="{{ $id }}"
                class="btn btn-sm btn-primary mb-0 shadow-sm {{ $isDisabled ? 'disabled' : '' }} {{ ($visibleButtons && !$isDisabled) ? '' : 'd-none' }}">
                <x-misc.icone :icon="$buttonIcon" color="secondary"/> {{ $buttonText }}
            </label>

            @if($allowRemove && $hasFile && !$isDisabled)
                <button
                    type="button"
                    id="btn_remove_{{ $id }}"
                    class="btn btn-sm btn-outline-danger mb-0 {{ $visibleButtons ? '' : 'd-none' }}"
                    onclick="handleUnifiedFileRemove_{{ $id }}()">
                    <x-misc.icone :icon="$removeIcon" color="danger" /> {{ $removeText }}
                </button>
            @endif

            @if($allowRemove)
                <input type="hidden" name="{{ $nameField }}_remove" id="{{ $id }}_remove" value="0">
            @endif
        </div>

        @if($showFileName)
            <div id="filename_{{ $id }}" class="small text-primary fw-semibold mt-2" style="display: none;"></div>
        @endif
    </div>

    {{-- INFORMAÇÃO DE TAMANHO MÁXIMO (SE NÃO HOUVER INSTRUÇÃO EXTERNA) --}}
    @if($maxSize && $visibleButtons && !$isDisabled)
        <div class="form-text small text-muted mt-1">
            Tamanho máximo: {{ $maxSize }} MB
        </div>
    @endif

    {{-- ERRO DE VALIDAÇÃO --}}
    @if($hasError)
        <div class="invalid-feedback d-block mt-1">
            {{ $errorMessage }}
        </div>
    @endif
</div>

@push('scripts')
    <script>
        function handleUnifiedFileChange_{{ $id }}(input) {
            const span = document.getElementById('filename_{{ $id }}');
            const targetId = "{{ $targetPreviewId }}";
            const removeInput = document.getElementById('{{ $id }}_remove');

            if (input.files && input.files.length > 0) {
                const file = input.files[0];

                // Validação client-side de tipo de arquivo
                const accept = input.accept;
                if (accept) {
                    const acceptedTypes = accept.split(',').map(t => t.trim());
                    const isValid = acceptedTypes.some(type => {
                        if (type.startsWith('.')) {
                            return file.name.toLowerCase().endsWith(type.toLowerCase());
                        }
                        return file.type.match(type.replace('*', '.*'));
                    });

                    if (!isValid) {
                        alert('Tipo de arquivo não permitido.');
                        input.value = '';
                        if (span) {
                            span.textContent = '';
                            span.style.display = 'none';
                        }
                        return;
                    }
                }

                // Validação client-side de tamanho em MB
                const maxSize = parseFloat(input.dataset.maxSize || 0);
                if (maxSize > 0) {
                    const fileSizeMB = file.size / (1024 * 1024);
                    if (fileSizeMB > maxSize) {
                        alert(`Arquivo muito grande. Tamanho máximo: ${maxSize} MB`);
                        input.value = '';
                        if (span) {
                            span.textContent = '';
                            span.style.display = 'none';
                        }
                        return;
                    }
                }

                // Exibe nome e tamanho do arquivo selecionado
                if (span) {
                    span.innerHTML = '<i class="bi bi-file-earmark-check me-1"></i><strong>Arquivo selecionado:</strong> ' + file.name + ' <span class="text-muted">(' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)</span>';
                    span.style.display = 'block';
                }

                // Se houver sinalização de remoção pendente, reseta para 0
                if (removeInput) {
                    removeInput.value = '0';
                }

                // Sincronização reativa com o componente de visualização x-field.image
                if (file.type.startsWith('image/') && targetId) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewImg = document.getElementById('preview_img_' + targetId) || document.getElementById(targetId);
                        const previewContainer = document.getElementById('preview_container_' + targetId);
                        const previewPlaceholder = document.getElementById('preview_placeholder_' + targetId);

                        if (previewImg) {
                            previewImg.src = e.target.result;
                        }
                        if (previewContainer) {
                            previewContainer.classList.remove('d-none');
                            previewContainer.style.display = 'block';
                        }
                        if (previewPlaceholder) {
                            previewPlaceholder.classList.add('d-none');
                        }
                    };
                    reader.readAsDataURL(file);
                }
            } else {
                if (span) {
                    span.textContent = '';
                    span.style.display = 'none';
                }
            }
        }

        @if($allowRemove)
        function handleUnifiedFileRemove_{{ $id }}() {
            const targetId = "{{ $targetPreviewId }}";
            const removeInput = document.getElementById('{{ $id }}_remove');
            const fileInput = document.getElementById('{{ $id }}');
            const span = document.getElementById('filename_{{ $id }}');
            const btnRemove = document.getElementById('btn_remove_{{ $id }}');

            // Sincronização com o componente de visualização x-field.image
            if (targetId) {
                const previewContainer = document.getElementById('preview_container_' + targetId);
                const previewPlaceholder = document.getElementById('preview_placeholder_' + targetId);
                const previewImg = document.getElementById('preview_img_' + targetId) || document.getElementById(targetId);

                if (previewContainer) {
                    previewContainer.classList.add('d-none');
                    previewContainer.style.display = 'none';
                }
                if (previewImg) {
                    previewImg.src = '';
                }
                if (previewPlaceholder) {
                    previewPlaceholder.classList.remove('d-none');
                }
            }

            // Sinalização de exclusão e reset do controle
            if (removeInput) {
                removeInput.value = '1';
            }
            if (fileInput) {
                fileInput.value = '';
            }
            if (span) {
                span.textContent = '';
                span.style.display = 'none';
            }
            if (btnRemove) {
                btnRemove.classList.add('d-none');
            }
        }
        @endif
    </script>
@endpush