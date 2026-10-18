@php
    $id = $id ?? ($component->id ?? ($name ? "content_{$name}" : null));
    $labelId = $labelId ?? ($component->labelId ?? ($id ? "{$id}Label" : null));
    $hasIcon = $hasIcon ?? ($component->hasIcon ?? (!empty($icon) && trim((string)$icon) !== ''));

    $color = $color ?? ($component->color ?? 'primary');
    $customHeader = (string) ($headerClass ?? ($component->headerClass ?? ''));
    $isDarkHeader = $isDarkHeader ?? ($component->isDarkHeader ?? (
        in_array($color, ['primary', 'danger', 'success', 'dark', 'info', 'secondary'], true)
        || str_contains($customHeader, 'text-white')
        || str_contains($customHeader, 'bg-primary')
        || str_contains($customHeader, 'bg-dark')
        || str_contains($customHeader, 'bg-danger')
        || str_contains($customHeader, 'bg-success')
        || str_contains($customHeader, 'bg-info')
    ));

    $defaultHeaderClass = match ($color) {
        'primary'   => 'bg-primary text-white border-bottom',
        'danger'    => 'bg-danger text-white border-bottom',
        'success'   => 'bg-success text-white border-bottom',
        'info'      => 'bg-info text-white border-bottom',
        'dark'      => 'bg-dark text-white border-bottom',
        'secondary' => 'bg-secondary text-white border-bottom',
        'warning'   => 'bg-warning text-dark border-bottom',
        'light'     => 'bg-light text-dark border-bottom',
        'white'     => 'bg-white text-dark border-bottom',
        default     => $color ? "bg-{$color} text-white border-bottom" : 'border-bottom',
    };

    $headerClasses = $headerClasses ?? ($component->headerClasses ?? trim(($customHeader ? "{$defaultHeaderClass} {$customHeader}" : $defaultHeaderClass)));
    $titleColorClass = $titleColorClass ?? ($component->titleColorClass ?? ($isDarkHeader ? 'text-white' : 'text-dark'));
    $resolvedIconColor = $resolvedIconColor ?? ($component->resolvedIconColor ?? ($iconColor ?? ($isDarkHeader ? 'white' : ($color === 'warning' ? 'dark' : 'primary'))));
    $closeButtonClass = $closeButtonClass ?? ($component->closeButtonClass ?? ($isDarkHeader ? 'btn-close-white' : ''));
    $isForm = $isForm ?? ($component->isForm ?? !empty($formAction));
    $hasAutoButtons = $hasAutoButtons ?? ($component->hasAutoButtons ?? (!empty($submitLabel) || !empty($cancelLabel)));

    $title = $title ?? ($component->title ?? '');
    $titleClass = $titleClass ?? ($component->titleClass ?? '');
    $icon = $icon ?? ($component->icon ?? null);
    $iconClass = $iconClass ?? ($component->iconClass ?? 'me-2 fs-5');
    $bodyClass = $bodyClass ?? ($component->bodyClass ?? '');
    $footerClass = $footerClass ?? ($component->footerClass ?? 'bg-light border-top');
    $contentClass = $contentClass ?? ($component->contentClass ?? 'border shadow-lg');
    $showHeader = $showHeader ?? ($component->showHeader ?? true);
    $dismissible = $dismissible ?? ($component->dismissible ?? true);
    $closeLabel = $closeLabel ?? ($component->closeLabel ?? 'Fechar');
    $rawBody = $rawBody ?? ($component->rawBody ?? false);
    $formAction = $formAction ?? ($component->formAction ?? null);
    $formMethod = $formMethod ?? ($component->formMethod ?? 'POST');
    $formEnctype = $formEnctype ?? ($component->formEnctype ?? null);
    $cancelLabel = $cancelLabel ?? ($component->cancelLabel ?? null);
    $cancelColor = $cancelColor ?? ($component->cancelColor ?? 'secondary');
    $cancelIcon = $cancelIcon ?? ($component->cancelIcon ?? null);
    $submitLabel = $submitLabel ?? ($component->submitLabel ?? null);
    $submitColor = $submitColor ?? ($component->submitColor ?? 'primary');
    $submitIcon = $submitIcon ?? ($component->submitIcon ?? null);

    $modalTitle = (isset($titleSlot) && $titleSlot->isNotEmpty()) ? $titleSlot : $title;
    $hasCustomFooter = isset($footer) && $footer->isNotEmpty();
@endphp

@if($isForm)
    <x-form.form
        :action="$formAction"
        :method="$formMethod"
        :enctype="$formEnctype"
        :id="$id"
        class="modal-content {{ $contentClass }}"
    >
        @if($showHeader)
            @if(isset($header) && $header->isNotEmpty())
                <div class="modal-header {{ $headerClasses }}">
                    {{ $header }}
                </div>
            @else
                <div class="modal-header {{ $headerClasses }}">
                    <h5 @class(['modal-title fw-bold d-flex align-items-center', $titleColorClass, $titleClass]) @if($labelId) id="{{ $labelId }}" @endif>
                        @if($hasIcon)
                            <x-misc.icone :icon="$icon" :color="$resolvedIconColor" :class="$iconClass" />
                        @endif
                        {{ $modalTitle }}
                    </h5>

                    @if(isset($actions) && $actions->isNotEmpty())
                        <div class="d-flex align-items-center gap-2 ms-auto me-2">
                            {{ $actions }}
                        </div>
                    @endif

                    @if($dismissible)
                        <x-field.button
                            type="close"
                            :class="$closeButtonClass"
                            data-bs-dismiss="modal"
                            :aria-label="$closeLabel"
                        />
                    @endif
                </div>
            @endif
        @endif

        @if($rawBody)
            {{ $slot }}
        @else
            <div @class(['modal-body', $bodyClass])>
                {{ $slot }}
            </div>
        @endif

        @if($hasCustomFooter || $hasAutoButtons)
            <div class="modal-footer {{ $footerClass }}">
                @if($hasCustomFooter)
                    {{ $footer }}
                @else
                    @if(!empty($cancelLabel))
                        <x-field.button
                            type="button"
                            :color="$cancelColor"
                            :label="$cancelLabel"
                            :icon="$cancelIcon"
                            data-bs-dismiss="modal"
                        />
                    @endif

                    @if(!empty($submitLabel))
                        <x-field.button
                            type="submit"
                            :color="$submitColor"
                            :label="$submitLabel"
                            :icon="$submitIcon"
                        />
                    @endif
                @endif
            </div>
        @endif
    </x-form.form>
@else
    <div class="modal-content {{ $contentClass }}" @if($id) id="{{ $id }}" @endif {{ $attributes ?? '' }}>
        @if($showHeader)
            @if(isset($header) && $header->isNotEmpty())
                <div class="modal-header {{ $headerClasses }}">
                    {{ $header }}
                </div>
            @else
                <div class="modal-header {{ $headerClasses }}">
                    <h5 @class(['modal-title fw-bold d-flex align-items-center', $titleColorClass, $titleClass]) @if($labelId) id="{{ $labelId }}" @endif>
                        @if($hasIcon)
                            <x-misc.icone :icon="$icon" :color="$resolvedIconColor" :class="$iconClass" />
                        @endif
                        {{ $modalTitle }}
                    </h5>

                    @if(isset($actions) && $actions->isNotEmpty())
                        <div class="d-flex align-items-center gap-2 ms-auto me-2">
                            {{ $actions }}
                        </div>
                    @endif

                    @if($dismissible)
                        <x-field.button
                            type="close"
                            :class="$closeButtonClass"
                            data-bs-dismiss="modal"
                            :aria-label="$closeLabel"
                        />
                    @endif
                </div>
            @endif
        @endif

        @if($rawBody)
            {{ $slot }}
        @else
            <div @class(['modal-body', $bodyClass])>
                {{ $slot }}
            </div>
        @endif

        @if($hasCustomFooter || $hasAutoButtons)
            <div class="modal-footer {{ $footerClass }}">
                @if($hasCustomFooter)
                    {{ $footer }}
                @else
                    @if(!empty($cancelLabel))
                        <x-field.button
                            type="button"
                            :color="$cancelColor"
                            :label="$cancelLabel"
                            :icon="$cancelIcon"
                            data-bs-dismiss="modal"
                        />
                    @endif

                    @if(!empty($submitLabel))
                        <x-field.button
                            type="button"
                            :color="$submitColor"
                            :label="$submitLabel"
                            :icon="$submitIcon"
                        />
                    @endif
                @endif
            </div>
        @endif
    </div>
@endif
