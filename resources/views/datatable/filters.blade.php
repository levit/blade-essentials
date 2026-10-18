@php  
    $action = $action ?? '';
    $DivFilterId = $DivFilterId ?? 'filterSectionId';
    $ButtonToggleId = $ButtonToggleId ?? 'toggleFilterBtnId';
    $iniciarOcultado = $iniciarOcultado ?? true;
    $displayStyle = $displayStyle ?? ($iniciarOcultado ? 'none' : 'block');
    $hasSlotSearch = isset($slot) && str_contains((string)$slot, 'name="search"');
    $deveExibirBusca = $mostrarBuscaTexto && !$hasSlotSearch;
    $mostrarDataInicial = $mostrarDataInicial ?? false;
    $mostrarDataFinal = $mostrarDataFinal ?? false;

    if (!isset($statusOptions)) {
        $statusOptions = [];
        foreach ($statuses ?? [] as $status) {
            if (is_object($status) && method_exists($status, 'label')) {
                $statusOptions[$status->value ?? $status->name] = $status->label();
            } elseif (is_string($status) || is_int($status)) {
                $statusOptions[$status] = (string) $status;
            }
        }
    }

    $resolvedClearRoute = $resolvedClearRoute ?? ($clearRoute ?? (str_contains($action, '?') ? $action . '&limpar=1' : $action . '?limpar=1'));
@endphp

<x-box.content id="{{ $DivFilterId }}" class="mt-2" style="display: {{ $displayStyle }}" :hasRow="false" bodyClass="p-3" bottom_space="mb-3">

    <x-form.form :action="$action" method="GET">
        
        <x-box.row class="align-items-end g-2 g-md-3" bottomSpace="mb-0">
        
            @if($deveExibirBusca)
                <x-box.col sizeMd="3">
                    <x-field.input nameField="search" label="Buscar por texto" inputType="search" :value="request('search')" :useOldValue="false" />
                </x-box.col>
            @endif

            @if(!empty($statusOptions))
                <x-box.col sizeMd="3">
                    <x-field.select nameField="status" label="Status" placeholder="Todos os Status" :options="$statusOptions" :value="request('status')"/>
                </x-box.col>
            @endif

            @if($mostrarDataInicial)
                <x-box.col sizeMd="2">
                    <x-field.datepicker nameField="date_from" label="Data Início" :value="request('date_from')" :useOldValue="false" />
                </x-box.col>
            @endif

            @if($mostrarDataFinal)
                <x-box.col sizeMd="2">
                    <x-field.datepicker nameField="date_to" label="Data Fim" :value="request('date_to')" :useOldValue="false" />
                </x-box.col>
            @endif

            {{ $slot }}

            <x-box.col sizeMd="auto" class="ms-auto d-flex align-items-end gap-2 pb-0">
                <x-field.button 
                    nameButton="applyFilters" 
                    label="Filtrar"
                    type="submit" 
                    icon="filter" 
                    iconLibrary="bootstrap" 
                    color="primary" 
                    size="md"
                    tooltip="Aplicar filtros"
                    class="me-0"
                />
                <x-field.button 
                    nameButton="clearFilters" 
                    label="Limpar"
                    type="link" 
                    icon="x-circle" 
                    iconLibrary="bootstrap" 
                    color="secondary" 
                    size="md"
                    tooltip="Limpar filtros"
                    :route="$resolvedClearRoute"
                    class="me-0"
                />
            </x-box.col>

        </x-box.row>
        
    </x-form.form>

</x-box.content>

@once
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {

                const urlParams = new URLSearchParams(window.location.search);
                const toggleBtn = document.getElementById('{{ $ButtonToggleId }}');
                const filterSection = document.getElementById('{{ $DivFilterId }}');              
                const toggleBtnIcon = toggleBtn?.querySelector('i');
                const hasActiveFilters = Array.from(urlParams.keys()).some(k => k !== 'page' && urlParams.get(k) !== '');
                
                if (toggleBtn && filterSection) {

                    if (hasActiveFilters) {
                        filterSection.style.display = 'block';
                        toggleBtn.classList.replace('btn-outline-primary', 'btn-primary');
                    }

                    toggleBtn.addEventListener('click', function () {
                        const isVisible = filterSection.style.display !== 'none';
                        filterSection.style.display = isVisible ? 'none' : 'block';

                        toggleBtn.classList.toggle('btn-outline-primary', isVisible);
                        toggleBtn.classList.toggle('btn-primary', !isVisible);

                        if (!isVisible && typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    });
                }
            });
        </script>
    @endpush
@endonce