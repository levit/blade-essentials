# 📝 Campos de Texto e Conteúdo

Esta seção documenta os componentes de entrada de dados textuais e conteúdo rico:
* [`<x-field.input>`](#x-fieldinput)
* [`<x-field.textarea>`](#x-fieldtextarea)
* [`<x-field.markdown>`](#x-fieldmarkdown)
* [`<x-field.tags>`](#x-fieldtags)
* [`<x-field.hidden>`](#x-fieldhidden)

---

## `<x-field.input>`

O `<x-field.input>` é o componente principal para entradas de linha única. Suporta todos os tipos válidos do HTML5 (`text`, `password`, `color`, `search`, etc.), herda de `BaseFieldComponent` e permite integração fluida com validação.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `type` | `string` | `'text'` | Tipo do input HTML (`text`, `password`, `search`, `color`, etc.). |
| *(Herança)* | Todos os atributos de [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, `readonly`, `disabled`, `onlyView`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Input de texto padrão com placeholder e obrigatoriedade --}}
<x-field.input
    nameField="nome_completo"
    label="Nome Completo"
    placeholder="Ex: Maria da Silva"
    :required="true"
/>

{{-- 2. Campo de senha --}}
<x-field.input
    type="password"
    nameField="password"
    label="Senha de Acesso"
    placeholder="Mínimo 8 caracteres"
    autocomplete="new-password"
/>

{{-- 3. Campo desabilitado preservando valor --}}
<x-field.input
    nameField="matricula"
    label="Número de Matrícula"
    value="MAT-2026-99"
    :disabled="true"
/>
```

---

## `<x-field.textarea>`

Renderiza uma caixa de texto multilinha expansível com controle de linhas (`rows`) e suporte a classes de estilização.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `rows` | `int\|string` | `3` | Número inicial de linhas visíveis do textarea. |
| `textareaClass` | `string` | `'form-control'` | Classes CSS aplicadas ao textarea. |
| *(Herança)* | Todos os atributos de [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, `readonly`, `disabled`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Textarea padrão para observações --}}
<x-field.textarea
    nameField="observacoes"
    label="Observações Adicionais"
    placeholder="Escreva detalhes adicionais sobre o atendimento..."
    rows="5"
/>

{{-- 2. Textarea vinculado ao Model e somente leitura --}}
<x-field.textarea
    nameField="historico"
    label="Histórico de Ocorrências"
    :value="$chamado->historico"
    :readonly="true"
    rows="4"
/>
```

---

## `<x-field.markdown>`

Editor rico de Markdown com interface em abas (Escrever, Pré-visualização e Guia de Ajuda). Inclui renderizador em tempo real, suporte opcional a KaTeX (fórmulas matemáticas LaTeX) e contador de caracteres.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `rows` | `int\|string` | `8` | Quantidade de linhas do editor de texto. |
| `preview` | `bool` | `true` | Habilita a aba de pré-visualização renderizada em tempo real. |
| `katex` | `bool` | `false` | Carrega dinamicamente a biblioteca KaTeX e renderiza equações matemáticas (`$inline$` ou `$$bloco$$`). |
| `help` | `bool` | `true` | Exibe aba com guia rápido de sintaxe Markdown e atalhos. |
| `helpText` | `string` | `''` | Texto de ajuda contextual exibido no rodapé do componente. |
| `maxlength` | `int\|string\|null` | `null` | Limite máximo de caracteres com contador visual. |
| `placeholder` | `string` | `'Digite o texto em formato Markdown...'` | Placeholder da área de digitação. |
| *(Herança)* | Todos os atributos de [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Editor Markdown completo com preview e ajuda --}}
<x-field.markdown
    nameField="conteudo_artigo"
    label="Corpo da Notícia"
    placeholder="Utilize cabeçalhos (#), listas (*) e links..."
    rows="10"
    maxlength="5000"
    helpText="Suporte a formatação com negrito, itálico, tabelas e código."
/>

{{-- 2. Editor com suporte a fórmulas matemáticas (LaTeX/KaTeX) --}}
<x-field.markdown
    nameField="enunciado_questao"
    label="Questão de Física/Matemática"
    :katex="true"
    rows="6"
    value="A fórmula da relatividade é $$E = mc^2$$ e $\Delta = b^2 - 4ac$."
/>
```

---

## `<x-field.tags>`

Componente de inserção e gerenciamento de palavras-chave/tags. Transforma termos digitados seguidos de vírgula (`,`) ou ponto-e-vírgula (`;`) em badges visuais interativos que podem ser removidos individualmente com um clique.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `maxTags` | `int\|string\|null` | `null` | Limite máximo permitido de tags simultâneas. |
| `helpText` | `string\|null` | *(Instrução padrão)* | Texto informativo exibido abaixo das tags. |
| `placeholder` | `string\|null` | *(Placeholder padrão)* | Texto exibido no campo quando vazio. |
| *(Herança)* | Todos os atributos de [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Campo de tags com limite máximo --}}
<x-field.tags
    nameField="interesses"
    label="Áreas de Interesse"
    value="Laravel, PHP, Bootstrap, Docker"
    :maxTags="6"
/>

{{-- 2. Modo visualização de tags --}}
<x-field.tags
    nameField="habilidades"
    label="Habilidades Comprovadas"
    :value="implode(', ', $candidato->habilidades)"
    :disabled="true"
/>
```

---

## `<x-field.hidden>`

Gera um elemento `<input type="hidden">` padronizado, mantendo a resolução de valores antigos (`old()`) e integração com o ID prefixado do pacote.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `name`, `id`, `value`, `useOldValue`. |

### Exemplos Práticos

```blade
{{-- 1. Campo oculto para envio de ID ou token --}}
<x-field.hidden nameField="usuario_id" :value="$usuario->id" />

{{-- 2. Indicador de etapa ou ação do formulário --}}
<x-field.hidden nameField="action_step" value="finalizar" />
```
