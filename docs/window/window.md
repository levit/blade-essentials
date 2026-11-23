# 🖥️ Janelas e Módulos CRUD (`<x-window.*>`)

O namespace `<x-window.*>` organiza a estrutura de páginas de administração corporativa, telas de cadastro e fluxos CRUD completos.

Componentes do namespace:
* [`<x-window.header>`](#x-windowheader)
* [`<x-window.content>`](#x-windowcontent)
* [`<x-window.crud>`](#x-windowcrud)
* [`<x-window.modal>`](#x-windowmodal)

---

## `<x-window.header>`

Cabeçalho de página com suporte a título, subtítulo explicativo, navegação estruturada por migalhas de pão (**Breadcrumbs**) e slot para botões de ação do topo.

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `caption` | `string` | `''` | Título principal da página. |
| `icon` | `string\|null` | `null` | Ícone ao lado do título. |
| `subtitle` | `string\|null` | `null` | Subtítulo ou descrição contextual da página. |
| `breadcrumbs` | `array` | `[]` | Array associativo de navegação `['Rótulo' => 'URL']`. O último item sem URL é considerado ativo. |
| `actions` | `mixed` | `null` | Botões de ação alinhados à direita do cabeçalho. |

---

## `<x-window.content>`

Container principal do corpo da página com espaçamento padronizado.

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `class` | `string\|null` | `null` | Classes adicionais do container. |

---

## `<x-window.crud>`

Componente integrador de telas de cadastro geradas dinamicamente ou formulários CRUD baseados em `FormBuilder`.

---

## 🛠️ Exemplo Completo de Página com Window

```blade
@extends('layouts.app')

@section('content')

{{-- Cabeçalho da Janela com Breadcrumbs e Botão de Ação --}}
<x-window.header
    caption="Gestão de Produtos"
    icon="bi-box-seam"
    subtitle="Listagem, controle de estoque e precificação dos itens da loja"
    :breadcrumbs="[
        'Início' => route('home'),
        'Catálogo' => '#',
        'Produtos' => null
    ]"
>
    <x-slot:actions>
        <x-field.button
            label="Novo Produto"
            icon="bi-plus-lg"
            color="primary"
            href="{{ route('produtos.create') }}"
            shortcut="N"
        />
    </x-slot:actions>
</x-window.header>

{{-- Conteúdo Principal --}}
<x-window.content>
    <x-box.content title="Filtros e Relatórios" icon="bi-filter">
        {{-- Área de filtros ou datatables --}}
    </x-box.content>
</x-window.content>

@endsection
```
