# 🔘 Botões e Agrupamentos

Esta seção documenta os componentes de botões de ação e agrupamento de inputs:
* [`<x-field.button>`](#x-fieldbutton)
* [`<x-field.input-group>`](#x-fieldinput-group)

---

## `<x-field.button>`

Componente versátil de botão e link de ação. Suporta ícones com alinhamento automático, variantes Bootstrap, atalhos visuais de teclado (`<x-misc.kbd>`), disparo de rotas nomeadas com métodos HTTP (`DELETE`, `PUT`, `POST`) e confirmação interativa.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `label` | `string` | `''` | Texto exibido no botão. |
| `type` | `string` | `'button'` | Tipo HTML do botão (`'button'`, `'submit'`, `'reset'`). |
| `color` | `string\|null` | `null` | Cor do botão Bootstrap (`'primary'`, `'secondary'`, `'success'`, `'danger'`, `'warning'`, `'info'`, `'light'`, `'dark'`). |
| `size` | `string\|null` | `null` | Tamanho do botão (`'sm'`, `'lg'`). |
| `icon` | `string\|null` | `null` | Ícone a exibir (ex: `'plus'`, `'trash'`, `'bi-save'`). |
| `iconLibrary` | `string\|null` | `null` | Biblioteca do ícone (`feather`, `bootstrap`, etc.). |
| `iconColor` | `string\|null` | `null` | Cor específica para o ícone. |
| `iconClass` | `string` | `''` | Classes adicionais do ícone. |
| `iconSize` | `string\|null` | `null` | Tamanho do ícone. |
| `href` / `link` | `string\|null` | `null` | Se informado, renderiza como tag `<a>` ao invés de `<button>`. |
| `route` | `string` | `''` | Rota nomeada do Laravel a ser executada. |
| `routeMethod` | `string\|null` | `null` | Método HTTP da rota (`'DELETE'`, `'PUT'`, `'POST'`). Quando informado, gera formulário inline oculto. |
| `tooltip` / `title` | `string` | `''` | Texto do tooltip ao passar o mouse. |
| `shortcut` | `string\|null` | `null` | Tecla de atalho (ex: `'S'`, `'N'`, `'Enter'`). Exibe badge `<x-misc.kbd>`. |
| `showShortcut` | `bool` | `true` | Se `true`, renderiza visualmente o atalho dentro do botão. |
| `shortcutModifier` | `string` | `'Alt'` | Tecla modificadora do atalho (`'Alt'`, `'Ctrl'`, `'Shift'`). |
| `isDisabled` | `bool` | `false` | Se `true`, desabilita o botão. |

### Exemplos Práticos

```blade
{{-- 1. Botão de submissão com ícone e atalho de teclado Alt+S --}}
<x-field.button
    type="submit"
    label="Salvar Registro"
    icon="bi-check-lg"
    color="primary"
    shortcut="S"
/>

{{-- 2. Botão de exclusão com rota DELETE e confirmação nativa --}}
<x-field.button
    label="Excluir"
    icon="trash"
    color="danger"
    size="sm"
    route="usuarios.destroy"
    routeMethod="DELETE"
    onclick="return confirm('Tem certeza que deseja excluir este registro?')"
/>

{{-- 3. Link estilizado como botão de contorno --}}
<x-field.button
    label="Novo Chamado"
    icon="plus"
    href="{{ route('chamados.create') }}"
    color="outline-primary"
/>
```

---

## `<x-field.input-group>`

Componente de composição flexível para criar grupos de entrada com prefixos, sufixos, botões e dropdowns anexados antes (`prepend`) ou depois (`append`) do input.

### Slots e Atributos

| Slot / Atributo | Tipo | Descrição |
| :--- | :--- | :--- |
| `slot` | Slot Padrão | O elemento de entrada principal (ou múltiplos inputs). |
| `prepend` | Slot Nomeado | Conteúdo adicionado antes do input (textos, ícones, botões). |
| `append` | Slot Nomeado | Conteúdo adicionado após o input. |
| `label` | `string\|null` | Rótulo acima do grupo de entrada. |
| `required` | `bool` | Indicador visual de campo obrigatório. |
| `class` | `string` | Classes CSS adicionais aplicadas ao `.input-group`. |

### Exemplos Práticos

```blade
{{-- 1. Input Group com botão de pesquisa anexado --}}
<x-field.input-group label="Pesquisar no Catálogo">
    <input type="text" name="busca" class="form-control" placeholder="Digite termos ou código...">
    <x-slot:append>
        <button class="btn btn-primary" type="submit">
            <x-misc.icone icon="search" /> Buscar
        </button>
    </x-slot:append>
</x-field.input-group>

{{-- 2. Input com prefixo de protocolo e sufixo de domínio --}}
<x-field.input-group label="Subdomínio Corporativo">
    <x-slot:prepend>
        <span class="input-group-text">https://</span>
    </x-slot:prepend>
    
    <input type="text" name="subdominio" class="form-control" placeholder="empresa">
    
    <x-slot:append>
        <span class="input-group-text">.meusistema.com.br</span>
    </x-slot:append>
</x-field.input-group>
```
