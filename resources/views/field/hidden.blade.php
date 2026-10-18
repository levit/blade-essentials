<input
    @if($id) id="{{ $id }}" @endif
    type="hidden"
    @if($nameField ?? $name) name="{{ $nameField ?? $name }}" @endif
    value="{{ $value }}"
    {{ $attributes }}
/>