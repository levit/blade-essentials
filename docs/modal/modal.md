# 🪟 Janelas Modais (`<x-modal.*>`)

O namespace `<x-modal.*>` oferece caixas de diálogo modais modernas do Bootstrap 5, com controle dinâmico de cabeçalho, animação, formulário interno e rodapé com botões de ação.

Componentes do namespace:
* [`<x-modal>` (ou `<x-modal.modal>`)](#x-modal)
* [`<x-modal.content>`](#x-modalcontent)

---

## `<x-modal>` (ou `<x-modal.modal>`)

Container modal completo do Bootstrap 5.

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `id` | `string\|null` | `null` | ID HTML do modal (usado pelo gatilho `data-bs-target="#id"`). |
| `title` | `string` | `''` | Título do cabeçalho do modal. |
| `icon` | `string\|null` | `null` | Ícone ao lado do título. |
| `iconColor` | `string\|null` | `null` | Cor do ícone do cabeçalho. |
| `color` | `string` | `'primary'` | Cor temática do cabeçalho (`'primary'`, `'danger'`, `'warning'`, etc.). |
| `size` | `string\|null` | `null` | Tamanho do modal (`'sm'`, `'lg'`, `'xl'`, `'fullscreen'`). |
| `centered` | `bool` | `true` | Centraliza verticalmente na tela (`modal-dialog-centered`). |
| `scrollable` | `bool` | `true` | Habilita rolagem interna no corpo do modal (`modal-dialog-scrollable`). |
| `static` | `bool` | `false` | Se `true`, impede fechamento ao clicar fora (`data-bs-backdrop="static"`). |
| `dismissible` | `bool` | `true` | Exibe o botão de fechar (`x`) no topo. |
| `formAction` | `string\|null` | `null` | Se informado, envolve o conteúdo do modal em um formulário com esta action. |
| `formMethod` | `string` | `'POST'` | Método HTTP do formulário embutido. |
| `submitLabel` | `string\|null` | `null` | Rótulo do botão de confirmação do rodapé. |
| `cancelLabel` | `string\|null` | `null` | Rótulo do botão de cancelamento do rodapé. |

---

## `<x-modal.content>`

Estrutura interna isolada de conteúdo modal para uso com slots customizados ou renderização AJAX.

---

## 🛠️ Exemplos Práticos

### 1. Modal Informativo Simples

```blade
{{-- Botão de abertura --}}
<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalInfo">
    <x-misc.icone icon="info-circle" /> Termos de Uso
</button>

{{-- Definição do Modal --}}
<x-modal id="modalInfo" title="Termos e Condições" icon="bi-file-text" size="lg">
    <p>Leia atentamente os termos e condições de uso da plataforma...</p>
</x-modal>
```

### 2. Modal com Formulário e Confirmação Embutidos

```blade
<x-modal
    id="modalNovoUsuario"
    title="Cadastrar Novo Usuário"
    icon="bi-person-plus"
    color="primary"
    size="md"
    formAction="{{ route('usuarios.store') }}"
    formMethod="POST"
    submitLabel="Cadastrar"
    cancelLabel="Cancelar"
>
    <x-field.input nameField="name" label="Nome Completo" :required="true" />
    <x-field.email nameField="email" label="E-mail" :required="true" class="mt-2" />
    <x-field.select nameField="perfil" label="Perfil" :options="['admin' => 'Admin', 'user' => 'Usuário']" class="mt-2" />
</x-modal>
```
