<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Illuminate\Contracts\View\View;

/**
 * Componente Blade para Campo de Identificação de Navegador / Cliente (User-Agent).
 *
 * Exibe informações detalhadas e amigáveis de navegador, plataforma e dispositivo a partir da string raw de User-Agent.
 */
class UserAgent extends BaseFieldComponent
{
    /**
     * Cria uma nova instância do componente UserAgent.
     *
     * @param string|null $nameField Nome do campo no formulário (padrão 'user_agent').
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado do elemento.
     * @param string|null $label Rótulo exibido para o campo.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $inputClass Classes CSS do input.
     * @param bool $useOldValue Se deve recuperar valor da sessão (old).
     * @param mixed $value Valor inicial do cabeçalho User-Agent.
     * @param string|null $agent Alias para o valor do User-Agent.
     * @param bool|null $required Flag de obrigatoriedade explícita.
     * @param mixed $model Model Eloquent para inferência de regras.
     * @param mixed $form FormRequest para inferência de regras.
     * @param bool $readonly Se o campo é somente leitura (padrão true para auditoria).
     * @param bool $disabled Se o campo está desabilitado.
     * @param bool $onlyView Se o campo está em modo somente visualização.
     */
    public function __construct(
        ?string $nameField = 'user_agent',
        ?string $name = null,
        ?string $id = null,
        ?string $label = 'Navegador / Cliente (User-Agent)',
        string $labelClass = 'small mb-1',
        string $inputClass = '',
        bool $useOldValue = true,
        mixed $value = null,
        public ?string $agent = null,
        ?bool $required = null,
        mixed $model = null,
        mixed $form = null,
        bool $readonly = true,
        bool $disabled = false,
        bool $onlyView = false,
    ) {
        parent::__construct(
            nameField: $nameField,
            name: $name,
            id: $id,
            label: $label,
            labelClass: $labelClass,
            inputClass: $inputClass,
            useOldValue: $useOldValue,
            value: $value ?? $agent,
            required: $required,
            model: $model,
            form: $form,
            readonly: $readonly,
            disabled: $disabled,
            onlyView: $onlyView,
        );

        $rawAgent = $this->resolvedValue ?? $this->agent;
        $this->parsed = \Select\Components\Support\UserAgentSupport::parse($rawAgent);
        $this->fullText = (string) ($this->parsed['full'] ?? '');
    }

    /**
     * Informações estruturadas decodificadas do User-Agent.
     *
     * @var array<string, mixed>
     */
    public array $parsed = [
        'full' => '',
        'os' => '',
        'os_icon' => 'bi-question-circle',
        'browser' => '',
        'browser_icon' => 'bi-globe',
    ];

    /**
     * Descrição textual completa amigável do User-Agent.
     */
    public string $fullText = '';

    /**
     * Prefixo para geração de ID HTML automático.
     */
    protected function getIdPrefix(): string
    {
        return 'input';
    }

    /**
     * Renderiza a view do componente.
     */
    public function render(): View
    {
        return view('components.field.user-agent');
    }
}
