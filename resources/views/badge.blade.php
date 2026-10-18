@props([
    'text'          => null,
    'label'         => null,
    'value'         => null,
    'status'        => null,
    'color'         => 'secondary',
    'variant'       => 'soft',
    'soft'          => true,
    'outline'       => false,
    'pill'          => false,
    'size'          => null,
    'icon'          => null,
    'iconPosition'  => 'left',
    'dot'           => false,
    'dotColor'      => null,
    'pulse'         => false,
    'href'          => null,
    'target'        => null,
    'dismissible'   => false,
    'title'         => null,
    'role'          => null,
])

<x-misc.badge
    :text="$text"
    :label="$label"
    :value="$value"
    :status="$status"
    :color="$color"
    :variant="$variant"
    :soft="$soft"
    :outline="$outline"
    :pill="$pill"
    :size="$size"
    :icon="$icon"
    :iconPosition="$iconPosition"
    :dot="$dot"
    :dotColor="$dotColor"
    :pulse="$pulse"
    :href="$href"
    :target="$target"
    :dismissible="$dismissible"
    :title="$title"
    :role="$role"
    {{ $attributes }}
>
    {{ $slot }}
</x-misc.badge>

