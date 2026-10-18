# Blade Components

Biblioteca corporativa de componentes Blade para Laravel. O pacote reúne campos
de formulário, elementos de interface, tabelas e componentes de layout para
reutilizar padrões visuais e comportamentais em aplicações Laravel.

## Requisitos

- PHP 8.3 ou superior, conforme as versões suportadas pelo Composer.
- Laravel 11, 12 ou 13.
- Bootstrap 5 para os estilos e comportamentos de interface usados pelos
  componentes (por exemplo, classes utilitárias, modais, dropdowns e collapse).

Alguns componentes também dependem de bibliotecas específicas, como Leaflet
para mapas e Simple-DataTables para a tabela interativa. Veja
[Assets e dependências de front-end](#assets-e-dependências-de-front-end).

## Instalação

Instale o pacote pelo Composer:

```bash
composer require levit/blade-components
```

O provider `Select\Components\SelectComponentsServiceProvider` é descoberto
automaticamente pelo Laravel. Não é necessário registrá-lo manualmente em
aplicações com package discovery habilitado.

Para publicar a configuração:

```bash
php artisan vendor:publish --tag=select-components-config
```

Para publicar as views e personalizá-las na aplicação:

```bash
php artisan vendor:publish --tag=select-components-views
```

## Uso

Os componentes são usados como tags Blade. Por exemplo, um campo de entrada:

```blade
<x-field.input
    nameField="usuario"
    label="Usuário"
    value="levi"
    placeholder="Digite seu usuário"
    :required="true"
/>
```

Os campos aceitam atributos HTML adicionais e, em geral, oferecem opções como
`id`, `nameField`, `label`, `value`, `required`, `readonly`, `disabled` e
`onlyView`. Campos com nome podem recuperar o valor enviado anteriormente pelo
Laravel (`old()`) e apresentar erros de validação quando disponíveis. `name`
é aceito como alternativa a `nameField` em componentes que estendem o
componente-base de campos.

Um select estático, por exemplo:

```blade
<x-field.select
    nameField="uf"
    label="Estado"
    :options="['SP' => 'São Paulo', 'RJ' => 'Rio de Janeiro']"
    selected="SP"
/>
```

Campos de formulário não substituem a validação no servidor: valide os dados
recebidos pela aplicação com as regras Laravel apropriadas.

### Componentes de formulário

Disponíveis no namespace `<x-field.*>`:

| Componente | Uso |
| --- | --- |
| `button` | Botões, links estilizados e botões de fechar. |
| `cep` | Campo para CEP. |
| `checkbox` | Checkbox, inclusive em modo switch. |
| `cpf-cnpj` | Documento CPF, CNPJ ou ambos. |
| `datepicker`, `datetime` | Campos de data e data/hora formatados. |
| `email` | Campo de e-mail com validação visual e sugestão configurável. |
| `file`, `image` | Seleção de arquivo e visualização de imagem. |
| `hidden`, `input`, `textarea`, `number`, `url` | Campos HTML de uso geral. |
| `input-group` | Agrupamento de conteúdo ao redor de um campo via slots. |
| `ip`, `user-agent` | Exibição ou entrada de endereço IP e user-agent. |
| `markdown` | Campo de conteúdo Markdown, com opções para badges de Markdown/LaTeX. |
| `money`, `phone` | Campos formatados para valores monetários e telefone. |
| `radio`, `select`, `select-checkbox` | Opções de seleção simples ou múltipla. |
| `switch`, `tags` | Controle switch e entrada de tags. |

Exemplo com um grupo de entrada e slots:

```blade
<x-field.input-group label="Preço" :joined="true">
    <x-slot:prepend>
        <span class="input-group-text">R$</span>
    </x-slot:prepend>
    <input class="form-control" type="text" name="preco">
</x-field.input-group>
```

### Tabelas e ações

O namespace `<x-datatable.*>` inclui `table`, `header`, `filters`, `row`,
`cell`, `empty` (também disponível como `empty-state`) e `actions`.

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

`items` pode receber um paginador Laravel; quando houver páginas, o componente
exibe os links e o resumo dos registros. Por padrão, `jsDatatable` habilita a
integração com Simple-DataTables. Ações podem receber URLs explícitas ou
`routePrefix` e `recordId`; nesse último caso, o componente espera as rotas
`{prefix}.show`, `{prefix}.edit` e `{prefix}.delete`.

### Layout, formulários e outros elementos

- `<x-box.row>`, `<x-box.col>` e `<x-box.content>`: estrutura de linhas,
  colunas responsivas e cards.
- `<x-form.form>` e `<x-form.actions>`: estrutura de formulário e ações.
- `<x-window.header>`, `<x-window.content>`, `<x-window.crud>` e
  `<x-window.modal>`: composição de janelas e telas.
- `<x-modal>` e `<x-modal.content>`: modal Bootstrap e seu conteúdo.
- `<x-maps>`: mapa interativo com coordenadas ou endereço.
- `<x-misc.badge>`, `<x-misc.icone>`, `<x-misc.image>`, `<x-misc.ip>`,
  `<x-misc.kbd>`, `<x-misc.label>`, `<x-misc.link>`, `<x-misc.messages>` e
  `<x-misc.user-agent>`: elementos de interface e apresentação.

Alguns componentes da raiz também estão disponíveis como `<x-badge>` e
`<x-switch>`.

## Assets e dependências de front-end

Os componentes usam classes e convenções do Bootstrap 5. A aplicação deve
carregar o CSS do Bootstrap e, para recursos interativos como modal, collapse e
dropdown, o JavaScript do Bootstrap.

Views de componentes podem adicionar dependências às stacks Blade `styles` e
`scripts`. Inclua ambas no layout principal para que esses recursos sejam
renderizados:

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

O componente de mapas usa Leaflet e OpenStreetMap. A tabela interativa carrega
Simple-DataTables quando `jsDatatable` está habilitado. O componente de ícones
suporta Bootstrap Icons, Feather, Font Awesome, Material Icons, Ionicons,
Remix Icon e Heroicons. Informe `library` ou use um prefixo reconhecido no nome
do ícone (por exemplo, `bi-search`) para escolher a biblioteca explicitamente.

O arquivo `config/select-components.php` contém as opções:

```php
return [
    'default_icon_library' => env('SELECT_COMPONENTS_ICON_LIB', 'bootstrap'),
    'enable_interactive_email_suggestion' => true,
];
```

Na implementação atual, esses dois valores ainda não controlam os componentes:
ícones sem biblioteca/prefixo usam Feather, e a sugestão de e-mail é controlada
pelo atributo `enableSuggestion` do `<x-field.email>` (habilitado por padrão).

O pacote também registra a publicação `select-components-assets`; consulte a
versão instalada do pacote para saber quais arquivos de assets ela disponibiliza
(a distribuição pode não incluir arquivos CSS/JS próprios).

## Configuração

| Opção | Padrão | Descrição |
| --- | --- | --- |
| `default_icon_library` | `bootstrap` | Opção declarada no arquivo; não altera atualmente o fallback de ícones, que é Feather. |
| `enable_interactive_email_suggestion` | `true` | Opção declarada no arquivo; use `enableSuggestion` no campo de e-mail para controlar as sugestões. |

## Testes

Instale as dependências de desenvolvimento e execute a suíte PHPUnit:

```bash
composer install
composer test
```

## Licença

Este pacote é distribuído sob licença proprietária (`proprietary`).
© Select.
