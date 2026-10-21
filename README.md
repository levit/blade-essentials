# 🧩 Blade Components

[![PHP Version](https://img.shields.io/badge/PHP-%5E8.3-777bb4.svg?style=flat-square&logo=php)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-11%20%7C%2012%20%7C%2013-ff2d20.svg?style=flat-square&logo=laravel)](https://laravel.com/)
[![License: Proprietary](https://img.shields.io/badge/license-proprietary-lightgrey.svg?style=flat-square)](composer.json)
[![Tests](https://img.shields.io/badge/tests-PHPUnit-blue.svg?style=flat-square)](phpunit.xml)

**Blade Components** é uma biblioteca corporativa de componentes Blade para aplicações Laravel. O pacote reúne campos de formulário, elementos de interface, tabelas e componentes de layout para reutilizar padrões visuais e comportamentais.

Os componentes integram-se às views Blade e podem ser personalizados por meio das views publicáveis e da configuração do pacote.

---

## 📋 Requisitos

- **PHP:** `>=8.3`
- **Framework:** Laravel 11, 12 ou 13
- **CSS e JavaScript:** Bootstrap 5 para as classes e comportamentos de interface usados pelos componentes

Alguns componentes também dependem de bibliotecas específicas, como Leaflet para mapas e Simple-DataTables para a tabela interativa. Consulte [Assets e configuração](#-assets-e-configuração).

---

## 📥 Instalação

Adicione o pacote ao projeto via [Composer](https://getcomposer.org/):

```bash
composer require softtan/blade-essentials
```

O provider `Select\Components\SelectComponentsServiceProvider` é descoberto automaticamente pelo Laravel. Não é necessário registrá-lo manualmente em aplicações com package discovery habilitado.

Para publicar a configuração:

```bash
php artisan vendor:publish --tag=select-components-config
```

Para publicar as views e personalizá-las na aplicação:

```bash
php artisan vendor:publish --tag=select-components-views
```

---

## ⚡ Guia Rápido de Uso (Quickstart)

Os componentes são usados como tags Blade. Abaixo estão alguns exemplos para começar.

### 1. Campos de formulário

Um campo de entrada:

```blade
<x-field.input
    nameField="usuario"
    label="Usuário"
    value="levi"
    placeholder="Digite seu usuário"
    :required="true"
/>
```

Um select estático:

```blade
<x-field.select
    nameField="uf"
    label="Estado"
    :options="['SP' => 'São Paulo', 'RJ' => 'Rio de Janeiro']"
    selected="SP"
/>
```

Os campos aceitam atributos HTML adicionais e, em geral, oferecem opções como `id`, `nameField`, `label`, `value`, `required`, `readonly`, `disabled` e `onlyView`. Campos com nome podem recuperar o valor enviado anteriormente pelo Laravel (`old()`) e apresentar erros de validação quando disponíveis. `name` é aceito como alternativa a `nameField` em componentes que estendem o componente-base de campos.

Campos de formulário não substituem a validação no servidor: valide os dados recebidos pela aplicação com as regras Laravel apropriadas.

### 2. Tabelas e ações

```blade
@php
    $headers = [
        ['label' => 'Código', 'width' => '10%'],
        ['label' => 'Nome'],
    ];
@endphp

<x-datatable.table :headers="$headers" :jsDatatable="false">
    <x-datatable.row>
        <x-datatable.cell>1</x-datatable.cell>
        <x-datatable.cell>Exemplo</x-datatable.cell>
    </x-datatable.row>
</x-datatable.table>
```

`items` pode receber um paginador Laravel; quando houver páginas, o componente exibe os links e o resumo dos registros. Por padrão, `jsDatatable` habilita a integração com Simple-DataTables. Ações podem receber URLs explícitas ou `routePrefix` e `recordId`; nesse último caso, o componente espera as rotas `{prefix}.show`, `{prefix}.edit` e `{prefix}.delete`.

### 3. Grupos de entrada

```blade
<x-field.input-group label="Preço" :joined="true">
    <x-slot:prepend>
        <span class="input-group-text">R$</span>
    </x-slot:prepend>
    <input class="form-control" type="text" name="preco">
</x-field.input-group>
```

---

## 🗺️ Mapa de Componentes Disponíveis

| Grupo | Namespace / tags | Componentes |
| --- | --- | --- |
| **Formulários** | `<x-field.*>` | `button`, `cep`, `checkbox`, `cpf-cnpj`, `datepicker`, `datetime`, `email`, `file`, `hidden`, `image`, `input`, `input-group`, `ip`, `markdown`, `money`, `number`, `phone`, `radio`, `select`, `select-checkbox`, `switch`, `tags`, `textarea`, `url` e `user-agent`. |
| **Tabelas** | `<x-datatable.*>` | `table`, `header`, `filters`, `row`, `cell`, `empty` (também `empty-state`) e `actions`. |
| **Layout** | `<x-box.*>`, `<x-form.*>`, `<x-window.*>` | Linhas, colunas, cards, formulários, ações, cabeçalhos, conteúdo, telas CRUD e modais. |
| **Modais** | `<x-modal>` | Modal Bootstrap e seu conteúdo (`<x-modal.content>`). |
| **Mapas** | `<x-maps>` | Mapa interativo com coordenadas ou endereço. |
| **Elementos de interface** | `<x-misc.*>` | `badge`, `icone`, `image`, `ip`, `kbd`, `label`, `link`, `messages` e `user-agent`. |
| **Componentes de raiz** | `<x-badge>`, `<x-switch>` | Badge e controle switch. |

---

## 🧩 Assets e configuração

Os componentes usam classes e convenções do Bootstrap 5. A aplicação deve carregar o CSS do Bootstrap e, para recursos interativos como modal, collapse e dropdown, o JavaScript do Bootstrap.

Views de componentes podem adicionar dependências às stacks Blade `styles` e `scripts`. Inclua ambas no layout principal para que esses recursos sejam renderizados:

```blade
<!doctype html>
<html lang="pt-BR">
<head>
    {{-- CSS da aplicação e do Bootstrap --}}
    @stack('styles')
</head>
<body>
    @yield('content')

    {{-- JavaScript da aplicação e do Bootstrap --}}
    @stack('scripts')
</body>
</html>
```

O componente de mapas usa Leaflet e OpenStreetMap. A tabela interativa carrega Simple-DataTables quando `jsDatatable` está habilitado. O componente de ícones suporta Bootstrap Icons, Feather, Font Awesome, Material Icons, Ionicons, Remix Icon e Heroicons. Informe `library` ou use um prefixo reconhecido no nome do ícone (por exemplo, `bi-search`) para escolher a biblioteca explicitamente.

O arquivo `config/select-components.php` contém as opções:

```php
return [
    'default_icon_library' => env('SELECT_COMPONENTS_ICON_LIB', 'bootstrap'),
    'enable_interactive_email_suggestion' => true,
];
```

Na implementação atual, esses dois valores ainda não controlam os componentes: ícones sem biblioteca/prefixo usam Feather, e a sugestão de e-mail é controlada pelo atributo `enableSuggestion` do `<x-field.email>` (habilitado por padrão).

O pacote também registra a publicação `select-components-assets`; consulte a versão instalada do pacote para saber quais arquivos de assets ela disponibiliza (a distribuição pode não incluir arquivos CSS/JS próprios).

| Opção | Padrão | Descrição |
| --- | --- | --- |
| `default_icon_library` | `bootstrap` | Opção declarada no arquivo; não altera atualmente o fallback de ícones, que é Feather. |
| `enable_interactive_email_suggestion` | `true` | Opção declarada no arquivo; use `enableSuggestion` no campo de e-mail para controlar as sugestões. |

---

## 🧪 Executando os Testes

Instale as dependências de desenvolvimento e execute a suíte PHPUnit:

```bash
composer install
composer test
```

---

## 📜 Licença e Créditos

Este pacote é distribuído sob licença proprietária (`proprietary`).

© Select.
