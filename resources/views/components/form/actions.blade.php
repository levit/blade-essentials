@php
    $actionsComponent = $component ?? null;
    $form = $form ?? ($actionsComponent?->form ?? null);
    $buttons = ($actionsComponent instanceof \Select\Components\Components\Form\Actions) ? $actionsComponent->buttons() : ($form?->getButtons() ?? []);
    
    $routeMethod = 'POST';
    if ($form !== null) {
        try {
            // formmethod só aceita GET/POST; PUT/DELETE dependem do _method spoofing.
            $method = strtoupper($form->getMethod());
            $routeMethod = in_array($method, ['GET', 'POST'], true) ? $method : 'POST';
        } catch (\Throwable) {
            $routeMethod = 'POST';
        }
    }
@endphp

<x-box.col sizeMd="auto" {{ $attributes ?? '' }}>
    @foreach ($buttons as $button)
        @php
            $route = ($actionsComponent instanceof \Select\Components\Components\Form\Actions) ? $actionsComponent->resolveButtonRoute($button) : null;
            if ($route === null && $form !== null) {
                $action = $button->getAction();
                $baseRoute = (string) $form->getBaseRoute();
                $sessionFiltersKey = str_replace('-', '_', $baseRoute) . '_filtros';

                if ($action && (str_starts_with($action, 'http://') || str_starts_with($action, 'https://') || str_starts_with($action, '/'))) {
                    $route = $action;
                } elseif ($button->getUsePrimaryKey() && $form->getPrimaryKey()) {
                    $route = route($baseRoute.'.'.$action, $form->getPrimaryKey());
                } elseif ($action === 'list' && session()->has($sessionFiltersKey)) {
                    $route = route($baseRoute.'.list', session($sessionFiltersKey));
                } elseif (!empty($action)) {
                    $route = route($baseRoute.'.'.$action);
                }
            }
        @endphp

        <x-field.button 
            :nameButton="$button->getName()" 
            :label="$button->getLabel()" 
            :icon="$button->getIcon()"
            :color="$button->getStyle()->getClassButton()" 
            :type="$button->getType()->value" 
            :route="$route ?? ''" 
            :routeMethod="$routeMethod"
            :tooltip="$button->getTooltip() ?? ''" 
            :isDisabled="$button->isDisabled()"
            :shortcut="$button->getShortcut()"
        />
    @endforeach
</x-box.col>
