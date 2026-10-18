@php
    $class = $class ?? '';
@endphp

<div {{ $attributes->class(['card shadow-sm datatable-header-card mb-3', $class]) }}>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>