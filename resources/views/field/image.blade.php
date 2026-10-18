{{--
/**
 * Componente de Exibição / Preview de Imagem (Field Image)
 *
 * Responsável pela renderização do quadro visual para imagens, fotos e logotipos,
 * com moldura estilizada, alinhamento à esquerda, proporções ajustáveis,
 * preview reativo conectado ao componente <x-field.file> e placeholder amigável.
 */
--}}

@php
    $attributes ??= new \Illuminate\View\ComponentAttributeBag;
    $targetId ??= ($id ?? ($nameField ?? ($name ?? \Select\Components\Support\StringSupport::nameRandom())));
    $id_image ??= 'preview_img_' . $targetId;
    $id_container ??= 'preview_container_' . $targetId;
    $id_placeholder ??= 'preview_placeholder_' . $targetId;
    $title ??= 'Visualização da Imagem';
    $placeholderText ??= 'Nenhuma imagem carregada';
    $maxWidth ??= '260px';
    $maxHeight ??= '120px';
    $minHeight ??= '160px';
    $src ??= null;
    $alt ??= null;
@endphp

<div {{ $attributes->merge(['class' => 'field-image-wrapper']) }}>
    <div class="border rounded-3 p-3 bg-light text-start d-flex flex-column align-items-start justify-content-center h-100" style="min-height: {{ $minHeight }};">
        @if($title)
            <span class="text-xs text-muted text-uppercase fw-bold mb-2">{{ $title }}</span>
        @endif

        <div id="{{ $id_container }}" @class(['text-start', 'd-none' => empty($src)])>
            <img 
                id="{{ $id_image }}"
                @if($src) src="{{ $src }}" @endif
                alt="{{ $alt ?? $placeholderText }}"
                class="img-fluid rounded border bg-white p-2 shadow-sm"
                style="max-width: {{ $maxWidth }}; max-height: {{ $maxHeight }}; object-fit: contain;"
            />
        </div>

        <div id="{{ $id_placeholder }}" @class(['d-none' => !empty($src),
                                                'd-flex flex-column align-items-start text-muted py-2' => empty($src)])>
            <div class="mb-1" style="font-size: 2.5rem; opacity: 0.5;">
                <x-misc.icone icon="bi-image" color="secondary" />
            </div>
            <span class="small">{{ $placeholderText }}</span>
        </div>
    </div>
</div>

