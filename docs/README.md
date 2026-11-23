# 📚 Blade Essentials - Documentação Técnica de Componentes

Bem-vindo à documentação oficial dos componentes do pacote **Blade Essentials**.
Esta documentação detalha a finalidade, a lista completa de atributos (com tipos e valores padrão) e exemplos práticos de uso para cada componente do pacote.

---

## 🧭 Estrutura da Documentação

A documentação está organizada por domínios de componentes:

### 1. [Campos de Formulário (`<x-field.*>`)](fields/overview.md)
* **[BaseFieldComponent (Atributos Comuns)](fields/overview.md)**: Atributos herdados por todos os campos (`nameField`, `label`, `required`, `value`, `model`, `form`, `readonly`, `disabled`, `onlyView`, etc.).
* **[Campos de Texto e Conteúdo](fields/inputs.md)**:
  * `<x-field.input>` - Campo de texto genérico (suporta múltiplos tipos HTML5).
  * `<x-field.textarea>` - Área de texto multilinhas redimensionável.
  * `<x-field.markdown>` - Editor Markdown com abas de Pré-visualização, Ajuda e suporte a LaTeX/KaTeX.
  * `<x-field.tags>` - Entrada de etiquetas e palavras-chave separadas por vírgula ou ponto-e-vírgula.
  * `<x-field.hidden>` - Campo oculto seguro com suporte a resolução de valor.
* **[Seleções e Opções](fields/selects.md)**:
  * `<x-field.select>` - Menu suspenso (Select simples, via array, enum, coleção ou requisição assíncrona/API).
  * `<x-field.select-checkbox>` - Dropdown multi-seleção com checkboxes, busca interna e ações de marcar todos/limpar.
  * `<x-field.checkbox>` - Caixa de seleção individual ou em lista.
  * `<x-field.radio>` - Botões de opção exclusiva.
  * `<x-field.switch>` (ou `<x-switch>`) - Interruptor visual estilo toggle switch.
* **[Campos Formatados e Máscaras](fields/masks.md)**:
  * `<x-field.money>` - Campo monetário com formatação em tempo real (R$) e precisão decimal configurável.
  * `<x-field.phone>` - Telefone fixo (8 dígitos) e celular (9 dígitos com nono dígito automático).
  * `<x-field.cep>` - Código postal com máscara e integração automática de preenchimento de endereço (ViaCEP).
  * `<x-field.cpf-cnpj>` - Validação e máscara dinâmica ou explícita de CPF (11 dígitos) e CNPJ (14 dígitos).
  * `<x-field.number>` - Entrada numérica com limites `min`, `max` e `step`.
* **[Datas e Horas](fields/dates.md)**:
  * `<x-field.datepicker>` - Seletor de data com máscara, mini calendário integrado (Flatpickr) e gatilho via ícone.
  * `<x-field.datetime>` - Campo combinado de data e hora com formatação amigável.
* **[Botões e Composição](fields/buttons.md)**:
  * `<x-field.button>` - Botão multifuncional (submit, link, atalhos de teclado `kbd`, ícones e rotas).
  * `<x-field.input-group>` - Agrupamento Bootstrap para prefixos, sufixos e botões anexados.
* **[Mídia, Redes e Dados Técnicos](fields/media-and-network.md)**:
  * `<x-field.file>` - Upload de arquivos com botão customizado, feedback de arquivo selecionado e remoção.
  * `<x-field.image>` - Upload de imagens com pré-visualização instantânea (thumbnail).
  * `<x-field.email>` - Campo de e-mail com ícone contextual e validação nativa.
  * `<x-field.url>` - Campo de endereço web / hiperlink.
  * `<x-field.ip>` - Visualização e entrada de IP com detecção de origem e atalho de geolocalização.
  * `<x-field.user-agent>` - Identificação e exibição detalhada de Sistema Operacional e Navegador.

---

### 2. [Tabelas Interativas e Datatables (`<x-datatable.*>`)](datatable/datatable.md)
* `<x-datatable.table>` - Tabela principal com suporte a paginação do Laravel, ordenação e integração com Simple-DataTables.
* `<x-datatable.header>` - Cabeçalho da tabela com classes e larguras de coluna.
* `<x-datatable.row>` - Linha de registro com alinhamento e classes utilitárias.
* `<x-datatable.cell>` - Célula com alinhamento textual, `colspan` e ordenação.
* `<x-datatable.filters>` - Barra colapsável de filtros de pesquisa rápida, status e intervalo de datas.
* `<x-datatable.actions>` - Botões padronizados de ação por linha (Visualizar, Editar, Excluir).
* `<x-datatable.empty>` (ou `<x-datatable.empty-state>`) - Estado vazio amigável quando nenhum registro é retornado.

---

### 3. [Layout, Grid e Cards (`<x-box.*>`)](box/box.md)
* `<x-box.row>` - Linha do grid Bootstrap (`.row`) com espaçamentos parametrizáveis.
* `<x-box.col>` - Coluna responsiva (`.col-*`) com suporte granular a breakpoints (`xs`, `sm`, `md`, `lg`, `xl`, `xxl`).
* `<x-box.content>` - Card corporativo completo com cabeçalho, cores temáticas, ações laterais e colapso dinâmico (expandir/recolher).

---

### 4. [Formulários Estruturados (`<x-form.*>`)](form/form.md)
* `<x-form.form>` - Elemento de formulário com injeção automática de token CSRF, suporte a upload de arquivos (`multipart/form-data`) e spoofing de método HTTP (`PUT`, `DELETE`).
* `<x-form.actions>` - Barra inferior de botões de ação (Salvar, Cancelar, Voltar) com alinhamentos automáticos.

---

### 5. [Janelas Modais (`<x-modal.*>`)](modal/modal.md)
* `<x-modal>` (ou `<x-modal.modal>`) - Container modal do Bootstrap 5 com controle de backdrop, animação fade, rolagem e centralização vertical.
* `<x-modal.content>` - Estrutura interna com cabeçalho temático, ícone, formulário embutido e botões de rodapé.

---

### 6. [Janelas e Módulos CRUD (`<x-window.*>`)](window/window.md)
* `<x-window.crud>` - Layout unificado para telas de listagem e manutenção de cadastros.
* `<x-window.header>` - Cabeçalho de página com título, subtítulo, breadcrumbs navegáveis e botões de ação superior.
* `<x-window.content>` - Container de conteúdo principal da janela.
* `<x-window.modal>` - Janela em formato modal integrada ao fluxo de trabalho CRUD.

---

### 7. [Elementos Utilitários e Auxiliares (`<x-misc.*>`)](misc/misc.md)
* `<x-misc.icone>` - Gerenciador universal de ícones com suporte a 7 bibliotecas (Bootstrap Icons, Feather, FontAwesome, Material Icons, Ionicons, RemixIcon, HeroIcons).
* `<x-misc.badge>` (ou `<x-badge>`) - Selos e emblemas com variantes de cor, formato pílula e ícones.
* `<x-misc.label>` - Rótulo de acessibilidade com indicação visual obrigatória (`*`).
* `<x-misc.kbd>` - Exibição estilizada de atalhos de teclado (ex: `Alt + S`).
* `<x-misc.link>` - Hiperlinks padronizados com controle de abertura em nova aba e ícones.
* `<x-misc.messages>` - Alertas flutuantes e mensagens flash de sessão (`success`, `error`, `warning`, `info`) com temporizador de auto-fechamento.
* `<x-misc.image>` - Imagens responsivas com suporte a miniaturas e imagens substitutas (placeholders).
* `<x-misc.ip>` - Exibição de endereços IP públicos e privados.
* `<x-misc.user-agent>` - Tag compacta identificando o navegador e SO do visitante.

---

### 8. [Mapas e Geolocalização (`<x-maps>`)](maps/maps.md)
* `<x-maps>` - Mapa interativo baseado em Leaflet e OpenStreetMap com suporte a busca por CEP/endereço, marcação de coordenadas, barra de ferramentas e sincronização bidirecional com campos do formulário.

---

## ⚡ Requisitos de Layout (Stacks Blade)

Muitos componentes do **Blade Essentials** injetam dependências de estilos e scripts através das diretivas `@push('styles')` e `@push('scripts')`.
Para que todos os componentes funcionem com perfeição, certifique-se de que o layout principal da sua aplicação (ex: `resources/views/layouts/app.blade.php`) contenha as seguintes pilhas:

```blade
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>

    <!-- CSS do Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Stack de estilos dos componentes -->
    @stack('styles')
</head>
<body class="bg-light">

    <main class="container py-4">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- JS do Bootstrap 5 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Stack de scripts dos componentes -->
    @stack('scripts')
</body>
</html>
```
