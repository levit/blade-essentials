<?php

declare(strict_types=1);

namespace Select\Components\Components;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Select\Components\Support\StringSupport;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Renderização e Georreferenciamento de Mapas (Maps).
 *
 * Utiliza o Leaflet.js e OpenStreetMap para exibição de mapas interativos, suporte
 * a coordenadas fixas, geocodificação em cascata por CEP/logradouro/cidade e sincronização dinâmica com formulários.
 */
class Maps extends Component
{
    public string $name;

    public string $id;

    public string $containerId;

    public string $statusId;

    public string $addressInfoId;

    public string $externalLinkId;

    public ?float $numericLatitude;

    public ?float $numericLongitude;

    /**
     * @var array{cep: string, logradouro: string, bairro: string, cidade: string, uf: string, titulo: string}
     */
    public array $addressData;

    /**
     * Cria uma nova instância do componente Maps.
     *
     * @param string|null $name Nome identificador do mapa.
     * @param string|null $id Identificador HTML único do container do mapa.
     * @param string $height Altura CSS do container do mapa (padrão: '360px').
     * @param string $width Largura CSS do container do mapa (padrão: '100%').
     * @param float|int|string|null $latitude Coordenada de latitude inicial fixa.
     * @param float|int|string|null $longitude Coordenada de longitude inicial fixa.
     * @param int $zoom Nível de zoom inicial do mapa (padrão: 25).
     * @param string|null $cep CEP para geocodificação automática.
     * @param string|null $logradouro Nome da rua/logradouro para busca espacial.
     * @param string|null $bairro Bairro para precisão da geocodificação.
     * @param string|null $cidade Nome da cidade / município.
     * @param string|null $uf Sigla da Unidade Federativa (Estado).
     * @param string $titulo Rótulo ou título do popup do marcador.
     * @param bool $locked Inicia com interações e arrasto desabilitados (estático).
     * @param bool $showExternalLink Exibe link direto para abrir no OpenStreetMap.
     * @param bool $showToolbar Exibe barra de status e botões de ação abaixo do mapa.
     * @param array $syncFields Mapeamento de seletores CSS para escuta e recarregamento automático.
     * @param string $class Classes CSS adicionais para o wrapper do mapa.
     */
    public function __construct(
        ?string $name = null,
        ?string $id = null,
        public string $height = '360px',
        public string $width = '100%',
        public float|int|string|null $latitude = null,
        public float|int|string|null $longitude = null,
        public int $zoom = 25,
        public ?string $cep = null,
        public ?string $logradouro = null,
        public ?string $bairro = null,
        public ?string $cidade = null,
        public ?string $uf = null,
        public string $titulo = 'Localização',
        public bool $locked = false,
        public bool $showExternalLink = true,
        public bool $showToolbar = true,
        public array $syncFields = [],
        public string $class = '',
    ) {
        $this->name = $name ?? StringSupport::nameRandom();
        $this->id = StringSupport::hasValue($id)
            ? (string) $id
            : ($name !== null ? "maps_{$this->name}_id" : ComponentIdPrefixEnum::MAPS->generateId());

        $this->containerId = 'container_' . $this->id;
        $this->statusId = 'status_' . $this->id;
        $this->addressInfoId = 'address_info_' . $this->id;
        $this->externalLinkId = 'ext_link_' . $this->id;

        $this->numericLatitude = (!empty($this->latitude) && is_numeric($this->latitude)) ? (float) $this->latitude : null;
        $this->numericLongitude = (!empty($this->longitude) && is_numeric($this->longitude)) ? (float) $this->longitude : null;

        $this->addressData = [
            'cep' => (string) ($this->cep ?? ''),
            'logradouro' => (string) ($this->logradouro ?? ''),
            'bairro' => (string) ($this->bairro ?? ''),
            'cidade' => (string) ($this->cidade ?? ''),
            'uf' => (string) ($this->uf ?? ''),
            'titulo' => (string) ($this->titulo ?: 'Localização'),
        ];
    }

    /**
     * Retorna os dados normalizados de endereço para geocodificação.
     *
     * @return array{cep: string, logradouro: string, bairro: string, cidade: string, uf: string, titulo: string}
     */
    public function getAddressData(): array
    {
        return $this->addressData;
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.maps');
    }
}
