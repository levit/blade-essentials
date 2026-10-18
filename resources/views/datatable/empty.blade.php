@php
    $colspan = $colspan ?? null;
    $message = $message ?? 'Nenhum registro encontrado.';
    $icon = $icon ?? 'inbox';
@endphp

<x-datatable.row>
    <x-datatable.cell :colspan="$colspan" align="center" {{ $attributes->class(['text-center text-muted py-4']) }}>
        <x-misc.icone :icon="$icon" color="secondary" class="icon-lg mb-2" />
        <p class="mb-0">
            @if(isset($slot) && $slot->isNotEmpty())
                {{ $slot }}
            @else
                {{ $message }}
            @endif
        </p>
    </x-datatable.cell>
</x-datatable.row>