@php
    $align = $align ?? 'left';
    $alignmentClass = $alignmentClass ?? match ($align) {
        'center', 'middle' => 'text-center',
        'right', 'end' => 'text-end',
        default => 'text-start',
    };
    $class = $class ?? '';
    $colspan = $colspan ?? null;
    $rowspan = $rowspan ?? null;
    $sort_data = $sort_data ?? ($sortData ?? null);

    $cellAttributes = [];
    if ($colspan !== null) {
        $cellAttributes['colspan'] = $colspan;
    }
    if ($rowspan !== null) {
        $cellAttributes['rowspan'] = $rowspan;
    }
    if ($sort_data !== null) {
        $cellAttributes['data-sort'] = $sort_data;
    }
@endphp

<td {{ $attributes->except(['sort_data', 'sortData'])->class([$alignmentClass, $class])->merge($cellAttributes) }}>
    {{ $slot }}
</td>