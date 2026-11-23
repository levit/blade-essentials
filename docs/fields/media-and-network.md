# 📁 Mídia, Redes e Dados Técnicos

Esta seção documenta os componentes de uploads de arquivos e campos de rede:
* [`<x-field.file>`](#x-fieldfile)
* [`<x-field.image>`](#x-fieldimage)
* [`<x-field.email>`](#x-fieldemail)
* [`<x-field.url>`](#x-fieldurl)
* [`<x-field.ip>`](#x-fieldip)
* [`<x-field.user-agent>`](#x-fielduser-agent)

---

## `<x-field.file>`

Componente customizado de upload de arquivos com botão estilizado, nome do arquivo selecionado dinâmico e botão para remover/limpar o arquivo selecionado.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `buttonText` | `string` | `'Selecionar Arquivo'` | Texto do botão de upload. |
| `buttonIcon` | `string` | `'bi-upload'` | Ícone do botão de upload. |
| `removeText` | `string` | `'Remover'` | Texto do botão de exclusão/limpeza. |
| `removeIcon` | `string` | `'bi-trash'` | Ícone do botão de remoção. |
| `accept` | `string\|null` | `null` | Tipos MIME ou extensões aceitas (ex: `'.pdf,.doc,.docx'`). |
| `showRemove` | `bool` | `true` | Exibe o botão de limpar seleção. |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `required`, `disabled`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Upload de documento PDF --}}
<x-field.file
    nameField="curriculo"
    label="Currículo Profissional (PDF)"
    accept=".pdf"
    buttonText="Enviar PDF"
    buttonIcon="bi-file-earmark-pdf"
    :required="true"
/>
```

---

## `<x-field.image>`

Componente especializado para upload de imagens com **pré-visualização instantânea (thumbnail)** no próprio navegador antes do envio do formulário.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `accept` | `string` | `'image/*'` | Tipos de imagem aceitos. |
| `previewWidth` | `string` | `'120px'` | Largura do quadro de preview da imagem. |
| `previewHeight` | `string` | `'120px'` | Altura do quadro de preview. |
| `placeholder` | `string\|null` | `null` | URL de imagem padrão quando nenhuma imagem for selecionada. |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, `disabled`, etc. |

### Exemplos Práticos

```blade
{{-- 1. Upload de foto de perfil com preview e foto atual --}}
<x-field.image
    nameField="avatar"
    label="Foto de Perfil"
    :value="$usuario->avatar_url"
    previewWidth="150px"
    previewHeight="150px"
/>
```

---

## `<x-field.email>`

Campo de e-mail padronizado com validação HTML5 (`type="email"`), conversão para letras minúsculas e ícone contextual.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, `placeholder`, etc. |

### Exemplos Práticos

```blade
<x-field.email
    nameField="email_contato"
    label="E-mail de Notificação"
    placeholder="contato@empresa.com.br"
    :required="true"
/>
```

---

## `<x-field.url>`

Campo para inserção de links e URLs web (`type="url"`), com validação de formato e suporte a abertura rápida.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `required`, `placeholder`, etc. |

### Exemplos Práticos

```blade
<x-field.url
    nameField="website"
    label="Website Oficial"
    placeholder="https://exemplo.com.br"
/>
```

---

## `<x-field.ip>`

Campo para manipulação de endereços IPv4 ou IPv6. Identifica automaticamente se o IP é de rede privada (Loopback/Localhost) ou público da Internet, com atalho direto para consulta de geolocalização no `ipinfo.io`.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `showNetworkInfo` | `bool` | `true` | Exibe indicador se o IP é privado/servidor ou externo. |
| `showGeolocation` | `bool` | `true` | Exibe botão com link para consulta de geolocalização. |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `value`, `readonly`, etc. |

### Exemplos Práticos

```blade
<x-field.ip
    nameField="ip_origem"
    label="IP de Acesso do Usuário"
    :value="request()->ip()"
    :readonly="true"
/>
```

---

## `<x-field.user-agent>`

Componente técnico que analisa a string `User-Agent` do navegador do cliente e a decompõe visualmente em **Sistema Operacional** e **Navegador**, exibindo os respectivos ícones identificadores.

### Atributos Específicos

| Atributo | Tipo | Padrão | Descrição |
| :--- | :--- | :--- | :--- |
| `agent` / `value` | `string\|null` | `null` | A string do User-Agent a analisar (se nulo, usa `request()->userAgent()`). |
| *(Herança)* | [BaseFieldComponent](overview.md) | | `nameField`, `label`, `readonly`, etc. |

### Exemplos Práticos

```blade
<x-field.user-agent
    nameField="user_agent"
    label="Dispositivo e Navegador"
    :value="$sessao->user_agent"
/>
```
