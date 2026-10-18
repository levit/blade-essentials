<?php

declare(strict_types=1);

namespace Select\Components\Support\Forms;

use Select\Components\Enums\ButtonTypeEnum;
use Select\Components\Enums\FormModeEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * Builder para criação de formulários padronizados
 *
 * Esta classe facilita a criação de formulários com configurações consistentes,
 * incluindo validações, botões de ação e integração com rotas do Laravel.
 *
 * @property string $title Título da página
 * @property string $caption Título do formulário
 * @property string $captionIcon Ícone do título do formulário
 * @property FormModeEnum $mode Modo do formulário (valor string do enum)
 * @property string $route URL de destino do formulário
 * @property string $enctype Tipo de codificação do formulário
 * @property array $buttons Lista de botões do formulário
 *
 * @author Levi Corrêa Tancredo
 *
 * @version 1.0.1
 */
class FormBuilder
{
    /**
     * Lista de botões do formulário
     *
     * @var ButtonBuilder[]
     */
    private array $buttons = [];

    /**
     * Cria uma nova instância do FormBuilder
     *
     * @param  string  $title  Título da página
     * @param  string  $caption  Título do formulário
     * @param  string  $captionIcon  Ícone do título do formulário (ex: 'building', 'edit')
     * @param  FormModeEnum  $mode  Modo do formulário (CREATE, EDIT, SHOW, DELETE)
     * @param  Model|null  $modelClass  Classe do modelo Eloquent associada ao formulário
     * @param  string  $baseRoute  Rota base para ações do formulário (ex: 'instituicoes')
     * @param  string  $action  Nome da ação da rota (ex: 'store', 'update')
     * @param  int|string|array|null  $primaryKey  Chave primária do registro (null para CREATE)
     * @param  string  $enctype  Tipo de codificação (padrão: application/x-www-form-urlencoded)
     *
     * @throws \InvalidArgumentException Se o método HTTP ou enctype forem inválidos
     */
    /**
     * Classe do FormRequest opcionalmente associada ao formulário para inferência de regras
     */
    private ?string $requestClass = null;

    public function __construct(
        private string $title,
        private string $caption,
        private string $captionIcon,
        private FormModeEnum $mode,
        private ?Model $modelClass,
        private string $baseRoute,
        private string $action,
        private int|string|array|null $primaryKey = null,
        private string $enctype = 'application/x-www-form-urlencoded',
        ?string $requestClass = null
    ) {
        $this->requestClass = $requestClass;
        $this->validateEnctype($enctype);
    }

    /**
     * Valida se o enctype é válido
     *
     * @param  string  $enctype  Tipo de codificação a ser validado
     *
     * @throws \InvalidArgumentException Se o enctype for inválido
     */
    private function validateEnctype(string $enctype): void
    {
        $validEnctypes = [
            'application/x-www-form-urlencoded',
            'multipart/form-data',
            'text/plain',
        ];

        if (! in_array($enctype, $validEnctypes)) {
            throw new \InvalidArgumentException("Enctype inválido: $enctype");
        }
    }

    // ========================================
    // Getters
    // ========================================

    /**
     * Retorna o título da página
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Retorna o título do formulário
     */
    public function getCaption(): string
    {
        return $this->caption;
    }

    /**
     * Retorna o ícone do título do formulário
     */
    public function getCaptionIcon(): string
    {
        return $this->captionIcon;
    }

    /**
     * Retorna o modo do formulário como enum
     */
    public function getMode(): FormModeEnum
    {
        return $this->mode;
    }

    /**
     * Retorna o valor string do modo do formulário
     */
    public function getModeValue(): string
    {
        return $this->mode->value;
    }

    /**
     * Retorna o método HTTP do formulário
     */
    public function getMethod(): string
    {
        return isset($this->mode) ? $this->mode->formMethod() : 'POST';
    }

    /**
     * Retorna a classe ou nulo do Model principal do formulário
     */
    public function getModelClass(): ?Model
    {
        return isset($this->modelClass) ? $this->modelClass : null;
    }

    /**
     * Define a classe do FormRequest associada para inferência de regras de validação
     */
    public function setRequestClass(?string $requestClass): self
    {
        $this->requestClass = $requestClass;

        return $this;
    }

    /**
     * Retorna a classe do FormRequest associada
     */
    public function getRequestClass(): ?string
    {
        return $this->requestClass;
    }

    /**
     * Resolve se um campo é obrigatório para este formulário
     */
    public function isRequired(string $field, ?bool $explicit = null): bool
    {
        return \App\Support\FormSupport::isRequired($field, $this->modelClass, $this, $explicit);
    }

    /**
     * Retorna a rota base do formulário
     */
    public function getBaseRoute(): string
    {
        return $this->baseRoute;
    }

    /**
     * Retorna a ação do formulário
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Retorna a ação do formulário
     */
    public function getRoute(): string
    {
        if (is_null($this->getBaseRoute()) || empty($this->getBaseRoute())) {
            throw new \InvalidArgumentException('Necessário definir a base de URL para construção da rota.');
        }

        if (is_null($this->getAction()) || empty($this->getAction())) {
            throw new \InvalidArgumentException('Necessário definir a ação para construção da rota.');
        }

        $route = $this->getBaseRoute().'.'.$this->getAction();

        if ($this->getPrimaryKey() === null) {
            return route($route);
        }

        return route($route, $this->getPrimaryKey());
    }

    /**
     * Retorna a chave primária do registro
     */
    public function getPrimaryKey(): int|string|array|null
    {
        return isset($this->primaryKey) ? $this->primaryKey : null;
    }

    /**
     * Retorna o tipo de codificação do formulário
     */
    public function getEnctype(): string
    {
        return $this->enctype;
    }

    /**
     * Retorna a lista de botões do formulário
     *
     * @return ButtonBuilder[]
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    // ========================================
    // Setters
    // ========================================

    /**
     * Define o título da página
     *
     * @param  string  $title  Título da página
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Define o título do formulário
     *
     * @param  string  $caption  Título do formulário
     */
    public function setCaption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Define o ícone do título do formulário
     *
     * @param  string  $captionIcon  Ícone do título do formulário
     */
    public function setCaptionIcon(string $captionIcon): self
    {
        $this->captionIcon = $captionIcon;

        return $this;
    }

    /**
     * Define o modo do formulário
     *
     * @param  FormModeEnum  $mode  Modo do formulário
     */
    public function setMode(FormModeEnum $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Define a classe do modelo Eloquent
     *
     * @param  Model|null  $modelClass  Classe do modelo
     */
    public function setModelClass(?Model $modelClass): self
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    /**
     * Define a rota base do formulário
     *
     * @param  string  $baseRoute  Rota base
     */
    public function setBaseRoute(string $baseRoute): self
    {
        $this->baseRoute = $baseRoute;

        return $this;
    }

    /**
     * Define a ação do formulário
     *
     * @param  string  $action  Ação do formulário
     */
    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Define a chave primária do registro
     *
     * @param  int|string|array|null  $primaryKey  Chave primária
     */
    public function setPrimaryKey(int|string|array|null $primaryKey): self
    {
        $this->primaryKey = $primaryKey;

        return $this;
    }

    /**
     * Define o tipo de codificação do formulário
     *
     * @param  string  $enctype  Tipo de codificação
     *
     * @throws \InvalidArgumentException Se o enctype for inválido
     */
    public function setEnctype(string $enctype): self
    {
        $this->validateEnctype($enctype);
        $this->enctype = $enctype;

        return $this;
    }

    // ========================================
    // Gerenciamento de Botões
    // ========================================

    /**
     * Adiciona um botão ao formulário
     *
     * @param  ButtonBuilder  $button  Instância do botão a ser adicionado
     * @return self Retorna a própria instância para encadeamento fluente
     */
    public function addButton(ButtonBuilder $button): self
    {
        if ($button->getType() === ButtonTypeEnum::SUBMIT) {
            if ($button->getAction() !== null && empty($button->getAction()) === false) {
                $this->action = $button->getAction();
            }
        }

        $this->buttons[] = $button;

        return $this;
    }

    /**
     * Adiciona múltiplos botões de uma vez
     *
     * @param  ButtonBuilder  ...$buttons  Lista variável de botões
     * @return self Retorna a própria instância para encadeamento fluente
     *
     * @example
     * $formBuilder->addButtons(
     *     ButtonBuilder::submit('Salvar'),
     *     ButtonBuilder::cancel('Cancelar')
     * );
     */
    public function addButtons(ButtonBuilder ...$buttons): self
    {
        foreach ($buttons as $button) {
            $this->addButton($button);
        }

        return $this;
    }

    /**
     * Remove todos os botões do formulário
     *
     * @return self Retorna a própria instância para encadeamento fluente
     */
    public function clearButtons(): self
    {
        $this->buttons = [];

        return $this;
    }

    // ========================================
    // Métodos de Verificação de Modo
    // ========================================

    /**
     * Verifica se o formulário está em modo de criação
     */
    public function isCreateMode(): bool
    {
        return $this->mode === FormModeEnum::CREATE;
    }

    /**
     * Verifica se o formulário está em modo de edição
     */
    public function isEditMode(): bool
    {
        return $this->mode === FormModeEnum::EDIT;
    }

    /**
     * Verifica se o formulário está em modo de visualização
     */
    public function isShowMode(): bool
    {
        return $this->mode === FormModeEnum::SHOW;
    }

    /**
     * Verifica se o formulário está em modo de exclusão
     */
    public function isDeleteMode(): bool
    {
        return $this->mode === FormModeEnum::DELETE;
    }

    /**
     * Verifica se o formulário está em modo somente leitura (SHOW ou DELETE)
     */
    public function isReadOnly(): bool
    {
        return $this->isShowMode() || $this->isDeleteMode();
    }

    /**
     * Verifica se o formulário está em modo de criação (alias para isCreateMode)
     */
    public function isCreate(): bool
    {
        return $this->isCreateMode();
    }

    /**
     * Verifica se o formulário está em modo de edição (alias para isEditMode)
     */
    public function isEdit(): bool
    {
        return $this->isEditMode();
    }

    /**
     * Verifica se o formulário está em modo de visualização (alias para isShowMode)
     */
    public function isShow(): bool
    {
        return $this->isShowMode();
    }

    /**
     * Verifica se o formulário está em modo de exclusão (alias para isDeleteMode)
     */
    public function isDelete(): bool
    {
        return $this->isDeleteMode();
    }

    /**
     * Verifica se o formulário precisa de token CSRF (@csrf)
     *
     * Todos os métodos exceto GET precisam de proteção CSRF
     */
    public function needsCsrf(): bool
    {
        return $this->mode->formMethod() != 'GET';
    }

    // ========================================
    // Factory Methods (Métodos Estáticos)
    // ========================================

    /**
     * Cria um formulário de criação (CREATE)
     *
     * @param  string  $title  Título da página
     * @param  string  $caption  Título do formulário
     * @param  string  $baseRoute  Rota base (ex: 'instituicoes')
     * @param  string  $icon  Ícone do formulário (padrão: 'plus-circle')
     * @return self Nova instância configurada para criação
     *
     * @example
     * $form = FormBuilder::create(
     *     'Nova Instituição',
     *     'Cadastro de Instituição',
     *     'instituicoes'
     * );
     */
    public static function create(string $title, string $caption, string $baseRoute, string $icon = 'plus-circle'): self
    {
        return new self(
            $title,
            $caption,
            $icon,
            FormModeEnum::CREATE,
            null,
            $baseRoute,
            'store'
        );
    }

    /**
     * Cria um formulário de edição (EDIT)
     *
     * @param  string  $title  Título da página
     * @param  string  $caption  Título do formulário
     * @param  string  $baseRoute  Rota base (ex: 'instituicoes')
     * @param  int|string|array  $primaryKey  ID do registro a ser editado
     * @param  string  $icon  Ícone do formulário (padrão: 'edit')
     * @return self Nova instância configurada para edição
     *
     * @example
     * $form = FormBuilder::edit(
     *     'Editar Instituição',
     *     'Atualização de Dados',
     *     'instituicoes',
     *     5
     * );
     */
    public static function edit(string $title, string $caption, Model $modelClass, string $baseRoute, int|string|array $primaryKey, string $icon = 'edit'): self
    {
        return new self(
            $title,
            $caption,
            $icon,
            FormModeEnum::EDIT,
            $modelClass,
            $baseRoute,
            'update',
            $primaryKey
        );
    }

    /**
     * Cria um formulário de visualização (SHOW)
     *
     * @param  string  $title  Título da página
     * @param  string  $caption  Título do formulário
     * @param  string  $baseRoute  Rota base (ex: 'instituicoes')
     * @param  int|string|array  $primaryKey  ID do registro a ser visualizado
     * @param  string  $icon  Ícone do formulário (padrão: 'eye')
     * @return self Nova instância configurada para visualização
     *
     * @example
     * $form = FormBuilder::show(
     *     'Visualizar Instituição',
     *     'Detalhes da Instituição',
     *     'instituicoes',
     *     5
     * );
     */
    public static function show(string $title, string $caption, Model $modelClass, string $baseRoute, int|string|array $primaryKey, string $icon = 'eye'): self
    {
        return new self(
            $title,
            $caption,
            $icon,
            FormModeEnum::SHOW,
            $modelClass,
            $baseRoute,
            'show',
            $primaryKey
        );
    }

    /**
     * Cria um formulário de exclusão (DELETE)
     *
     * @param  string  $title  Título da página
     * @param  string  $caption  Título do formulário
     * @param  string  $baseRoute  Rota base (ex: 'instituicoes')
     * @param  int|string|array  $primaryKey  ID do registro a ser excluído
     * @param  string  $icon  Ícone do formulário (padrão: 'trash')
     * @return self Nova instância configurada para exclusão
     *
     * @example
     * $form = FormBuilder::delete(
     *     'Excluir Instituição',
     *     'Confirmação de Exclusão',
     *     'instituicoes',
     *     5
     * );
     */
    public static function delete(string $title, string $caption, Model $modelClass, string $baseRoute, int|string|array $primaryKey, string $icon = 'trash'): self
    {
        return new self(
            $title,
            $caption,
            $icon,
            FormModeEnum::DELETE,
            $modelClass,
            $baseRoute,
            'destroy',
            $primaryKey
        );
    }

    // ========================================
    // Métodos de Botões Padrão
    // ========================================

    /**
     * Adiciona os botões padrão para formulário de criação
     *
     * Botões: [Salvar] [Voltar]
     *
     * @return self Retorna a própria instância para encadeamento fluente
     */
    public function createDefaultButtons(): self
    {
        return $this->addButtons(
            ButtonBuilder::submit('Salvar'),
            ButtonBuilder::cancel('Voltar')
        );
    }

    /**
     * Adiciona os botões padrão para formulário de edição
     *
     * Botões: [Atualizar] [Voltar]
     *
     * @return self Retorna a própria instância para encadeamento fluente
     */
    public function editDefaultButtons(): self
    {
        return $this->addButtons(
            ButtonBuilder::edit('Atualizar'),
            ButtonBuilder::cancel('Voltar')
        );
    }

    /**
     * Adiciona os botões padrão para formulário de visualização
     *
     * Botões: [Voltar]
     *
     * @return self Retorna a própria instância para encadeamento fluente
     */
    public function showDefaultButtons(): self
    {
        return $this->addButton(
            ButtonBuilder::cancel('Voltar')
        );
    }

    /**
     * Adiciona os botões padrão para formulário de exclusão
     *
     * Botões: [Excluir] [Voltar]
     *
     * @return self Retorna a própria instância para encadeamento fluente
     */
    public function deleteDefaultButtons(): self
    {
        return $this->addButtons(
            ButtonBuilder::delete('Excluir'),
            ButtonBuilder::cancel('Voltar')
        );
    }

    // ========================================
    // Magic Methods
    // ========================================

    /**
     * Permite acesso às propriedades privadas como se fossem públicas (somente leitura)
     *
     * Este método permite acessar propriedades usando $formBuilder->title
     * em vez de $formBuilder->getTitle()
     *
     * @param  string  $name  Nome da propriedade
     * @return mixed Valor da propriedade ou null se não existir
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'mode' => $this->mode->value,
            'title', 'caption', 'captionIcon', 'route', 'enctype', 'buttons', 'modelClass', 'baseRoute', 'action', 'primaryKey' => $this->$name,
            default => null
        };
    }

    /**
     * Verifica se uma propriedade existe
     *
     * @param  string  $name  Nome da propriedade
     */
    public function __isset(string $name): bool
    {
        return property_exists($this, $name);
    }

    /**
     * Trata chamadas dinâmicas a métodos de conveniência ou aliases
     *
     * @param  string  $name  Nome do método chamado
     * @param  array  $arguments  Argumentos passados
     *
     * @throws \BadMethodCallException Se o método não existir
     */
    public function __call(string $name, array $arguments): mixed
    {
        return match ($name) {
            'isShow' => $this->isShowMode(),
            'isCreate' => $this->isCreateMode(),
            'isEdit' => $this->isEditMode(),
            'isDelete' => $this->isDeleteMode(),
            default => throw new \BadMethodCallException(sprintf('Call to undefined method %s::%s()', static::class, $name)),
        };
    }
}
