@php
    $id = $id ?? 'datatable_' . \Select\Components\Support\StringSupport::nameRandom();
    $headers = collect($headers ?? [])->all();
    $isEmpty = $isEmpty ?? (isset($qtdRegistros) && $qtdRegistros === 0);
    $emptyMessage = $emptyMessage ?? 'Nenhum registro encontrado.';
    $card = $card ?? true;
    $jsDatatable = $jsDatatable ?? true;
    $tbodyId = $tbodyId ?? null;
    $theadClass = $theadClass ?? null;
    $responsiveClass = $responsiveClass ?? 'd-inline-block w-100 table-responsive';
    $responsiveStyle = $responsiveStyle ?? null;
    $items = $items ?? null;
    $defaultOptions = $defaultOptions ?? [];
    $datatableOptions = $datatableOptions ?? [];
    $class = $class ?? '';
@endphp

@if($card)
<div class="card mt-2">
    <div class="card-body">
@endif
        <div class="{{ $responsiveClass }}" @if($responsiveStyle) style="{{ $responsiveStyle }}" @endif>
            <table id="{{ $id }}" {{ $attributes->class(['table table-bordered table-hover align-middle', $class]) }}>

                @if(!empty($headers))
                    <thead @if($theadClass) class="{{ $theadClass }}" @endif>
                        <tr>
                            @foreach($headers as $header)
                                @php
                                    $thClasses = array_filter([
                                        is_array($header) ? ($header['class'] ?? null) : null,
                                        is_array($header) && isset($header['align']) ? 'text-' . $header['align'] : null,
                                    ]);
                                    $thAttrs = [];
                                    if (!empty($thClasses)) {
                                        $thAttrs[] = 'class="' . e(implode(' ', $thClasses)) . '"';
                                    }
                                    if (is_array($header) && isset($header['width'])) {
                                        $thAttrs[] = 'style="width: ' . e($header['width']) . ';"';
                                    }
                                    $thAttrString = !empty($thAttrs) ? ' ' . implode(' ', $thAttrs) : '';
                                @endphp
                                <th{!! $thAttrString !!}>
                                    @if(is_array($header) && isset($header['html']))
                                        {!! $header['html'] !!}
                                    @else
                                        {{ is_array($header) ? ($header['label'] ?? '') : $header }}
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                @endif

                <tbody @if($tbodyId) id="{{ $tbodyId }}" @endif>
                    @if ($isEmpty)
                        <x-datatable.empty :colspan="count($headers) ?: 1" :message="$emptyMessage" />
                    @else
                        {{ $slot }}
                    @endif
                </tbody>

                @if(isset($footer))
                    <tfoot>
                        {{ $footer }}
                    </tfoot>
                @endif
            </table>
        </div>
        @if ($items instanceof \Illuminate\Contracts\Pagination\Paginator && $items->hasPages())
            <div class="card-footer bg-transparent border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 py-3 px-3">
                <div class="text-muted small">
                    @if ($items instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                        Mostrando <strong>{{ $items->firstItem() }}</strong> até <strong>{{ $items->lastItem() }}</strong> de <strong>{{ $items->total() }}</strong> registros
                    @else
                        Mostrando <strong>{{ $items->firstItem() }}</strong> até <strong>{{ $items->lastItem() }}</strong> registros
                    @endif
                </div>
                <div class="datatable-pagination">
                    {{ $items->links() }}
                </div>
            </div>
        @endif
@if($card)
    </div>
</div>
@endif

@if($jsDatatable)
{{-- Scripts do DataTable --}}
@once
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@10/dist/style.min.css" rel="stylesheet" crossorigin="anonymous" />
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@10" type="text/javascript" crossorigin="anonymous"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const dataTable = document.getElementById('{{ $id }}');
                if (dataTable) {
                    const options = @json($defaultOptions);
                    const mergedOptions = Object.assign({}, options, @json($datatableOptions));
                    const dt = new simpleDatatables.DataTable(dataTable, mergedOptions);

                    const renderIcons = function () {
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    };

                    renderIcons();
                    dt.on('datatable.init', renderIcons);
                    dt.on('datatable.page', renderIcons);
                    dt.on('datatable.sort', renderIcons);
                    dt.on('datatable.search', renderIcons);
                    dt.on('datatable.perpage', renderIcons);
                }

                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        </script>
    @endpush
@endonce
@endif
