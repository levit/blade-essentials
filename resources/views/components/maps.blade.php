@php
    $name = $name ?? ($component->name ?? \Select\Components\Support\StringSupport::nameRandom());
    $id = $id ?? ($component->id ?? "maps_{$name}_id");

    $containerId = $containerId ?? ($component->containerId ?? "container_{$id}");
    $statusId = $statusId ?? ($component->statusId ?? "status_{$id}");
    $addressInfoId = $addressInfoId ?? ($component->addressInfoId ?? "address_info_{$id}");
    $externalLinkId = $externalLinkId ?? ($component->externalLinkId ?? "ext_link_{$id}");

    $height = $height ?? ($component->height ?? '360px');
    $width = $width ?? ($component->width ?? '100%');
    $zoom = $zoom ?? ($component->zoom ?? 25);
    $locked = $locked ?? ($component->locked ?? false);
    $showToolbar = $showToolbar ?? ($component->showToolbar ?? true);
    $showExternalLink = $showExternalLink ?? ($component->showExternalLink ?? true);
    $class = $class ?? ($component->class ?? '');
    $syncFields = $syncFields ?? ($component->syncFields ?? []);

    $currentLat =
        $component->numericLatitude ?? (!empty($latitude) && is_numeric($latitude) ? (float) $latitude : null);
    $currentLng =
        $component->numericLongitude ?? (!empty($longitude) && is_numeric($longitude) ? (float) $longitude : null);

    $addressData = $component->addressData ?? [
        'cep' => (string) ($cep ?? ''),
        'logradouro' => (string) ($logradouro ?? ''),
        'bairro' => (string) ($bairro ?? ''),
        'cidade' => (string) ($cidade ?? ''),
        'uf' => (string) ($uf ?? ''),
        'titulo' => (string) ($titulo ?? 'Localização'),
    ];
@endphp

{{-- Inclusão de Estilos do Leaflet (Open-Source e 100% Gratuito) --}}
@once
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            .leaflet-container {
                font-family: inherit;
                background-color: #f8f9fa;
            }

            .maps-component-card {
                position: relative;
                overflow: hidden;
            }

            .maps-component-overlay {
                transition: all 0.3s ease;
            }
        </style>
    @endpush
@endonce

<div id="{{ $id }}_wrapper" @class([
    'maps-component-card',
    'w-100 h-100 d-flex flex-column' => $height === '100%',
    $class => !empty($class),
]) {{ $attributes ?? '' }}>
    {{-- Container do Mapa Leaflet --}}
    <div id="{{ $containerId }}"
        style="height: {{ $height }}; width: {{ $width }}; min-height: 260px; border-radius: 0.375rem; border: 1px solid #dee2e6; z-index: 1;"
        @class([
            'shadow-sm bg-light position-relative',
            'flex-grow-1' => $height === '100%',
        ])>
        {{-- Loader Inicial --}}
        <div id="{{ $id }}_loader" class="position-absolute top-50 start-50 translate-middle text-center"
            style="z-index: 999; pointer-events: none;">
            <div class="spinner-border spinner-border-sm text-primary me-1" role="status"></div>
            <span class="small text-muted fw-semibold">Carregando mapa...</span>
        </div>
    </div>

    {{-- Barra de Ferramentas / Rodapé do Mapa --}}
    @if ($showToolbar)
        <div class="d-flex flex-wrap align-items-center justify-content-between pt-2 px-1 gap-2 small">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <x-misc.badge :id="$statusId" color="light" class="text-muted border py-1 px-2">
                    <x-misc.icone icon="bi-geo-alt" color="primary" class="me-1" />Aguardando localização...
                </x-misc.badge>
                <span id="{{ $addressInfoId }}" class="text-muted small d-none d-md-inline"></span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <x-field.button type="button" color="secondary" class="py-0 px-2" icon="bi-arrow-clockwise"
                    label="Atualizar" tooltip="Recarregar localização no mapa"
                    onclick="window['recarregarMapa_{{ $id }}']()" />

                @if ($showExternalLink)
                    <x-misc.link id="{{ $externalLinkId }}" href="https://www.openstreetmap.org" target="_blank"
                        rel="noopener noreferrer" class="btn btn-sm btn-outline-primary py-0 px-2"
                        title="Ver em tela cheia no OpenStreetMap">
                        <x-misc.icone icon="bi-box-arrow-up-right" class="me-1" />OpenStreetMap
                    </x-misc.link>
                @endif
            </div>
        </div>
    @endif
</div>

{{-- Inclusão de Scripts do Leaflet e Motor de Georreferenciamento --}}
@once
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @endpush
@endonce

@push('scripts')
    <script>
        (function() {
            // Configurações e Estado do Mapa
            const mapContainer = document.getElementById('{{ $containerId }}');
            const loader = document.getElementById('{{ $id }}_loader');
            const statusBadge = document.getElementById('{{ $statusId }}');
            const addressInfo = document.getElementById('{{ $addressInfoId }}');
            const externalLink = document.getElementById('{{ $externalLinkId }}');

            let map = null;
            let marker = null;
            let initialZoom = {{ (int) $zoom }};
            let isLocked = @json((bool) $locked);

            // Coordenadas iniciais padrão (Centro do Brasil - Brasília como fallback)
            const fallbackLat = -15.793889;
            const fallbackLng = -47.882778;
            let currentLat = {{ !is_null($currentLat) ? $currentLat : 'null' }};
            let currentLng = {{ !is_null($currentLng) ? $currentLng : 'null' }};

            // Dados de endereço recebidos do Blade / PHP Component
            let dadosEndereco = @json($addressData);

            // Inicializa o Leaflet Map
            function initMap() {
                if (!mapContainer || typeof L === 'undefined') return;

                const startLat = currentLat !== null ? currentLat : fallbackLat;
                const startLng = currentLng !== null ? currentLng : fallbackLng;
                const startZoom = currentLat !== null ? initialZoom : 4;

                map = L.map('{{ $containerId }}', {
                    center: [startLat, startLng],
                    zoom: startZoom,
                    scrollWheelZoom: true,
                    zoomControl: true
                });

                // Camada de Tiles 100% Gratuita do OpenStreetMap
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>'
                }).addTo(map);

                // Ícone padrão com sombra
                const customIcon = L.icon({
                    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                marker = L.marker([startLat, startLng], {
                    icon: customIcon
                }).addTo(map);

                if (loader) loader.style.display = 'none';

                // Aplica o estado de trava inicial (modo estático/foto se locked=true)
                aplicarEstadoTrava(isLocked);

                // Dispara geocodificação ou centralização
                resolverLocalizacao();

                // Garante que o mapa redesenhe seus tiles quando exibido após abas/collapse
                setTimeout(() => {
                    if (map) map.invalidateSize();
                }, 300);
            }

            // Aplica ou remove a trava de interação do mapa Leaflet
            function aplicarEstadoTrava(travar) {
                isLocked = travar;
                if (!map) return;

                if (isLocked) {
                    map.dragging.disable();
                    map.touchZoom.disable();
                    map.doubleClickZoom.disable();
                    map.scrollWheelZoom.disable();
                    map.boxZoom.disable();
                    map.keyboard.disable();
                    const zoomCtrl = mapContainer.querySelector('.leaflet-control-zoom');
                    if (zoomCtrl) zoomCtrl.style.display = 'none';
                    mapContainer.style.cursor = 'default';
                } else {
                    map.dragging.enable();
                    map.touchZoom.enable();
                    map.doubleClickZoom.enable();
                    map.scrollWheelZoom.enable();
                    map.boxZoom.enable();
                    map.keyboard.enable();
                    const zoomCtrl = mapContainer.querySelector('.leaflet-control-zoom');
                    if (zoomCtrl) zoomCtrl.style.display = '';
                    mapContainer.style.cursor = 'grab';
                }
            }

            // Atualiza o Marcador e Popup no Mapa
            function posicionarNoMapa(lat, lng, zoomLevel, fonte, infoTexto) {
                currentLat = lat;
                currentLng = lng;

                if (!map || !marker) return;

                map.setView([lat, lng], zoomLevel);
                marker.setLatLng([lat, lng]);

                // Conteúdo do Popup
                const cepFormatado = dadosEndereco.cep ? dadosEndereco.cep.replace(/\D/g, '').replace(/^(\d{5})(\d)/,
                    '$1-$2') : '';
                const popupHtml = `
            <div style="min-width: 180px; font-size: 13px;">
                <strong style="color: #0d6efd; display: block; margin-bottom: 3px;">
                    <i class="bi bi-geo-alt-fill"></i> ${dadosEndereco.titulo}
                </strong>
                ${cepFormatado ? `<div><strong>CEP:</strong> ${cepFormatado}</div>` : ''}
                ${dadosEndereco.logradouro ? `<div><strong>Endereço:</strong> ${dadosEndereco.logradouro}</div>` : ''}
                ${dadosEndereco.bairro ? `<div><strong>Bairro:</strong> ${dadosEndereco.bairro}</div>` : ''}
                ${(dadosEndereco.cidade || dadosEndereco.uf) ? `<div><strong>Cidade:</strong> ${dadosEndereco.cidade || ''}/${dadosEndereco.uf || ''}</div>` : ''}
                <div style="margin-top: 6px; padding-top: 4px; border-top: 1px solid #eee; font-size: 11px; color: #6c757d;">
                    ${lat.toFixed(5)}, ${lng.toFixed(5)} (${fonte})
                </div>
            </div>
        `;
                marker.bindPopup(popupHtml).openPopup();

                // Atualiza elementos visuais da toolbar
                if (statusBadge) {
                    statusBadge.className =
                        'badge bg-success-subtle text-success border border-success-subtle py-1 px-2';
                    statusBadge.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i>Localizado (${fonte})`;
                }

                if (addressInfo) {
                    addressInfo.textContent = infoTexto ||
                        `${dadosEndereco.logradouro || ''} - ${dadosEndereco.cidade || ''}/${dadosEndereco.uf || ''}`;
                }

                if (externalLink) {
                    externalLink.href =
                        `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lng}#map=${zoomLevel}/${lat}/${lng}`;
                }

                // Dispara evento global notificando as coordenadas atualizadas para componentes externos (ex: botão Google Maps)
                window.dispatchEvent(new CustomEvent('mapaCoordenadasAtualizadas_{{ $id }}', {
                    detail: {
                        lat: lat,
                        lng: lng,
                        zoom: zoomLevel,
                        fonte: fonte,
                        infoTexto: infoTexto ||
                            `${dadosEndereco.logradouro || ''} - ${dadosEndereco.cidade || ''}/${dadosEndereco.uf || ''}`
                    }
                }));
            }

            // Funções auxiliares para consulta assíncrona ao Nominatim
            async function consultarNominatim(query) {
                try {
                    const url =
                        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=br&limit=1`;
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (response.ok) {
                        const data = await response.json();
                        if (Array.isArray(data) && data.length > 0) {
                            return data[0];
                        }
                    }
                } catch (err) {
                    console.warn('[Maps Component] Falha ao consultar Nominatim:', err);
                }
                return null;
            }

            async function consultarNominatimPostalCode(postcode) {
                try {
                    const url =
                        `https://nominatim.openstreetmap.org/search?postalcode=${encodeURIComponent(postcode)}&countrycodes=br&format=json&limit=1`;
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (response.ok) {
                        const data = await response.json();
                        if (Array.isArray(data) && data.length > 0) {
                            return data[0];
                        }
                    }
                } catch (err) {
                    console.warn('[Maps Component] Falha ao consultar Nominatim por CEP:', err);
                }
                return null;
            }

            // Mecanismo em Cascata de Geocodificação de Alta Precisão (100% Gratuito)
            async function resolverLocalizacao() {
                // 1. Se coordenadas foram passadas diretamente via atributo (ex: CRUD Cidades)
                if (currentLat !== null && currentLng !== null) {
                    posicionarNoMapa(currentLat, currentLng, initialZoom, 'Coordenadas Fixas',
                        'Posição pré-definida');
                    return;
                }

                const cepLimpo = dadosEndereco.cep ? dadosEndereco.cep.replace(/\D/g, '') : '';
                const cepFormatado = (cepLimpo && cepLimpo.length === 8) ? cepLimpo.replace(/^(\d{5})(\d{3})$/,
                    '$1-$2') : '';
                let brasilApiFallbackCoords = null;

                // Se NÃO temos logradouro ou cidade definidos, mas temos CEP, consulta BrasilAPI v2 para enriquecer os dados
                if ((!dadosEndereco.logradouro || !dadosEndereco.cidade) && cepLimpo.length === 8) {
                    if (statusBadge) {
                        statusBadge.className =
                        'badge bg-info-subtle text-info border border-info-subtle py-1 px-2';
                        statusBadge.innerHTML =
                            '<span class="spinner-border spinner-border-sm me-1"></span>Consultando CEP (BrasilAPI)...';
                    }

                    try {
                        const response = await fetch(`https://brasilapi.com.br/api/cep/v2/${cepLimpo}`);
                        if (response.ok) {
                            const data = await response.json();
                            if (!dadosEndereco.logradouro && data.street) dadosEndereco.logradouro = data.street;
                            if (!dadosEndereco.bairro && data.neighborhood) dadosEndereco.bairro = data
                            .neighborhood;
                            if (!dadosEndereco.cidade && data.city) dadosEndereco.cidade = data.city;
                            if (!dadosEndereco.uf && data.state) dadosEndereco.uf = data.state;

                            if (data.location && data.location.coordinates && data.location.coordinates.latitude &&
                                data.location.coordinates.longitude) {
                                brasilApiFallbackCoords = {
                                    lat: parseFloat(data.location.coordinates.latitude),
                                    lng: parseFloat(data.location.coordinates.longitude)
                                };
                            }
                        }
                    } catch (err) {
                        console.warn('[Maps Component] Falha ao consultar BrasilAPI v2:', err);
                    }
                }

                // 2. Prioridade Máxima: Geocodificar por Logradouro / Rua via OpenStreetMap Nominatim
                if (dadosEndereco.logradouro && (dadosEndereco.cidade || dadosEndereco.uf)) {
                    if (statusBadge) {
                        statusBadge.className =
                            'badge bg-warning-subtle text-warning border border-warning-subtle py-1 px-2';
                        statusBadge.innerHTML =
                            '<span class="spinner-border spinner-border-sm me-1"></span>Localizando rua no mapa...';
                    }

                    // Tentativa A: Logradouro + Bairro + Cidade + UF + Brasil
                    const partesA = [dadosEndereco.logradouro, dadosEndereco.bairro, dadosEndereco.cidade,
                        dadosEndereco.uf, 'Brasil'
                    ].filter(Boolean);
                    let itemEncontrado = await consultarNominatim(partesA.join(', '));

                    // Tentativa B: Logradouro + Cidade + UF + Brasil (sem bairro, comum quando bairro não bate exato no OSM)
                    if (!itemEncontrado && dadosEndereco.bairro) {
                        const partesB = [dadosEndereco.logradouro, dadosEndereco.cidade, dadosEndereco.uf, 'Brasil']
                            .filter(Boolean);
                        itemEncontrado = await consultarNominatim(partesB.join(', '));
                    }

                    // Tentativa C: Apenas nome da rua base (sem número ou complemento) + Cidade + UF + Brasil
                    if (!itemEncontrado && (dadosEndereco.logradouro.includes(',') || dadosEndereco.logradouro
                            .includes('-'))) {
                        const ruaBase = dadosEndereco.logradouro.split(/[,-]/)[0].trim();
                        if (ruaBase && ruaBase !== dadosEndereco.logradouro) {
                            const partesC = [ruaBase, dadosEndereco.cidade, dadosEndereco.uf, 'Brasil'].filter(
                                Boolean);
                            itemEncontrado = await consultarNominatim(partesC.join(', '));
                        }
                    }

                    if (itemEncontrado) {
                        const lat = parseFloat(itemEncontrado.lat);
                        const lng = parseFloat(itemEncontrado.lon);
                        posicionarNoMapa(lat, lng, 17, 'OpenStreetMap', itemEncontrado.display_name.split(',')
                            .slice(0, 3).join(', '));
                        return;
                    }
                }

                // 3. Segunda Prioridade: Geocodificar por CEP via OpenStreetMap Nominatim
                if (cepFormatado || (cepLimpo && cepLimpo.length === 8)) {
                    const cepParaBusca = cepFormatado || cepLimpo;
                    let itemCep = await consultarNominatimPostalCode(cepParaBusca);
                    if (!itemCep && cepFormatado !== cepLimpo) {
                        itemCep = await consultarNominatimPostalCode(cepLimpo);
                    }
                    if (itemCep) {
                        const lat = parseFloat(itemCep.lat);
                        const lng = parseFloat(itemCep.lon);
                        posicionarNoMapa(lat, lng, 16, 'OSM CEP', itemCep.display_name.split(',').slice(0, 3).join(
                            ', '));
                        return;
                    }
                }

                // 4. Terceira Prioridade: Fallback com coordenadas da BrasilAPI (se obtidas)
                if (brasilApiFallbackCoords && !isNaN(brasilApiFallbackCoords.lat) && !isNaN(brasilApiFallbackCoords
                        .lng)) {
                    posicionarNoMapa(brasilApiFallbackCoords.lat, brasilApiFallbackCoords.lng, 15, 'BrasilAPI',
                        `${dadosEndereco.logradouro || ''}, ${dadosEndereco.cidade || ''} - ${dadosEndereco.uf || ''}`
                        );
                    return;
                }

                // Se ainda não consultou a BrasilAPI (pois já tinha logradouro mas Nominatim não achou), tenta agora
                if (cepLimpo.length === 8) {
                    try {
                        const response = await fetch(`https://brasilapi.com.br/api/cep/v2/${cepLimpo}`);
                        if (response.ok) {
                            const data = await response.json();
                            if (data.location && data.location.coordinates && data.location.coordinates.latitude &&
                                data.location.coordinates.longitude) {
                                const lat = parseFloat(data.location.coordinates.latitude);
                                const lng = parseFloat(data.location.coordinates.longitude);
                                posicionarNoMapa(lat, lng, 15, 'BrasilAPI',
                                    `${data.street || dadosEndereco.logradouro || ''}, ${data.city || dadosEndereco.cidade || ''}`
                                    );
                                return;
                            }
                        }
                    } catch (err) {
                        console.warn('[Maps Component] Falha no fallback BrasilAPI:', err);
                    }
                }

                // 5. Fallback final: Apenas Cidade / UF no Nominatim
                if (dadosEndereco.cidade && dadosEndereco.uf) {
                    const itemCidade = await consultarNominatim(
                        `${dadosEndereco.cidade}, ${dadosEndereco.uf}, Brasil`);
                    if (itemCidade) {
                        const lat = parseFloat(itemCidade.lat);
                        const lng = parseFloat(itemCidade.lon);
                        posicionarNoMapa(lat, lng, 12, 'OSM Cidade', `${dadosEndereco.cidade}/${dadosEndereco.uf}`);
                        return;
                    }
                }

                // Se nada for encontrado
                if (statusBadge) {
                    statusBadge.className = 'badge bg-secondary-subtle text-muted border py-1 px-2';
                    statusBadge.innerHTML = '<i class="bi bi-info-circle me-1"></i>Localização não encontrada';
                }
            }

            // Função pública para forçar atualização do mapa
            window['recarregarMapa_{{ $id }}'] = function(novosDados = null) {
                if (novosDados) {
                    dadosEndereco = Object.assign(dadosEndereco, novosDados);
                }
                currentLat = null;
                currentLng = null;
                resolverLocalizacao();
            };

            // Funções públicas para controle da trava de interação do mapa
            window['toggleTravaMapa_{{ $id }}'] = function() {
                aplicarEstadoTrava(!isLocked);
                return isLocked;
            };
            window['isMapaTravado_{{ $id }}'] = function() {
                return isLocked;
            };
            window['mapInstance_{{ $id }}'] = map;

            // Sincronização Dinâmica com Campos de Formulário (se fornecido)
            const syncMap = @json($syncFields);
            if (syncMap && typeof syncMap === 'object') {
                const capturarValoresFormulario = () => {
                    let houveMudanca = false;

                    if (syncMap.cep) {
                        const elCep = document.querySelector(syncMap.cep);
                        if (elCep && elCep.value !== dadosEndereco.cep) {
                            dadosEndereco.cep = elCep.value;
                            houveMudanca = true;
                        }
                    }
                    if (syncMap.logradouro) {
                        const elLog = document.querySelector(syncMap.logradouro);
                        if (elLog && elLog.value !== dadosEndereco.logradouro) {
                            dadosEndereco.logradouro = elLog.value;
                            houveMudanca = true;
                        }
                    }
                    if (syncMap.bairro) {
                        const elBai = document.querySelector(syncMap.bairro);
                        if (elBai && elBai.value !== dadosEndereco.bairro) {
                            dadosEndereco.bairro = elBai.value;
                            houveMudanca = true;
                        }
                    }
                    if (syncMap.estado_uf) {
                        const elUf = document.querySelector(syncMap.estado_uf);
                        if (elUf && elUf.value !== dadosEndereco.uf) {
                            dadosEndereco.uf = elUf.value;
                            houveMudanca = true;
                        }
                    }
                    if (syncMap.cidade || syncMap.cidade_id) {
                        const elCid = document.querySelector(syncMap.cidade || syncMap.cidade_id);
                        if (elCid) {
                            const textoCidade = elCid.tagName === 'SELECT' && elCid.selectedIndex >= 0 ? elCid
                                .options[elCid.selectedIndex].text : elCid.value;
                            if (textoCidade && textoCidade !== dadosEndereco.cidade) {
                                dadosEndereco.cidade = textoCidade;
                                houveMudanca = true;
                            }
                        }
                    }

                    if (houveMudanca) {
                        window['recarregarMapa_{{ $id }}']();
                    }
                };

                // Escuta eventos change/blur nos inputs monitorados
                Object.values(syncMap).forEach(seletor => {
                    if (!seletor) return;
                    const el = document.querySelector(seletor);
                    if (el) {
                        el.addEventListener('change', capturarValoresFormulario);
                        el.addEventListener('blur', capturarValoresFormulario);
                    }
                });
            }

            // Inicialização quando o DOM estiver pronto
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initMap);
            } else {
                initMap();
            }

            // Eventos de redimensionamento e expansão de card/collapse
            window.addEventListener('resize', () => {
                if (map) map.invalidateSize();
            });

            document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(trigger => {
                trigger.addEventListener('click', () => {
                    setTimeout(() => {
                        if (map) map.invalidateSize();
                    }, 350);
                });
            });
        })();
    </script>
@endpush
