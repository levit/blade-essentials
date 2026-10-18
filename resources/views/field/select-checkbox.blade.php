@php
    $attributes ??= new \Illuminate\View\ComponentAttributeBag;
@endphp

<div class="dropdown select-checkbox-dropdown"
     id="dropdown_wrapper_{{ $id }}"
     data-select-checkbox="true"
     data-placeholder="{{ $placeholder }}"
     data-all-selected-text="{{ $allSelectedText }}"
     data-max-display="{{ $maxDisplay }}"
     data-total-options="{{ $totalOptions }}"
>
    @if($label)
        <x-misc.label :for="$id" :label="$label" :required="$required" :labelClass="$labelClass" />
    @endif

    <button
        type="button"
        class="{{ $buttonClasses }}"
        id="{{ $id }}"
        data-bs-toggle="dropdown"
        data-bs-auto-close="outside"
        aria-expanded="false"
        @disabled($isDisabled)
    >
        <span class="dropdown-selected-text text-truncate pe-2">{{ $initialText }}</span>
    </button>

    <div class="dropdown-menu shadow p-2 w-100 animated--fade-in {{ $menuClass }}"
         aria-labelledby="{{ $id }}"
         style="min-width: 220px; max-height: {{ $menuMaxHeight }}; overflow-y: auto;">

        @if($showActions && $totalOptions > 1 && !$isDisabled)
            <div class="d-flex justify-content-between align-items-center px-2 py-1 mb-1 border-bottom small">
                <x-misc.link href="javascript:void(0);" class="select-all-action small fw-semibold">
                    {{ $selectAllLabel }}
                </x-misc.link>
                <x-misc.link href="javascript:void(0);" class="text-muted clear-all-action small">
                    {{ $clearLabel }}
                </x-misc.link>
            </div>
        @endif

        @if(($searchable ?? ($totalOptions > 6)) && $totalOptions > 4 && !$isDisabled)
            <div class="px-2 py-1 mb-1 border-bottom">
                <input type="text" class="form-control form-control-sm select-checkbox-search" placeholder="{{ $searchPlaceholder }}" aria-label="{{ $searchPlaceholder }}" autocomplete="off">
            </div>
        @endif

        @foreach($options as $optionValue => $optionLabel)
            @php
                $optValStr = (string) ($optionValue instanceof \BackedEnum ? $optionValue->value : $optionValue);
                $isItemChecked = in_array($optValStr, $selectedStrings, true);
                $itemId = "{$id}_opt_{$loop->index}";
            @endphp
            <div class="dropdown-item-text py-1 px-2 rounded hover-bg-light">
                <x-field.checkbox
                    :id="$itemId"
                    :nameField="$arrayNameField"
                    :label="$optionLabel"
                    :value="$optValStr"
                    :checked="$isItemChecked"
                    :disabled="$isDisabled"
                    checkboxClass="form-check-input select-checkbox-item"
                    wrapperClass="form-check mb-0"
                    labelClass="form-check-label w-100 cursor-pointer small"
                />
            </div>
        @endforeach
    </div>

    @if($isDisabled && $selectedCount > 0)
        @foreach($selectedStrings as $savedVal)
            <input type="hidden" name="{{ $arrayNameField }}" value="{{ $savedVal }}">
        @endforeach
    @endif

    @if(($hasError ?? false) || (isset($errors) && ($errors->has($cleanName) || $errors->has($arrayNameField))))
        <div class="invalid-feedback d-block">
            {{ $errorMessage ?: ($errors->first($cleanName) ?: $errors->first($arrayNameField)) }}
        </div>
    @endif
</div>

@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function updateSelectCheckboxDropdown(wrapper) {
            const placeholder = wrapper.getAttribute('data-placeholder') || 'Selecione...';
            const allText = wrapper.getAttribute('data-all-selected-text') || 'Todos selecionados';
            const maxDisplay = parseInt(wrapper.getAttribute('data-max-display') || '2', 10);
            const totalOptions = parseInt(wrapper.getAttribute('data-total-options') || '0', 10);

            const textSpan = wrapper.querySelector('.dropdown-selected-text');
            const checkboxes = wrapper.querySelectorAll('.select-checkbox-item');
            if (!textSpan || checkboxes.length === 0) return;

            const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
            const count = checkedBoxes.length;

            if (count === 0) {
                textSpan.textContent = placeholder;
            } else if (count === totalOptions && totalOptions > maxDisplay) {
                textSpan.textContent = allText;
            } else if (count <= maxDisplay) {
                const labels = checkedBoxes.map(cb => {
                    const labelElem = cb.closest('.form-check')?.querySelector('.form-check-label');
                    return labelElem ? labelElem.textContent.trim() : cb.value;
                });
                textSpan.textContent = labels.join(', ');
            } else {
                textSpan.textContent = `${count} selecionados`;
            }
        }

        // Listener delegado para alterações em checkboxes do componente
        document.addEventListener('change', function (e) {
            if (e.target && e.target.classList.contains('select-checkbox-item')) {
                const wrapper = e.target.closest('[data-select-checkbox="true"]');
                if (wrapper) {
                    updateSelectCheckboxDropdown(wrapper);
                }
            }
        });

        // Ações Selecionar Todos / Limpar
        document.addEventListener('click', function (e) {
            const selectAllBtn = e.target.closest('.select-all-action');
            if (selectAllBtn) {
                e.preventDefault();
                const wrapper = selectAllBtn.closest('[data-select-checkbox="true"]');
                if (wrapper) {
                    const checkboxes = wrapper.querySelectorAll('.select-checkbox-item');
                    checkboxes.forEach(cb => { cb.checked = true; });
                    updateSelectCheckboxDropdown(wrapper);
                }
                return;
            }

            const clearAllBtn = e.target.closest('.clear-all-action');
            if (clearAllBtn) {
                e.preventDefault();
                const wrapper = clearAllBtn.closest('[data-select-checkbox="true"]');
                if (wrapper) {
                    const checkboxes = wrapper.querySelectorAll('.select-checkbox-item');
                    checkboxes.forEach(cb => { cb.checked = false; });
                    updateSelectCheckboxDropdown(wrapper);
                }
            }
        });

        // Filtro em tempo real no campo de busca do dropdown
        document.addEventListener('input', function (e) {
            if (e.target && e.target.classList.contains('select-checkbox-search')) {
                const searchInput = e.target;
                const dropdownMenu = searchInput.closest('.dropdown-menu');
                if (!dropdownMenu) return;

                const query = searchInput.value.toLowerCase().trim().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                const items = dropdownMenu.querySelectorAll('.dropdown-item-text');

                items.forEach(function (item) {
                    const label = (item.textContent || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                    item.style.display = label.includes(query) ? '' : 'none';
                });
            }
        });
    });
</script>
@endpush
@endonce
