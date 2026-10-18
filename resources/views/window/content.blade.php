@php
    $class = $class ?? ($component->class ?? '');
@endphp

<div @class(['container-fluid px-4', $class => !empty($class)]) {{ $attributes ?? '' }}>
    <div class="row gx-4">
        <div class="col-lg-12">
            {{ $slot }}
        </div>
    </div>
</div>
