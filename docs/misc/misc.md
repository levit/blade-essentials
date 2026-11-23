# 🧰 Elementos Utilitários e Auxiliares (`<x-misc.*>`)

O namespace `<x-misc.*>` fornece elementos visuais auxiliares e utilitários de interface:
* [`<x-misc.icone>`](#x-miscicone)
* [`<x-misc.badge>` (ou `<x-badge>`)](#x-miscbadge)
* [`<x-misc.label>`](#x-misclabel)
* [`<x-misc.kbd>`](#x-misckbd)
* [`<x-misc.link>`](#x-misclink)
* [`<x-misc.messages>`](#x-miscmessages)
* [`<x-misc.image>`](#x-miscimage)
* [`<x-misc.ip>`](#x-miscip)
* [`<x-misc.user-agent>`](#x-miscuser-agent)

---

## `<x-misc.icone>`

Gerenciador universal de ícones com detecção automática e suporte dinâmico a 7 grandes bibliotecas:
1. **Feather Icons** (padrão sem prefixo)
2. **Bootstrap Icons** (prefixo `bi-`)
3. **FontAwesome 6** (prefixo `fa-`, `fas-`, `fab-`)
4. **Material Icons** (prefixo `md-`)
5. **Ionicons** (prefixo `ion-`)
6. **RemixIcon** (prefixo `ri-`)
7. **Heroicons** (prefixo `hero-`)

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `icon` / `icone` | `string` | *(Obrigatório)* | Identificador do ícone (ex: `'calendar'`, `'bi-save'`, `'fa-user'`). |
| `library` | `string\|null` | `null` | Força a biblioteca a ser utilizada. |
| `color` | `string\|null` | `null` | Cor do ícone (`'primary'`, `'danger'`, `'muted'`, etc.). |
| `size` | `string\|null` | `null` | Tamanho do ícone (`'sm'`, `'lg'`, `'xl'`, `'2x'`). |
| `class` | `string\|null` | `null` | Classes CSS adicionais. |
| `title` / `alt` | `string\|null` | `null` | Texto de acessibilidade e tooltip. |

---

## `<x-misc.badge>` (ou `<x-badge>`)

Badge visual do Bootstrap.

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `label` | `string` | `''` | Texto do badge. |
| `color` | `string` | `'primary'` | Cor temática do badge. |
| `pill` | `bool` | `false` | Formato arredondado estilo pílula (`rounded-pill`). |
| `icon` | `string\|null` | `null` | Ícone interno exibido no badge. |
| `iconPosition` | `string` | `'left'` | Posição do ícone (`'left'` ou `'right'`). |
| `size` | `string\|null` | `null` | Tamanho customizado. |

---

## `<x-misc.label>`

Rótulo de formulário com indicação de acessibilidade e asterisco vermelho de obrigatoriedade.

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `for` | `string\|null` | `null` | ID do input referenciado. |
| `label` | `string\|null` | `null` | Texto do rótulo. |
| `required` | `bool` | `false` | Se `true`, exibe o asterisco vermelho `<span class="text-danger">*</span>`. |
| `labelClass` | `string\|null` | `'small mb-1'` | Classes CSS da tag `<label>`. |

---

## `<x-misc.kbd>`

Renderiza teclas e atalhos de teclado estilizados (`<kbd>`).

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `keys` | `string\|array` | `''` | Tecla única (`'S'`) ou sequência (`['Ctrl', 'Shift', 'P']`). |
| `separator` | `string` | `'+'` | Separador entre as teclas. |

---

## `<x-misc.link>`

Link `<a>` com controle de segurança ao abrir em nova aba (`target="_blank"` com `rel="noopener noreferrer"` automático).

---

## `<x-misc.messages>`

Exibe alertas flutuantes e mensagens flash de sessão do Laravel (`session('success')`, `session('error')`, `session('warning')`, `session('info')`) com timer para sumir automaticamente.

### Atributos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `timeout` | `int` | `7` | Segundos para o alerta fechar automaticamente (0 para não fechar). |

---

## 🛠️ Exemplos Práticos

```blade
{{-- 1. Ícones de bibliotecas diferentes --}}
<x-misc.icone icon="calendar" /> {{-- Feather --}}
<x-misc.icone icon="bi-check-circle" color="success" size="lg" /> {{-- Bootstrap --}}
<x-misc.icone icon="fa-database" color="primary" /> {{-- FontAwesome --}}

{{-- 2. Badges informativos --}}
<x-misc.badge label="Novo" color="warning" :pill="true" />
<x-misc.badge label="Aprovado" color="success" icon="bi-check" />

{{-- 3. Atalho de teclado --}}
<span>Para salvar, pressione <x-misc.kbd keys="Alt+S" /></span>

{{-- 4. Mensagens flash da sessão --}}
<x-misc.messages :timeout="5" />
```
