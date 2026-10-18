@if($segmented && count($resolvedKeys) > 1)
    <span class="kbd-combo d-inline-flex align-items-center gap-1">
        @foreach($resolvedKeys as $index => $k)
            @if($index > 0)
                <span class="kbd-separator text-muted opacity-75 small fw-bold">+</span>
            @endif
            <kbd {{ $attributes->class([$kbdClasses]) }}>{{ $k }}</kbd>
        @endforeach
    </span>
@else
    <kbd {{ $attributes->class([$kbdClasses]) }}>{{ !empty($displayShortcut) ? $displayShortcut : $slot }}</kbd>
@endif

