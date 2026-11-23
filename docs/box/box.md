# 📦 Layout, Grid e Cards (`<x-box.*>`)

O namespace `<x-box.*>` fornece componentes estruturais para o sistema de grid do Bootstrap e cards avançados com suporte a colapso e botões de ação.

Componentes do namespace:
* [`<x-box.row>`](#x-boxrow)
* [`<x-box.col>`](#x-boxcol)
* [`<x-box.content>`](#x-boxcontent)

---

## `<x-box.row>`

Container de linha do grid Bootstrap (`.row`).

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `bottom_space` / `bottomSpace` | `string` | `'mb-3'` | Classe de margem inferior aplicada à linha. |
| `class` | `string\|null` | `null` | Classes CSS adicionais aplicadas ao `.row`. |
| `id` | `string\|null` | `null` | ID HTML do elemento. |

---

## `<x-box.col>`

Coluna responsiva do grid com controle detalhado para cada breakpoint do Bootstrap.

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `sizeXs` | `string\|int\|null` | `'12'` | Largura para telas extra-pequenas (`col-*`). |
| `sizeSm` | `string\|int\|null` | `'12'` | Largura para telas pequenas (`col-sm-*`). |
| `sizeMd` | `string\|int\|null` | `null` | Largura para telas médias (`col-md-*`). |
| `sizeLg` | `string\|int\|null` | `null` | Largura para telas grandes (`col-lg-*`). |
| `sizeXl` | `string\|int\|null` | `null` | Largura para telas extra-grandes (`col-xl-*`). |
| `sizeXxl` | `string\|int\|null` | `null` | Largura para telas gigantes (`col-xxl-*`). |
| `bottom_space` / `bottomSpace` | `string\|null` | `null` | Margem inferior da coluna. |
| `class` | `string\|null` | `null` | Classes adicionais da coluna. |

---

## `<x-box.content>`

Card corporativo completo (`.card`). Inclui cabeçalho customizável, ícones, ações laterais (botões adicionais no cabeçalho) e **funcionalidade de colapso/recolhimento** integrada.

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `title` | `string` | `''` | Título do card. |
| `icon` | `string\|null` | `null` | Ícone exibido antes do título. |
| `iconColor` | `string\|null` | `null` | Cor do ícone do cabeçalho. |
| `titleColor` | `string` | `'dark'` | Cor do texto do título. |
| `bgTitleColor` | `string` | `'bg-white border-bottom py-3'` | Classes de fundo e borda do cabeçalho. |
| `collapse` | `bool` | `true` | Se `true`, exibe botão para expandir/recolher o conteúdo do card. |
| `actions` | `mixed` | `null` | Conteúdo ou botões injetados no lado direito do cabeçalho. |
| `cardClass` | `string\|null` | `null` | Classes adicionais aplicadas ao card container. |
| `bodyClass` | `string\|null` | `null` | Classes adicionais aplicadas ao corpo do card (`.card-body`). |
| `sizeXs` a `sizeXxl` | `string\|int\|null` | `null` | Se informado, envolve o card automaticamente em uma `<x-box.col>`. |

### Exemplos Práticos

```blade
{{-- 1. Card com colapso e botão de ação no topo --}}
<x-box.content title="Dados Pessoais do Cliente" icon="bi-person" :collapse="true">
    <x-slot:actions>
        <button class="btn btn-sm btn-outline-primary">
            <x-misc.icone icon="pencil" /> Editar
        </button>
    </x-slot:actions>

    <x-box.row>
        <x-box.col sizeMd="6">
            <x-field.input nameField="nome" label="Nome" value="Carlos Silva" />
        </x-box.col>
        <x-box.col sizeMd="6">
            <x-field.input nameField="email" label="E-mail" value="carlos@email.com" />
        </x-box.col>
    </x-box.row>
</x-box.content>
```
