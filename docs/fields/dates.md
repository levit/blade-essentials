# 📅 Datas e Horas

Esta seção documenta os componentes de seleção temporal:
* [`<x-field.datepicker>`](#x-fielddatepicker)
* [`<x-field.datetime>`](#x-fielddatetime)

---

## `<x-field.datepicker>`

Componente completo de seleção de datas integrado ao **Flatpickr**. Oferece máscara brasileira (`dd/mm/yyyy`), suporte a instâncias `Carbon`/`DateTime`, limites de datas (`minDate` e `maxDate`) e um **botão com ícone interativo** para abrir o mini calendário com um clique.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `format` | `string` | `'dd/mm/yyyy'` | Formato visual de exibição da data. |
| `minDate` | `string\|null` | `null` | Data mínima selecionável (formato `'Y-m-d'` ou `'today'`). |
| `maxDate` | `string\|null` | `null` | Data máxima selecionável. |
| `showIcon` | `bool` | `true` | Se `true`, renderiza o campo em um `input-group` com botão de ícone lateral. |
| `icon` | `string` | `'bi-calendar3'` | Identificador do ícone a exibir no botão (suporta Bootstrap Icons, Feather, etc.). |
| `iconLibrary` | `string\|null` | `null` | Biblioteca explícita do ícone (caso não utilize prefixo). |
| `iconPosition` | `string` | `'append'` | Posição do ícone: `'append'` (à direita) ou `'prepend'` (à esquerda). |
| `iconOnly` | `bool` | `false` | Se `true`, o calendário abre **exclusivamente** ao clicar no ícone (o input fica reservado à digitação direta sem popup instantâneo). |
| `buttonClass` | `string` | `'btn btn-outline-secondary'` | Classes CSS do botão que envolve o ícone. |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, `disabled`, `readonly`, etc. |

### Tratamento Automático de Tipos no `value`
Se o valor passado para `:value` for uma instância de `DateTimeInterface` ou `Carbon\Carbon`, o componente automaticamente formata a data para `d/m/Y` antes de renderizar.

### Exemplos Práticos

```blade
{{-- 1. Datepicker padrão (abre pelo campo ou clicando no ícone à direita) --}}
<x-field.datepicker
    nameField="data_nascimento"
    label="Data de Nascimento"
    value="1995-08-20"
    :required="true"
/>

{{-- 2. Passando instância do Carbon e limitando datas --}}
<x-field.datepicker
    nameField="data_agendamento"
    label="Data da Consulta"
    :value="now()->addDays(2)"
    minDate="today"
    maxDate="2026-12-31"
/>

{{-- 3. Abertura exclusiva via clique no ícone (modo iconOnly) --}}
<x-field.datepicker
    nameField="data_admissao"
    label="Data de Admissão"
    :iconOnly="true"
    buttonClass="btn btn-primary"
/>

{{-- 4. Ícone à esquerda (prepend) e ícone customizado --}}
<x-field.datepicker
    nameField="data_evento"
    label="Dia do Evento"
    icon="bi-calendar-event"
    iconPosition="prepend"
/>

{{-- 5. Modo simples sem ícone (apenas input de data) --}}
<x-field.datepicker
    nameField="data_simples"
    label="Data"
    :showIcon="false"
/>
```

---

## `<x-field.datetime>`

Campo combinado de Data e Hora. Formata automaticamente valores de timestamp ou `Carbon` para o padrão brasileiro `dd/mm/yyyy hh:mm`.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `format` | `string` | `'dd/mm/yyyy hh:ii'` | Formato de exibição da data e hora. |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, `disabled`, `readonly`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Campo de agendamento de data e horário --}}
<x-field.datetime
    nameField="data_hora_reuniao"
    label="Data e Horário da Reunião"
    :value="now()->addHour()"
    :required="true"
/>

{{-- 2. Exibição de data/hora de criação (somente leitura) --}}
<x-field.datetime
    nameField="created_at"
    label="Registrado no Sistema em"
    :value="$registro->created_at"
    :readonly="true"
/>
```
