# 🧱 Componente Base de Formulários (`BaseFieldComponent`)

Todos os componentes do namespace `<x-field.*>` (com exceção de containers estruturais como `input-group` e `button`) herdam da classe abstrata `Select\Components\Components\Field\BaseFieldComponent`.

Essa arquitetura unificada garante que qualquer campo de formulário possua recursos automáticos de:
1. **Recuperação automática de valores antigos (`old()`)** após erros de validação.
2. **Exibição automática de mensagens de erro** integradas à `ViewErrorBag` do Laravel.
3. **Geração padronizada de IDs HTML** únicos e seguros baseados no nome do campo.
4. **Inferência inteligente de obrigatoriedade (`required`)** a partir de Models Eloquent ou FormRequests.
5. **Suporte a modo de visualização estrita (`onlyView`)**, `readonly` e `disabled`.

---

## 📋 Atributos Comuns Herdados

Abaixo estão todos os atributos disponíveis em qualquer componente herdado de `BaseFieldComponent`:

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `nameField` | `string\|null` | `null` | Nome do campo enviado no formulário (atributo HTML `name`). |
| `name` | `string\|null` | `null` | Alias opcional para `nameField` (ex: `name="email"`). |
| `id` | `string\|null` | `null` | ID HTML personalizado do elemento. Se omitido, é gerado automaticamente no formato `{prefixo}_{slug}_id`. |
| `label` | `string\|null` | `''` | Texto exibido no rótulo (`<label>`) acima do campo. |
| `labelClass` | `string` | `'small mb-1'` | Classes CSS aplicadas à tag `<label>`. |
| `inputClass` | `string` | `'form-control'` | Classes CSS base do elemento de entrada (ou `'form-select'` em seletores). |
| `value` | `mixed` | `null` | Valor inicial do campo. É sobrescrito pelo valor de `old()` caso exista. |
| `useOldValue` | `bool` | `true` | Determina se o campo deve tentar recuperar o valor anterior via `old()` da sessão. |
| `required` | `bool\|null` | `null` | Define se o campo é obrigatório. Se `null`, tenta inferir das regras de validação. |
| `model` | `mixed` | `null` | Instância de Model Eloquent ou nome de classe para inferência de regras de validação. |
| `form` | `mixed` | `null` | Instância de `FormRequest` para inferência de regras de validação. |
| `readonly` | `bool` | `false` | Se `true`, impede a alteração do valor mas envia o dado no submit do formulário. |
| `disabled` | `bool` | `false` | Se `true`, desabilita o campo. Gera automaticamente um `<input type="hidden">` com o valor para garantir o envio no POST. |
| `onlyView` | `bool` | `false` | Ativa o modo de visualização (combina `readonly = true` e `disabled = true`). |
| `showError` | `bool` | `true` | Se `true`, adiciona automaticamente a classe `is-invalid` e renderiza o `<div class="invalid-feedback">`. |

---

## 💡 Mecanismos Internos

### 1. Resolução de Nomes em Dot-Notation
Campos que utilizam notação de matriz/array no HTML, como `nameField="contatos[0][telefone]"`, são automaticamente normalizados internamente para dot-notation (`contatos.0.telefone`). Isso garante compatibilidade nativa com:
- `old('contatos.0.telefone')`
- `$errors->has('contatos.0.telefone')`
- `$errors->first('contatos.0.telefone')`

### 2. Inferência de Validação Automática (`model` e `form`)
Ao informar `:model="$usuario"` ou `:form="$formRequest"`, o componente consulta o serviço `FormSupport` para inspecionar se o atributo possui a regra `required`. Se positivo, o asterisco vermelho no label e o atributo HTML `required` são adicionados automaticamente, sem necessidade de definir `:required="true"` manualmente.

### 3. Modo `onlyView` e `disabled` com Preservação de Valor
Campos nativos HTML que possuem o atributo `disabled` **não** são enviados nas requisições HTTP pelo navegador. Para resolver isso de forma transparente, o `BaseFieldComponent` injeta automaticamente:
```blade
@if($disabled && $resolvedValue)
    <input type="hidden" name="{{ $nameField }}" value="{{ $resolvedValue }}">
@endif
```
Dessa forma, campos desabilitados mantêm a consistência de dados ao serem submetidos.

---

## 🛠️ Exemplo de Uso Avançado com Herança

```blade
{{-- 1. Uso básico com validação do Laravel e recuperação de old() --}}
<x-field.input
    nameField="email"
    label="E-mail Corporativo"
    placeholder="nome@empresa.com"
/>

{{-- 2. Vinculando Model Eloquent para inferência de regras e preenchimento --}}
<x-field.input
    nameField="nome"
    label="Nome Completo"
    :value="$cliente->nome"
    :model="$cliente"
/>

{{-- 3. Modo estrito de visualização (detalhes/browse) --}}
<x-field.input
    nameField="codigo_identificacao"
    label="Código Interno"
    value="CLI-9821"
    :onlyView="true"
/>
```
