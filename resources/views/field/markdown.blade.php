@push('styles')
    @once
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css" integrity="sha384-ZoLYv3S+AsZX+zhbN1D1+WPpc8f+DmLfxfgw+qn0Nq8wJPOYQQXEW5ZrRhcGozlG" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha384-t1nt8BQoYMLFN5p42tRAtuAAFQaCQODekUVeKKZrEnEyp4H2R0RHFz0KWpmj7i8g" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.css" integrity="sha384-nB0miv6/jRmo5UMMR1wu3Gz6NLsoTkbqJghGIsx//Rlm+ZU03BU6SQNC66uf4l5+" crossorigin="anonymous">
        <style>
            .EasyMDEContainer .CodeMirror {
                font-family: var(--bs-font-monospace, monospace);
                font-size: 0.95rem;
                min-height: 180px;
                border-bottom-left-radius: 0.35rem;
                border-bottom-right-radius: 0.35rem;
            }
            .EasyMDEContainer .editor-toolbar {
                border-top-left-radius: 0.35rem;
                border-top-right-radius: 0.35rem;
                border-color: #ced4da;
                background-color: #f8fafc;
            }
            .EasyMDEContainer .editor-toolbar button {
                color: #495057 !important;
            }
            .EasyMDEContainer .editor-toolbar button:hover,
            .EasyMDEContainer .editor-toolbar button.active {
                background-color: #e2e8f0;
                border-color: #cbd5e1;
            }
            .EasyMDEContainer .editor-toolbar button i,
            .EasyMDEContainer .editor-toolbar button i.fa {
                font-size: 14px;
                display: inline-block;
            }
            /* Previne interferência de classes do Bootstrap (.table) na barra de ferramentas */
            .EasyMDEContainer .editor-toolbar button.table,
            .EasyMDEContainer .editor-toolbar button.table-grid,
            .EasyMDEContainer .editor-toolbar .table {
                all: unset;
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                width: 30px !important;
                min-width: 30px !important;
                max-width: 30px !important;
                height: 30px !important;
                margin: 0 !important;
                padding: 0 !important;
                border: 1px solid transparent !important;
                border-radius: 3px !important;
                background: transparent !important;
                color: #495057 !important;
                box-shadow: none !important;
                vertical-align: middle !important;
                cursor: pointer !important;
                box-sizing: border-box !important;
                float: none !important;
                clear: none !important;
            }
            .EasyMDEContainer .editor-toolbar button.table:hover,
            .EasyMDEContainer .editor-toolbar button.table-grid:hover,
            .EasyMDEContainer .editor-toolbar button.table.active,
            .EasyMDEContainer .editor-toolbar button.table-grid.active {
                background-color: #e2e8f0 !important;
                border-color: #cbd5e1 !important;
            }
            /* Botões personalizados para fórmulas matemáticas */
            .EasyMDEContainer .editor-toolbar button.btn-math-sqrt:before {
                content: "√x";
                font-size: 13px;
                font-weight: bold;
                font-family: serif, "Times New Roman", Times;
                line-height: 1;
            }
            .EasyMDEContainer .editor-toolbar button.btn-math-fraction:before {
                content: "½";
                font-size: 14px;
                font-weight: bold;
                font-family: serif, "Times New Roman", Times;
                line-height: 1;
            }
            .EasyMDEContainer .editor-toolbar button.btn-math-sigma:before {
                content: "∑";
                font-size: 15px;
                font-weight: bold;
                font-family: serif, "Times New Roman", Times;
                line-height: 1;
            }
            .EasyMDEContainer .editor-toolbar button.btn-math-integral:before {
                content: "∫";
                font-size: 16px;
                font-weight: bold;
                font-family: serif, "Times New Roman", Times;
                line-height: 1;
            }
            .EasyMDEContainer .editor-toolbar button.btn-math-matrix:before {
                content: "(::)";
                font-size: 11px;
                font-weight: bold;
                font-family: monospace, sans-serif;
                line-height: 1;
                letter-spacing: -1px;
            }
            [data-bs-theme="dark"] .EasyMDEContainer .editor-toolbar {
                background-color: #1e293b;
                border-color: #334155;
            }
            [data-bs-theme="dark"] .EasyMDEContainer .editor-toolbar button,
            [data-bs-theme="dark"] .EasyMDEContainer .editor-toolbar button.table,
            [data-bs-theme="dark"] .EasyMDEContainer .editor-toolbar button.table-grid {
                color: #e2e8f0 !important;
            }
            [data-bs-theme="dark"] .EasyMDEContainer .editor-toolbar button:hover,
            [data-bs-theme="dark"] .EasyMDEContainer .editor-toolbar button.active,
            [data-bs-theme="dark"] .EasyMDEContainer .editor-toolbar button.table:hover,
            [data-bs-theme="dark"] .EasyMDEContainer .editor-toolbar button.table-grid:hover,
            [data-bs-theme="dark"] .EasyMDEContainer .editor-toolbar button.table.active,
            [data-bs-theme="dark"] .EasyMDEContainer .editor-toolbar button.table-grid.active {
                background-color: #334155 !important;
                border-color: #475569 !important;
            }
            [data-bs-theme="dark"] .EasyMDEContainer .CodeMirror {
                background-color: #0f172a;
                border-color: #334155;
                color: #f8fafc;
            }
            [data-bs-theme="dark"] .EasyMDEContainer .editor-preview-full,
            [data-bs-theme="dark"] .EasyMDEContainer .editor-preview-side {
                background-color: #0f172a;
                color: #f8fafc;
                border-color: #334155;
            }
            .EasyMDEContainer .editor-preview-active {
                border-radius: 0.35rem;
                padding: 1rem 1.25rem;
            }
            [data-bs-theme="dark"] .katex {
                color: #f8fafc;
            }
        </style>
    @endonce
@endpush

@php
    $attributes ??= new \Illuminate\View\ComponentAttributeBag;
    $id ??= 'markdown_' . \Illuminate\Support\Str::slug($nameField ?? ($name ?? 'markdown'), '_') . '_id';
    $nameField = $nameField ?? ($name ?? 'markdown');
    $label ??= '';
    $labelClass ??= 'small mb-1 fw-bold';
    $rows ??= 12;
    $placeholder ??= 'Digite o texto em markdown ou insira fórmulas matemáticas...';
    $resolvedValue ??= ($value ?? '');
    $isRequired ??= ($required ?? false);
    $disabled ??= false;
    $readonly ??= false;
    $isReadOnlyOrDisabled ??= ($readonly || $disabled);
    $showMdBadge ??= false;
    $showMathBadge ??= false;
    $helpText ??= '';
    $hasError ??= false;
    $errorMessage ??= null;
@endphp

<div class="mb-3">
    @if ($label)
        <div class="d-flex justify-content-between align-items-center mb-1">
            <x-misc.label :for="$id" :label="$label" :required="$isRequired" :labelClass="$labelClass" class="mb-0" />
            <div class="d-flex gap-1">
                @if ($showMdBadge)
                    <x-misc.badge color="light" class="text-secondary border font-monospace small">
                        <x-misc.icone icon="bi-markdown" class="me-1" />Markdown
                    </x-misc.badge>
                @endif
                @if ($showMathBadge)
                    <x-misc.badge color="light" class="text-primary border font-monospace small" title="Fórmulas matemáticas com KaTeX / LaTeX">
                        <x-misc.icone icon="bi-calculator" class="me-1" />LaTeX/KaTeX
                    </x-misc.badge>
                @endif
            </div>
        </div>
    @endif

    <textarea
        id="{{ $id }}"
        name="{{ $nameField }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @disabled($disabled)
        @readonly($readonly)
        class="form-control {{ $hasError ? 'is-invalid' : '' }}"
        {{ $attributes }}
    >{{ $resolvedValue }}</textarea>

    @if ($helpText && !$isReadOnlyOrDisabled)
        <div class="form-text text-muted small mt-1">
            <x-misc.icone icon="bi-info-circle" class="me-1" />{{ $helpText }}
        </div>
    @endif

    @if($hasError)
        <div class="invalid-feedback d-block">{{ $errorMessage }}</div>
    @endif
</div>

@push('scripts')
    @once
        <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js" integrity="sha384-mTM6vzy+/UiHrMBClNGViM9qEv0/26iCGqpJKhSzdnjrxbKjO3vkT62ujXQ8B5iv" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.js" integrity="sha384-7zkQWkzuo3B5mTepMUcHkMB5jZaolc2xDwL6VFqjFALcbeS9Ggm/Yr2r3Dy4lfFg" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/contrib/auto-render.min.js" integrity="sha384-43gviWU0YVjaDtb/GhzOouOXtZMP/7XUzwPTstBeZFe/+rCMvRwr4yROQP43s0Xk" crossorigin="anonymous"></script>
    @endonce
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const textarea = document.getElementById('{{ $id }}');
            if (!textarea || textarea.dataset.easymdeInitialized) {
                return;
            }
            textarea.dataset.easymdeInitialized = 'true';

            const isReadOnly = {{ $isReadOnlyOrDisabled ? 'true' : 'false' }};

            // Função para envolver seleção ou inserir fórmula
            function insertMathSnippet(editor, prefix, defaultCode, suffix) {
                const cm = editor.codemirror;
                const selection = cm.getSelection();
                if (selection) {
                    cm.replaceSelection(prefix + selection + suffix);
                } else {
                    cm.replaceSelection(prefix + defaultCode + suffix);
                }
                cm.focus();
            }

            const toolbarConfig = isReadOnly ? false : [
                'bold', 'italic', 'heading', '|',
                'quote', 'unordered-list', 'ordered-list', '|',
                'link', 'image',
                {
                    name: 'table-grid',
                    action: EasyMDE.drawTable,
                    className: 'fa fa-table',
                    title: 'Inserir Tabela',
                },
                'horizontal-rule', '|',
                {
                    name: 'math-block',
                    action: function (editor) {
                        insertMathSnippet(editor, "$$\n", "x = \\frac{-b \\pm \\sqrt{b^2 - 4ac}}{2a}", "\n$$");
                    },
                    className: 'btn-math-sqrt',
                    title: 'Fórmula em Bloco / Equação ($$ ... $$)',
                },
                {
                    name: 'math-fraction',
                    action: function (editor) {
                        insertMathSnippet(editor, "\\frac{", "a", "}{b}");
                    },
                    className: 'btn-math-fraction',
                    title: 'Fração Matemática (\\frac{a}{b})',
                },
                {
                    name: 'math-sigma',
                    action: function (editor) {
                        insertMathSnippet(editor, "$$\n", "\\sum_{i=1}^{n} x_i", "\n$$");
                    },
                    className: 'btn-math-sigma',
                    title: 'Somatório / Expressão (\\sum)',
                },
                {
                    name: 'math-integral',
                    action: function (editor) {
                        insertMathSnippet(editor, "$$\n", "\\int_{a}^{b} f(x) \\, dx = F(b) - F(a)", "\n$$");
                    },
                    className: 'btn-math-integral',
                    title: 'Integral Definida (\\int_{a}^{b} f(x) dx)',
                },
                {
                    name: 'math-matrix',
                    action: function (editor) {
                        insertMathSnippet(editor, "$$\n", "A = \\begin{pmatrix} 1 & 2 \\\\ 3 & 4 \\end{pmatrix}", "\n$$");
                    },
                    className: 'btn-math-matrix',
                    title: 'Matriz (\\begin{pmatrix} ... \\end{pmatrix})',
                },
                {
                    name: 'math-inline',
                    action: function (editor) {
                        insertMathSnippet(editor, "$", "f(x) = y", "$");
                    },
                    className: 'fa fa-superscript',
                    title: 'Fórmula em Linha ($ ... $)',
                },
                '|',
                'preview', 'side-by-side', 'fullscreen', '|',
                'guide'
            ];

            const easyMDE = new EasyMDE({
                element: textarea,
                spellChecker: false,
                autosave: { enabled: false },
                placeholder: '{{ $placeholder }}',
                status: ["lines", "words", "cursor"],
                toolbar: toolbarConfig,
                tabSize: 4,
                autoDownloadFontAwesome: true,
                readOnly: isReadOnly,
                minHeight: '340px',
                renderingConfig: {
                    singleLineBreaks: false,
                    codeSyntaxHighlighting: true,
                },
                previewRender: function (plainText, preview) {
                    // 1. Extrai blocos ($$...$$ e \[...\]) e fórmulas inline ($...$ e \(...\)) preservando fórmulas intactas
                    const mathBlocks = [];
                    let placeholderCounter = 0;

                    // Protege fórmulas em bloco $$...$$ e \[...\]
                    let processedText = plainText.replace(/(\$\$[\s\S]*?\$\$|\\\[[\s\S]*?\\\])/g, function (match) {
                        const id = '@@MATH_BLOCK_' + (placeholderCounter++) + '@@';
                        mathBlocks.push({ id: id, math: match, isBlock: true });
                        return id;
                    });

                    // Protege fórmulas inline $...$ e \(...\)
                    processedText = processedText.replace(/(\$(?!\$)((?:\\.|[^$\\\n])+?)\$|\\\(((?:\\.|[^\\])+?)\\\))/g, function (match) {
                        const id = '@@MATH_INLINE_' + (placeholderCounter++) + '@@';
                        mathBlocks.push({ id: id, math: match, isBlock: false });
                        return id;
                    });

                    // 2. Compila markdown puro sem corromper backslashes \\ de matrizes ou underscores _ de integrais
                    let html = this.parent.markdown(processedText);

                    // 3. Renderiza os blocos matemáticos com KaTeX de forma protegida
                    mathBlocks.forEach(function (item) {
                        let renderedMath = null;
                        if (window.katex && window.katex.renderToString) {
                            try {
                                let formula = item.math;
                                if (item.isBlock) {
                                    formula = formula.replace(/^\$\$|^\\\[/, '').replace(/\$\$$|\\\]$/, '').trim();
                                    renderedMath = '<div class="katex-display my-2 text-center">' + window.katex.renderToString(formula, { displayMode: true, throwOnError: false }) + '</div>';
                                } else {
                                    formula = formula.replace(/^\$|^\\\(|\$$|\\\)$/g, '').trim();
                                    renderedMath = window.katex.renderToString(formula, { displayMode: false, throwOnError: false });
                                }
                            } catch (e) {
                                renderedMath = item.math;
                            }
                        }
                        const replacement = renderedMath || item.math;
                        html = html.replace(item.id, function () { return replacement; });
                    });

                    // 4. Executa auto-render caso reste alguma expressão no DOM
                    setTimeout(function () {
                        if (window.renderMathInElement && preview) {
                            window.renderMathInElement(preview, {
                                delimiters: [
                                    { left: '$$', right: '$$', display: true },
                                    { left: '$', right: '$', display: false },
                                    { left: '\\(', right: '\\)', display: false },
                                    { left: '\\[', right: '\\]', display: true }
                                ],
                                throwOnError: false
                            });
                        }
                    }, 40);

                    return html;
                },
            });

            // Expõe a instância do EasyMDE no elemento textarea para integrações externas
            textarea.easyMDE = easyMDE;

            // Se for modo somente leitura, ativa o preview renderizado automaticamente com as fórmulas
            if (isReadOnly) {
                setTimeout(function () {
                    if (!easyMDE.isPreviewActive()) {
                        easyMDE.togglePreview();
                    }
                }, 100);
            }

            // Sincroniza alterações em tempo real para o textarea
            easyMDE.codemirror.on('change', function () {
                textarea.value = easyMDE.value();
            });

            // Garante que o form envie o valor atualizado no submit
            if (textarea.form) {
                textarea.form.addEventListener('submit', function () {
                    textarea.value = easyMDE.value();
                });
            }
        });
    </script>
@endpush
