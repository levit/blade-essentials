# 📊 Tabelas Interativas e Datatables

O ecossistema `<x-datatable.*>` fornece uma estrutura completa para renderização de listagens de dados responsivas, tabelas estáticas do Bootstrap e tabelas ricas interativas integradas com a biblioteca **Simple-DataTables** e a paginação nativa do Laravel.

Componentes do namespace:
* [`<x-datatable.table>`](#x-datatabletable)
* [`<x-datatable.header>`](#x-datatableheader)
* [`<x-datatable.row>`](#x-datatablerow)
* [`<x-datatable.cell>`](#x-datatablecell)
* [`<x-datatable.filters>`](#x-datatablefilters)
* [`<x-datatable.actions>`](#x-datatableactions)
* [`<x-datatable.empty>` (ou `empty-state`)](#x-datatableempty)

---

## `<x-datatable.table>`

Container principal da tabela. Pode ser utilizado em modo simples ou com paginação do Laravel.

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `headers` | `array\|Collection` | `[]` | Definição das colunas (array com `'label'`, `'width'`, `'align'`, `'sortable'`). |
| `items` | `mixed` | `null` | Coleção de itens ou instância de `LengthAwarePaginator` do Laravel. |
| `jsDatatable` | `bool` | `true` | Ativa a biblioteca interativa Simple-DataTables (ordenação, busca no cliente). |
| `card` | `bool` | `true` | Envolve a tabela em um container estilo `.card`. |
| `paginacao` | `bool` | `false` | Se `true`, exibe os links de paginação e o resumo de registros. |
| `registrosPorPagina` | `int` | `15` | Quantidade de registros por página para Simple-DataTables. |
| `emptyMessage` | `string` | `'Nenhum registro encontrado.'` | Mensagem caso `items` esteja vazio. |
| `class` | `string` | `''` | Classes CSS aplicadas à tag `<table>`. |
| `responsiveClass` | `string` | `'d-inline-block w-100 table-responsive'` | Classes do wrapper responsivo da tabela. |

---

## `<x-datatable.header>`

Renderiza o cabeçalho `<thead>` da tabela.

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `class` | `string` | `''` | Classes CSS aplicadas à tag `<thead>` ou `<th>`. |

---

## `<x-datatable.row>`

Linha de dados (`<tr>`).

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `align` | `string` | `'middle'` | Alinhamento vertical do conteúdo da linha (`'top'`, `'middle'`, `'bottom'`). |
| `class` | `string` | `''` | Classes CSS adicionais aplicadas ao `<tr>`. |

---

## `<x-datatable.cell>`

Célula de dado (`<td>`).

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `align` | `string` | `'left'` | Alinhamento horizontal do texto (`'left'`, `'center'`, `'right'`). |
| `colspan` | `string\|int\|null` | `null` | Quantidade de colunas mescladas. |
| `rowspan` | `string\|int\|null` | `null` | Quantidade de linhas mescladas. |
| `sort_data` | `mixed` | `null` | Valor específico para ordenação numérica ou por data (atributo `data-sort`). |

---

## `<x-datatable.filters>`

Barra colapsável de filtros de pesquisa rápida para tabelas.

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `action` | `string` | *(Obrigatório)* | URL de submissão do formulário de filtros (via GET). |
| `clearRoute` | `string\|null` | `null` | Rota ou URL para o botão de limpar filtros. |
| `mostrarBuscaTexto` | `bool` | `true` | Exibe o campo de busca textual geral (`search`). |
| `mostrarDataInicial` | `bool` | `false` | Exibe filtro de data inicial (`date_from`). |
| `mostrarDataFinal` | `bool` | `false` | Exibe filtro de data final (`date_to`). |
| `statuses` | `array` | `[]` | Lista de opções para filtro por status `['ativo' => 'Ativo', ...]`. |
| `iniciarOcultado` | `bool` | `true` | Se `true`, a barra inicia colapsada. |

---

## `<x-datatable.actions>`

Coluna de ações padrão com botões padronizados para cada registro (Visualizar, Editar, Excluir).

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `routePrefix` | `string` | `''` | Prefixo das rotas nomeadas (ex: `'usuarios'`). Espera `{prefix}.show`, `{prefix}.edit` e `{prefix}.destroy`. |
| `recordId` / `id` | `string\|int\|null` | `null` | ID do registro associado às rotas. |
| `showView` | `bool` | `true` | Exibe botão de Visualização. |
| `showEdit` | `bool` | `true` | Exibe botão de Edição. |
| `showDelete` | `bool` | `true` | Exibe botão de Exclusão (submete form DELETE com confirmação). |
| `viewRoute` | `string\|null` | `null` | URL customizada para visualização. |
| `editRoute` | `string\|null` | `null` | URL customizada para edição. |
| `deleteRoute` | `string\|null` | `null` | URL customizada para exclusão. |

---

## `<x-datatable.empty>` (ou `<x-datatable.empty-state>`)

Exibe uma ilustração ou ícone centralizado com mensagem explicativa quando não houver registros.

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `message` | `string` | `'Nenhum registro encontrado.'` | Mensagem descritiva. |
| `icon` | `string` | `'inbox'` | Ícone representativo. |
| `colspan` | `string\|int\|null` | `null` | Quantidade de colunas mescladas. |

---

## 🛠️ Exemplo Completo de Datatable

```blade
@php
    $headers = [
        ['label' => 'ID', 'width' => '5%', 'align' => 'center'],
        ['label' => 'Nome', 'width' => '35%'],
        ['label' => 'E-mail', 'width' => '30%'],
        ['label' => 'Status', 'width' => '15%', 'align' => 'center'],
        ['label' => 'Ações', 'width' => '15%', 'align' => 'center'],
    ];
@endphp

{{-- Filtros de Pesquisa --}}
<x-datatable.filters
    action="{{ route('usuarios.index') }}"
    clearRoute="{{ route('usuarios.index') }}"
    :mostrarBuscaTexto="true"
    :mostrarDataInicial="true"
    :mostrarDataFinal="true"
    :statuses="['A' => 'Ativos', 'I' => 'Inativos']"
/>

{{-- Tabela de Registros --}}
<x-datatable.table :headers="$headers" :items="$usuarios" :paginacao="true" :jsDatatable="false">
    @forelse($usuarios as $usuario)
        <x-datatable.row>
            <x-datatable.cell align="center">{{ $usuario->id }}</x-datatable.cell>
            <x-datatable.cell>{{ $usuario->name }}</x-datatable.cell>
            <x-datatable.cell>{{ $usuario->email }}</x-datatable.cell>
            <x-datatable.cell align="center">
                <x-misc.badge
                    :label="$usuario->ativo ? 'Ativo' : 'Inativo'"
                    :color="$usuario->ativo ? 'success' : 'secondary'"
                    :pill="true"
                />
            </x-datatable.cell>
            <x-datatable.cell align="center">
                <x-datatable.actions
                    routePrefix="usuarios"
                    :recordId="$usuario->id"
                    :showView="true"
                    :showEdit="true"
                    :showDelete="true"
                />
            </x-datatable.cell>
        </x-datatable.row>
    @empty
        <x-datatable.empty colspan="5" message="Nenhum usuário localizado com os filtros informados." />
    @endforelse
</x-datatable.table>
```
