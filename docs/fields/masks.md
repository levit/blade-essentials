# 🔢 Campos Formatados e Máscaras

Esta seção documenta os componentes numéricos e com máscaras automáticas de formatação:
* [`<x-field.money>`](#x-fieldmoney)
* [`<x-field.phone>`](#x-fieldphone)
* [`<x-field.cep>`](#x-fieldcep)
* [`<x-field.cpf-cnpj>`](#x-fieldcpf-cnpj)
* [`<x-field.number>`](#x-fieldnumber)

---

## `<x-field.money>`

Componente monetário inteligente para valores em moeda (BRL/Real ou customizado). Formata valores automaticamente em tempo real durante a digitação e normaliza o dado para submissão confiável.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `prefix` | `string` | `'R$'` | Símbolo monetário exibido no prefixo do input group. |
| `decimals` | `int` | `2` | Quantidade de casas decimais permitidas (padrão 2). |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, `disabled`, `readonly`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Campo de salário ou preço padrão --}}
<x-field.money
    nameField="preco"
    label="Preço de Venda"
    value="1500.50"
    :required="true"
/>

{{-- 2. Campo com 3 casas decimais (ex: combustível ou commodities) --}}
<x-field.money
    nameField="valor_unitario"
    label="Valor por Litro"
    prefix="R$"
    :decimals="3"
    value="5.899"
/>
```

---

## `<x-field.phone>`

Campo de telefone com máscara flexível e inteligência para transição automática entre **Telefone Fixo** `(00) 0000-0000` (10 dígitos) e **Celular com Nono Dígito** `(00) 00000-0000` (11 dígitos).

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `type` | `string` | `'mobile'` | Tipo de máscara: `'mobile'` (dinâmico fixo/celular) ou `'phone'` (estrito fixo). |
| `placeholder` | `string` | `'(00) 00000-0000'` | Placeholder textual exibido no campo. |
| `maxlength` | `int\|string` | `15` | Limite de caracteres com máscara (14 para fixo, 15 para celular). |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, `disabled`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Celular com alternância automática de 8 e 9 dígitos --}}
<x-field.phone
    nameField="celular"
    label="Telefone Celular / WhatsApp"
    value="11999998888"
    :required="true"
/>

{{-- 2. Telefone fixo exclusivo --}}
<x-field.phone
    nameField="telefone_comercial"
    label="Telefone Comercial (Fixo)"
    type="phone"
    value="1133334444"
/>
```

---

## `<x-field.cep>`

Campo de CEP brasileiro com máscara automática (`00000-000`) e **preenchimento automático de endereço** integrado via serviço ViaCEP. Ao digitar o 8º número, os campos de Logradouro, Bairro, Cidade e UF são preenchidos automaticamente.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `placeholder` | `string` | `'00000-000'` | Máscara do placeholder. |
| `maxlength` | `int\|string\|null` | `9` | Limite de caracteres com o hífen. |
| `autofill` | `bool` | `true` | Se `true`, busca os dados na API e preenche os campos do formulário automaticamente. |
| `searchUrl` | `string\|null` | `null` | Endpoint alternativo de busca de CEP (padrão: ViaCEP). |
| `containerSelector` | `string\|null` | `null` | Seletor CSS do container/form onde estão os campos de endereço a preencher. |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, `disabled`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Formulário de Endereço com busca automática pelo CEP --}}
<div class="row">
    <div class="col-md-3">
        <x-field.cep
            nameField="cep"
            label="CEP"
            :autofill="true"
            :required="true"
        />
    </div>
    <div class="col-md-6">
        <x-field.input nameField="logradouro" label="Rua / Avenida" id="logradouro" />
    </div>
    <div class="col-md-3">
        <x-field.input nameField="numero" label="Número" id="numero" />
    </div>
    <div class="col-md-4">
        <x-field.input nameField="bairro" label="Bairro" id="bairro" />
    </div>
    <div class="col-md-5">
        <x-field.input nameField="cidade" label="Cidade" id="cidade" />
    </div>
    <div class="col-md-3">
        <x-field.input nameField="uf" label="UF" id="uf" />
    </div>
</div>
```

---

## `<x-field.cpf-cnpj>`

Campo inteligente para documentos fiscais brasileiros. Suporta modo unificado dinâmico (adapta a máscara de acordo com o tamanho digitado: 11 dígitos para CPF ou 14 dígitos para CNPJ) ou modo fixo explícito.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `type` | `string\|null` | `null` | Modo do documento: `'cpf'`, `'cnpj'` ou `null` (dinâmico CPF/CNPJ). |
| `tipo` | `string\|null` | `null` | Alias em português para `type`. |
| `placeholder` | `string` | *(Dinâmico)* | `'000.000.000-00'` (CPF) ou `'00.000.000/0000-00'` (CNPJ). |
| `maxlength` | `string` | *(Dinâmico)* | 14 para CPF, 18 para CNPJ. |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, `disabled`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Dinâmico: aceita tanto CPF quanto CNPJ automaticamente --}}
<x-field.cpf-cnpj
    nameField="documento"
    label="CPF ou CNPJ"
    placeholder="000.000.000-00 ou 00.000.000/0000-00"
    :required="true"
/>

{{-- 2. Modo estrito para CPF --}}
<x-field.cpf-cnpj
    nameField="cpf"
    label="CPF do Responsável"
    type="cpf"
    :value="$responsavel->cpf"
/>

{{-- 3. Modo estrito para CNPJ --}}
<x-field.cpf-cnpj
    nameField="cnpj"
    label="CNPJ da Empresa"
    type="cnpj"
    :value="$empresa->cnpj"
/>
```

---

## `<x-field.number>`

Input numérico HTML5 (`type="number"`) com controle de valores mínimos, máximos e incremento (`step`).

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `min` | `int\|float\|string\|null` | `null` | Valor numérico mínimo aceito. |
| `max` | `int\|float\|string\|null` | `null` | Valor numérico máximo aceito. |
| `step` | `int\|float\|string` | `'any'` | Passo de incremento (ex: `1`, `0.01`, `'any'`). |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, `disabled`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Quantidade inteira com limites --}}
<x-field.number
    nameField="quantidade"
    label="Quantidade de Itens"
    value="1"
    min="1"
    max="100"
    step="1"
    :required="true"
/>

{{-- 2. Percentual fracionário --}}
<x-field.number
    nameField="taxa_desconto"
    label="Taxa de Desconto (%)"
    value="5.5"
    min="0"
    max="100"
    step="0.1"
/>
```
