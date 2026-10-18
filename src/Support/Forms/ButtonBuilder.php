<?php

declare(strict_types=1);

namespace Select\Components\Support\Forms;

use Select\Components\Enums\ButtonStyleEnum;
use Select\Components\Enums\ButtonTypeEnum;
use Select\Components\Support\StringSupport;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Builder para criação, configuração fluente e renderização de botões padronizados no sistema.
 *
 * Suporta emissão em formato Array, Collection ou integração com componentes Blade de formulários.
 *
 * @property-read string $label
 * @property-read string $name
 * @property-read string $id
 * @property-read string|null $icon
 * @property-read string|null $action
 * @property-read ButtonStyleEnum $style
 * @property-read ButtonTypeEnum $type
 * @property-read string|null $tooltip
 * @property-read bool $isDisabled
 *
 * @implements Arrayable<string, mixed>
 */
class ButtonBuilder implements Arrayable
{
    private string $label;

    private string $name;

    private string $id;

    private ?string $icon;

    private ?string $action;

    private bool $usePrimaryKey = true;

    private ButtonStyleEnum $style;

    private ButtonTypeEnum $type;

    private ?string $tooltip;

    private bool $isDisabled;

    private ?string $shortcut;

    /**
     * Cria uma nova instância do ButtonBuilder.
     *
     * @param  string  $label  Texto do botão.
     * @param  string|null  $name  Nome/identificador do botão.
     * @param  string|null  $icon  Classe do ícone (ex: 'save', 'trash').
     * @param  string|null  $action  URL ou rota do botão.
     * @param  bool  $usePrimaryKey  Indica se deve usar a chave primária na rota.
     * @param  ButtonStyleEnum  $buttonStyle  Estilo visual do botão (padrão: ButtonStyleEnum::PADRAO).
     * @param  ButtonTypeEnum  $type  Tipo do botão (submit, button, link, reset).
     * @param  bool  $isDisabled  Se o botão inicia desabilitado.
     * @param  string|null  $tooltip  Texto informativo de tooltip.
     * @param  string|null  $shortcut  Tecla de atalho (ex: 's', 'v', 'Alt+S').
     */
    public function __construct(
        string $label,
        ?string $name = null,
        ?string $icon = null,
        ?string $action = null,
        bool $usePrimaryKey = true,
        ButtonStyleEnum $buttonStyle = ButtonStyleEnum::PADRAO,
        ButtonTypeEnum $type = ButtonTypeEnum::BUTTON,
        bool $isDisabled = false,
        ?string $tooltip = null,
        ?string $shortcut = null
    ) {
        $this->label = $label;
        $this->name = $name ?: StringSupport::nameRandom();
        $this->id = 'btn_'.Str::studly($this->name).'_id';
        $this->icon = $icon;
        $this->action = $action;
        $this->usePrimaryKey = $usePrimaryKey;
        $this->style = $buttonStyle;
        $this->type = $type;
        $this->isDisabled = $isDisabled;
        $this->tooltip = $tooltip;
        $this->shortcut = $shortcut;
    }

    // ========================================
    // Métodos de Conversão
    // ========================================

    /**
     * Converte o botão para array associativo (implementação do contrato Arrayable).
     *
     * @return array<string, mixed> Estrutura de dados com atributos e configurações do botão.
     */
    public function toArray(): array
    {
        return [
            'label' => $this->getLabel(),
            'name' => $this->getName(),
            'id' => $this->getId(),
            'icon' => $this->getIcon(),
            'action' => $this->getAction(),
            'usePrimaryKey' => $this->getUsePrimaryKey(),
            'style' => $this->getStyle()->getClassButton(),
            'type' => $this->getType()->value,
            'tooltip' => $this->getTooltip(),
            'isDisabled' => $this->isDisabled(),
            'shortcut' => $this->getShortcut(),
        ];
    }

    /**
     * Converte o botão para uma Collection do Laravel.
     *
     * @return Collection<string, mixed>
     */
    public function toCollection(): Collection
    {
        return collect($this->toArray());
    }

    /**
     * @deprecated Use toCollection() ou toArray().
     *
     * @return Collection<string, mixed>
     */
    public function getObject(): Collection
    {
        return $this->toCollection();
    }

    // ========================================
    // Setters Fluentes
    // ========================================

    /**
     * Define o texto visível do botão.
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Define o nome de identificação do botão.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Define o ID HTML do botão.
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Define o ícone associado ao botão.
     */
    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Define a ação ou rota do botão.
     */
    public function setAction(?string $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Define se deve anexar a chave primária na rota da ação.
     */
    public function setUsePrimaryKey(bool $usePrimaryKey): self
    {
        $this->usePrimaryKey = $usePrimaryKey;

        return $this;
    }

    /**
     * Define o estilo visual do botão.
     */
    public function setStyle(ButtonStyleEnum $buttonStyle): self
    {
        $this->style = $buttonStyle;

        return $this;
    }

    /**
     * Define o tipo funcional do botão.
     */
    public function setType(ButtonTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Define o texto explicativo de tooltip do botão.
     */
    public function setTooltip(?string $tooltip): self
    {
        $this->tooltip = $tooltip;

        return $this;
    }

    /**
     * Desabilita ou reabilita o botão.
     */
    public function setDisabled(bool $isDisabled = true): self
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }

    /**
     * Habilita ou desabilita o botão.
     */
    public function setEnabled(bool $isEnabled = true): self
    {
        $this->isDisabled = ! $isEnabled;

        return $this;
    }

    // ========================================
    // Getters
    // ========================================

    /**
     * Retorna o texto do botão.
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Retorna o nome identificador do botão.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Retorna o identificador DOM único do botão.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Retorna a classe ou nome do ícone do botão.
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Retorna a rota ou URL de ação do botão.
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * Retorna se a chave primária deve ser concatenada à ação.
     */
    public function getUsePrimaryKey(): bool
    {
        return $this->usePrimaryKey;
    }

    /**
     * Retorna o enum de estilo visual do botão.
     */
    public function getStyle(): ButtonStyleEnum
    {
        return $this->style;
    }

    /**
     * Retorna o enum de tipo funcional do botão.
     */
    public function getType(): ButtonTypeEnum
    {
        return $this->type;
    }

    /**
     * Retorna o texto de tooltip do botão.
     */
    public function getTooltip(): ?string
    {
        return $this->tooltip;
    }

    /**
     * Verifica se o botão está marcado como desabilitado.
     */
    public function isDisabled(): bool
    {
        return $this->isDisabled;
    }

    /**
     * Verifica se o botão está habilitado.
     */
    public function isEnabled(): bool
    {
        return ! $this->isDisabled;
    }

    // ========================================
    // Métodos de Verificação
    // ========================================

    /**
     * Verifica se o botão é do tipo submit de formulário.
     */
    public function isSubmit(): bool
    {
        return $this->type === ButtonTypeEnum::SUBMIT;
    }

    /**
     * Verifica se o botão é um elemento button HTML nativo.
     */
    public function isButton(): bool
    {
        return in_array($this->type, [
            ButtonTypeEnum::BUTTON,
            ButtonTypeEnum::RESET,
            ButtonTypeEnum::SUBMIT,
        ], true);
    }

    /**
     * Verifica se o botão funciona como um link de navegação.
     */
    public function isLink(): bool
    {
        return $this->type === ButtonTypeEnum::LINK;
    }

    /**
     * Verifica se o botão possui ícone configurado.
     */
    public function hasIcon(): bool
    {
        return ! empty($this->icon);
    }

    /**
     * Verifica se o botão possui ação configurada.
     */
    public function hasAction(): bool
    {
        return ! empty($this->action);
    }

    /**
     * Verifica se o botão possui tooltip descritivo.
     */
    public function hasTooltip(): bool
    {
        return ! empty($this->tooltip);
    }

    /**
     * Define ou atualiza a tecla de atalho do botão.
     */
    public function shortcut(?string $shortcut): self
    {
        $this->shortcut = $shortcut;

        return $this;
    }

    /**
     * Retorna a tecla de atalho configurada no botão.
     */
    public function getShortcut(): ?string
    {
        return $this->shortcut;
    }

    // ========================================
    // Factory Methods (Métodos Estáticos de Conveniência)
    // ========================================

    /**
     * Cria um botão padrão de submissão/salvamento (submit).
     */
    public static function submit(
        string $label = 'Salvar',
        string $action = 'store',
        string $tooltip = 'Salvar informações',
        ?string $icon = 'bi-floppy',
        ButtonStyleEnum $style = ButtonStyleEnum::SUCESSO,
        ButtonTypeEnum $type = ButtonTypeEnum::SUBMIT,
        ?string $shortcut = 's'
    ): self {
        return new self($label, null, $icon, $action, true, $style, $type, false, $tooltip, $shortcut);
    }

    /**
     * Cria um botão de cancelar/retornar.
     */
    public static function cancel(
        string $label = 'Cancelar',
        string $action = 'list',
        string $tooltip = 'Cancelar operação',
        ?string $icon = 'bi-x-circle',
        ButtonStyleEnum $style = ButtonStyleEnum::PADRAO,
        ButtonTypeEnum $type = ButtonTypeEnum::LINK,
        ?string $shortcut = 'v'
    ): self {
        return new self($label, null, $icon, $action, false, $style, $type, false, $tooltip, $shortcut);
    }

    /**
     * Cria um botão de exclusão de registro.
     */
    public static function delete(
        string $label = 'Excluir',
        string $action = 'destroy',
        ?string $icon = 'bi-trash',
        string $tooltip = 'Excluir registro',
        ButtonStyleEnum $style = ButtonStyleEnum::PERIGO,
        ButtonTypeEnum $type = ButtonTypeEnum::SUBMIT,
        ?string $shortcut = 'x'
    ): self {
        return new self($label, null, $icon, $action, true, $style, $type, false, $tooltip, $shortcut);
    }

    /**
     * Cria um botão de edição/atualização.
     */
    public static function edit(
        string $label = 'Editar',
        string $action = 'update',
        string $tooltip = 'Salvar modificações',
        ?string $icon = 'bi-pencil-square',
        ButtonStyleEnum $style = ButtonStyleEnum::AVISO,
        ButtonTypeEnum $type = ButtonTypeEnum::SUBMIT,
        ?string $shortcut = 's'
    ): self {
        return new self($label, null, $icon, $action, true, $style, $type, false, $tooltip, $shortcut);
    }

    /**
     * Cria um botão de visualização/detalhes.
     */
    public static function view(
        string $label = 'Visualizar',
        string $action = 'show',
        string $tooltip = 'Visualizar detalhes',
        ?string $icon = 'bi-eye',
        ButtonStyleEnum $style = ButtonStyleEnum::INFO,
        ButtonTypeEnum $type = ButtonTypeEnum::SUBMIT,
        ?string $shortcut = null
    ): self {
        return new self($label, null, $icon, $action, true, $style, $type, false, $tooltip, $shortcut);
    }
}

