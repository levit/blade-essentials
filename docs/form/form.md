# 📝 Formulários e Ações (`<x-form.*>`)

O namespace `<x-form.*>` centraliza a criação de formulários HTML integrados às diretivas do Laravel e barras de botões de ação padronizadas.

Componentes do namespace:
* [`<x-form.form>`](#x-formform)
* [`<x-form.actions>`](#x-formactions)

---

## `<x-form.form>`

Container `<form>` que trata automaticamente:
1. Injeção do token `@csrf` do Laravel.
2. Spoofing de métodos HTTP (`@method('PUT')`, `@method('DELETE')`, `@method('PATCH')`).
3. Suporte a envio de arquivos binários (`enctype="multipart/form-data"`).

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `action` | `string` | *(Obrigatório)* | URL de destino do formulário. |
| `method` | `string` | `'POST'` | Método HTTP (`'GET'`, `'POST'`, `'PUT'`, `'DELETE'`, `'PATCH'`). Se for diferente de GET/POST, injeta a diretiva `@method()`. |
| `hasFiles` | `bool` | `false` | Se `true`, adiciona `enctype="multipart/form-data"`. |
| `autocomplete` | `string` | `'off'` | Atributo de auto-completar do navegador. |
| `enableCsrf` | `bool` | `true` | Se `true`, injeta automaticamente a diretiva `@csrf` para métodos que não sejam GET. |
| `class` | `string\|null` | `null` | Classes CSS do elemento `<form>`. |
| `id` | `string\|null` | `null` | ID HTML do formulário. |

---

## `<x-form.actions>`

Barra inferior para agrupamento e alinhamento dos botões de ação do formulário (Salvar, Cancelar, Limpar).

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `align` | `string` | `'end'` | Alinhamento dos botões: `'end'` (à direita), `'start'` (à esquerda), `'center'` ou `'between'`. |
| `class` | `string` | `''` | Classes adicionais do container. |
| `wrapperClass` | `string` | `'d-flex gap-2'` | Classes de flexbox aplicadas ao grupo de botões. |

---

## 🛠️ Exemplo Completo de Formulário com Ações

```blade
<x-form.form action="{{ route('clientes.update', $cliente->id) }}" method="PUT" :hasFiles="true">
    
    <x-box.content title="Editar Cadastro de Cliente" icon="bi-pencil-square">
        <x-box.row>
            <x-box.col sizeMd="6">
                <x-field.input nameField="nome" label="Nome Completo" :value="$cliente->nome" :required="true" />
            </x-box.col>
            <x-box.col sizeMd="6">
                <x-field.cpf-cnpj nameField="documento" label="CPF/CNPJ" :value="$cliente->documento" :required="true" />
            </x-box.col>
        </x-box.row>

        <x-box.row>
            <x-box.col sizeMd="12">
                <x-field.file nameField="anexo" label="Documento Comprobatório (PDF)" accept=".pdf" />
            </x-box.col>
        </x-box.row>
    </x-box.content>

    {{-- Barra de Ações --}}
    <x-form.actions align="end" class="mt-3">
        <x-field.button
            label="Cancelar"
            color="secondary"
            href="{{ route('clientes.index') }}"
        />
        <x-field.button
            type="submit"
            label="Salvar Alterações"
            icon="bi-check-lg"
            color="primary"
            shortcut="S"
        />
    </x-form.actions>

</x-form.form>
```
