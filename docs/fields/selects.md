# 🔘 Seleções e Opções

Esta seção documenta os componentes de escolha e múltipla seleção:
* [`<x-field.select>`](#x-fieldselect)
* [`<x-field.select-checkbox>`](#x-fieldselect-checkbox)
* [`<x-field.checkbox>`](#x-fieldcheckbox)
* [`<x-field.radio>`](#x-fieldradio)
* [`<x-field.switch>` (ou `<x-switch>`)](#x-fieldswitch)

---

## `<x-field.select>`

Componente versátil de `<select>`. Suporta opções a partir de arrays simples associativos, coleções do Laravel, enums nativos do PHP ou **carregamento dinâmico via API assíncrona** com suporte a dependências hierárquicas (ex: Estado -> Cidade).

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `options` | `mixed` | `[]` | Lista de opções (Array `['id' => 'Label']`, Collection ou Enum PHP). |
| `selected` | `mixed` | `null` | Valor pré-selecionado (ou array de valores se `multiple=true`). |
| `placeholder` | `string\|null` | `'Selecione uma opção'` | Texto da opção inicial vazia. |
| `emptyMessage` | `string` | `'Nenhum item disponível'` | Mensagem caso a lista de opções esteja vazia. |
| `apiUrl` | `string\|null` | `null` | Endpoint URL para busca assíncrona de dados via `fetch()`. |
| `urlParam` | `string\|null` | `null` | Nome do parâmetro de busca enviado à API. |
| `dependsOn` | `string\|null` | `null` | ID ou seletor do campo pai que aciona o recarregamento deste select. |
| `targetUpdate` | `string\|null` | `null` | Seletor de outro campo filho que deve ser recarregado ao mudar este. |
| `loadOnInit` | `bool` | `true` | Se `true`, dispara a requisição da API na inicialização da página. |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, `disabled`, `readonly`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Select simples com array associativo --}}
<x-field.select
    nameField="status"
    label="Status do Pedido"
    :options="[
        'pendente' => 'Pendente',
        'pago' => 'Pago',
        'cancelado' => 'Cancelado'
    ]"
    selected="pendente"
    :required="true"
/>

{{-- 2. Select carregado a partir de Enum PHP --}}
<x-field.select
    nameField="tipo_usuario"
    label="Perfil de Acesso"
    :options="\App\Enums\TipoUsuarioEnum::cases()"
/>

{{-- 3. Select encadeado dependente de outro campo (Estado e Cidade via API) --}}
<x-field.select
    id="estado_id"
    nameField="estado_id"
    label="Estado"
    :options="$estados"
    targetUpdate="#cidade_id"
/>

<x-field.select
    id="cidade_id"
    nameField="cidade_id"
    label="Cidade"
    apiUrl="/api/estados/{value}/cidades"
    dependsOn="#estado_id"
    :loadOnInit="false"
/>
```

---

## `<x-field.select-checkbox>`

Menu suspenso moderno com múltiplas opções selecionáveis via checkbox, barra de pesquisa integrada para filtrar itens e botões de atalho ("Selecionar Todos" e "Limpar").

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `options` | `mixed` | `[]` | Array associativo de opções `['valor' => 'Rótulo']` ou coleção. |
| `selected` | `mixed` | `[]` | Array de valores inicialmente marcados. |
| `placeholder` | `string` | `'Selecione as opções'` | Texto exibido no botão quando nenhum item estiver marcado. |
| `maxDisplay` | `int` | `2` | Número máximo de itens listados no texto do botão antes de exibir "X selecionados". |
| `allSelectedText` | `string` | `'Todos selecionados'` | Texto exibido quando todos os itens estiverem marcados. |
| `showActions` | `bool` | `true` | Exibe os botões rápidos "Selecionar Todos" e "Limpar". |
| `selectAllLabel` | `string` | `'Selecionar Todos'` | Texto do botão de selecionar tudo. |
| `clearLabel` | `string` | `'Limpar'` | Texto do botão de desmarcar tudo. |
| `searchable` | `bool\|null` | `null` | Exibe campo de busca no topo do dropdown (automático para listas longas). |
| `searchPlaceholder` | `string` | `'Buscar opção...'` | Placeholder do input de busca. |
| `menuMaxHeight` | `string` | `'260px'` | Altura máxima do menu com scroll vertical. |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `required`, `disabled`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Múltipla seleção com pesquisa interna --}}
<x-field.select-checkbox
    nameField="setores[]"
    label="Setores com Permissão de Acesso"
    :options="[
        'financeiro' => 'Financeiro',
        'rh' => 'Recursos Humanos',
        'ti' => 'Tecnologia da Informação',
        'compras' => 'Compras',
        'juridico' => 'Jurídico',
        'marketing' => 'Marketing'
    ]"
    :selected="['financeiro', 'ti']"
    :searchable="true"
    maxDisplay="3"
/>
```

---

## `<x-field.checkbox>`

Renderiza caixas de seleção individuais do Bootstrap (`form-check`). Trata automaticamente o valor `1`/`0` ou valores customizados.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `checked` | `mixed` | `false` | Define se o checkbox está inicialmente marcado (`true`, `false`, `1`, `'1'`). |
| `value` | `mixed` | `'1'` | Valor submetido quando a caixa estiver marcada. |
| `inline` | `bool` | `false` | Alinha o checkbox em linha com outros (`form-check-inline`). |
| `switch` | `bool` | `false` | Se `true`, estiliza como switch toggle. |
| `checkboxClass` | `string` | `'form-check-input'` | Classes adicionais do input do checkbox. |
| `wrapperClass` | `string` | `'form-check'` | Classes do container do checkbox. |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `disabled`, `readonly`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Checkbox individual de aceite de termos --}}
<x-field.checkbox
    nameField="aceite_termos"
    label="Li e concordo com os Termos de Uso e Política de Privacidade"
    value="1"
    :checked="old('aceite_termos', false)"
    :required="true"
/>

{{-- 2. Checkboxes inline --}}
<div class="d-flex gap-3">
    <x-field.checkbox nameField="dias[]" label="Segunda" value="seg" :inline="true" />
    <x-field.checkbox nameField="dias[]" label="Terça" value="ter" :inline="true" />
    <x-field.checkbox nameField="dias[]" label="Quarta" value="qua" :inline="true" />
</div>
```

---

## `<x-field.radio>`

Renderiza botões de opção exclusivos para seleção de um único valor entre várias alternativas.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `options` | `array` | `[]` | Array associativo de opções `['valor' => 'Rótulo']`. |
| `selected` | `mixed` | `null` | Valor pré-selecionado. |
| `inline` | `bool` | `false` | Alinha os botões horizontalmente (`form-check-inline`). |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `required`, `disabled`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Seleção única horizontal (inline) --}}
<x-field.radio
    nameField="tipo_pessoa"
    label="Tipo de Pessoa"
    :options="[
        'F' => 'Pessoa Física',
        'J' => 'Pessoa Jurídica'
    ]"
    selected="F"
    :inline="true"
/>
```

---

## `<x-field.switch>` (ou `<x-switch>`)

Interruptor visual baseado no controle `form-switch` do Bootstrap 5. Suporta tamanhos e preservação de valores quando desabilitado.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `checked` | `bool\|string\|int\|null` | `false` | Define se o switch está ativado (`true`, `1`, `'1'`). |
| `value` | `mixed` | `'1'` | Valor enviado no POST quando ativado. |
| `size` | `string\|null` | `null` | Tamanho customizado (ex: `'lg'`). |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `disabled`, `readonly`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Switch de ativação padrão --}}
<x-field.switch
    nameField="ativo"
    label="Cadastro Ativo no Sistema"
    :checked="$usuario->ativo"
/>

{{-- 2. Switch com tamanho grande e desabilitado --}}
<x-field.switch
    nameField="notificacoes_email"
    label="Notificações por E-mail Obrigatórias"
    :checked="true"
    :disabled="true"
    size="lg"
/>
```
