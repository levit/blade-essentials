# 🗺️ Mapas e Geolocalização (`<x-maps>`)

O componente `<x-maps>` oferece um mapa geográfico interativo integrado com **Leaflet** e **OpenStreetMap**. É ideal para cadastro de endereços, visualização de coordenadas, geocodificação direta e sincronização bidirecional com campos de formulário.

---

## `<x-maps>`

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `height` | `string` | `'360px'` | Altura do elemento visual do mapa. |
| `width` | `string` | `'100%'` | Largura do mapa. |
| `latitude` | `float\|int\|string\|null` | `null` | Coordenada inicial de latitude. |
| `longitude` | `float\|int\|string\|null` | `null` | Coordenada inicial de longitude. |
| `zoom` | `int` | `25` | Nível de zoom inicial do mapa. |
| `cep` | `string\|null` | `null` | CEP inicial para geocodificação automática. |
| `logradouro` | `string\|null` | `null` | Rua/Avenida para busca de localização. |
| `bairro` | `string\|null` | `null` | Bairro para busca. |
| `cidade` | `string\|null` | `null` | Cidade para busca. |
| `uf` | `string\|null` | `null` | Estado (UF). |
| `titulo` | `string` | `'Localização'` | Título do marcador no mapa. |
| `locked` | `bool` | `false` | Se `true`, bloqueia o arraste do marcador (modo somente leitura). |
| `showToolbar` | `bool` | `true` | Exibe barra superior com botão de centralizar e status de endereço. |
| `showExternalLink` | `bool` | `true` | Exibe link direto para abrir no OpenStreetMap externo. |
| `syncFields` | `array` | `[]` | Mapeamento de campos do formulário para preenchimento ao mover o marcador (ex: `['lat' => '#latitude', 'lng' => '#longitude']`). |

---

## 🛠️ Exemplos Práticos

### 1. Mapa de Visualização Simples com Coordenadas

```blade
<x-maps
    latitude="-23.550520"
    longitude="-46.633308"
    titulo="Marco Zero de São Paulo"
    height="300px"
    :locked="true"
/>
```

### 2. Mapa Interativo com Sincronização em Formulário de Cadastro

```blade
<div class="row">
    <div class="col-md-6">
        <x-field.input id="lat_id" nameField="latitude" label="Latitude" />
        <x-field.input id="lng_id" nameField="longitude" label="Longitude" class="mt-2" />
    </div>

    <div class="col-md-6">
        {{-- Ao arrastar o pino no mapa, as coordenadas atualizam os inputs acima --}}
        <x-maps
            height="320px"
            latitude="-23.550520"
            longitude="-46.633308"
            :syncFields="[
                'lat' => '#lat_id',
                'lng' => '#lng_id'
            ]"
        />
    </div>
</div>
```
