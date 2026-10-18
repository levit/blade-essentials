<?php

declare(strict_types=1);

namespace Select\Components\Components\Field;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Select\Components\Support\FormSupport;
use Select\Components\Support\StringSupport;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

/**
 * Classe Base Abstrata para Componentes de Formulário (Namespace Field).
 *
 * Centraliza o tratamento de identificadores (id), nomes em dot-notation,
 * detecção de erros de validação (ViewErrorBag), precedência de valores (old),
 * inferência de obrigatoriedade (required) e modos de exibição (readonly, disabled, onlyView).
 */
abstract class BaseFieldComponent extends Component
{
    /**
     * Identificador HTML (id) computado e sanitizado.
     */
    public ?string $id = null;

    /**
     * Nome do campo no formato dot-notation para compatibilidade com session/errors/old.
     */
    public string $dotName;

    /**
     * Valor computado e sanitizado do campo (respeitando old() quando useOldValue=true).
     */
    public mixed $resolvedValue;

    /**
     * Indica se o campo deve ser obrigatório (required).
     */
    public bool $isRequired;

    /**
     * Indica se o campo possui erro de validação associado na sessão atual.
     */
    public bool $hasError = false;

    /**
     * Mensagem de erro de validação (se houver).
     */
    public ?string $errorMessage = null;

    /**
     * Classes CSS resolvidas para o input.
     */
    public string $classes = '';

    /**
     * Construtor da classe base de formulário.
     *
     * @param string|null $nameField Nome do campo (name attribute).
     * @param string|null $name Alias para nameField.
     * @param string|null $id ID HTML personalizado (se nulo, gerado automaticamente).
     * @param string|null $label Rótulo exibido para o campo.
     * @param string $labelClass Classes CSS do rótulo.
     * @param string $inputClass Classes CSS base do elemento de entrada.
     * @param bool $useOldValue Se deve buscar valor em old() da sessão após validação.
     * @param mixed $value Valor inicial ou pré-preenchido do campo.
     * @param bool|null $required Flag explícita de obrigatoriedade (se null, inferido).
     * @param mixed $model Model Eloquent para inferência de regras.
     * @param mixed $form FormRequest ou formulário para inferência de regras.
     * @param bool $readonly Se o campo deve estar em modo somente leitura.
     * @param bool $disabled Se o campo deve estar desabilitado.
     * @param bool $onlyView Se o campo está em modo de visualização estrita (browse/show).
     */
    public function __construct(
        public ?string $nameField = null,
        public ?string $name = null,
        ?string $id = null,
        public ?string $label = null,
        public string $labelClass = 'small mb-1',
        public string $inputClass = 'form-control',
        public bool $useOldValue = true,
        public mixed $value = null,
        public ?bool $required = null,
        public mixed $model = null,
        public mixed $form = null,
        public bool $readonly = false,
        public bool $disabled = false,
        public bool $onlyView = false,
        public bool $showError = true,
    ) {
        $this->nameField = $this->nameField ?? $this->name;
        $this->name = $this->nameField;
        $resolvedRawName = (string) ($this->nameField ?? '');

        // Normalização de dot notation para arrays e matrizes (ex: 'contatos[0][email]' -> 'contatos.0.email')
        $this->dotName = trim(str_replace(['[', ']'], ['.', ''], $resolvedRawName), '.');

        // Geração do ID HTML sanitizado
        if (StringSupport::hasValue($id)) {
            $this->id = (string) $id;
        } else {
            $prefix = $this->getIdPrefix();
            $cleanSlug = Str::slug(str_replace(['[', ']', '.', ' '], '_', $resolvedRawName));
            $this->id = $cleanSlug !== '' ? "{$prefix}_{$cleanSlug}_id" : "{$prefix}_" . StringSupport::nameRandom() . '_id';
        }

        // Modo de visualização desabilita e torna somente leitura
        if ($this->onlyView) {
            $this->readonly = true;
            $this->disabled = true;
        }

        // Inferência em cascata de obrigatoriedade (FormSupport)
        $this->isRequired = ($this->readonly || $this->disabled)
            ? false
            : FormSupport::isRequired($resolvedRawName, $this->model, $this->form, $this->required);
        $this->required = $this->isRequired;

        // Resolução do valor computado
        $baseVal = $this->value;
        if ($this->useOldValue && $resolvedRawName !== '') {
            $this->resolvedValue = old($this->dotName, old($resolvedRawName, $baseVal));
        } else {
            $this->resolvedValue = $baseVal;
        }
        $this->value = $this->resolvedValue;

        // Resolução de erros de validação
        $this->resolveErrors($resolvedRawName);

        // Classes resolvidas
        $this->classes = $this->resolvedClasses();
    }

    /**
     * Retorna o prefixo padrão para o ID HTML do componente.
     */
    protected function getIdPrefix(): string
    {
        return ComponentIdPrefixEnum::INPUT->value;
    }

    /**
     * Resolve e detecta se existem erros de validação associados ao campo.
     */
    protected function resolveErrors(string $resolvedRawName): void
    {
        $errorBag = null;
        if (session()->has('errors')) {
            $errorBag = session('errors');
        } elseif (View::shared('errors')) {
            $errorBag = View::shared('errors');
        }

        if ($errorBag && is_callable([$errorBag, 'has'])) {
            if ($resolvedRawName !== '' && $errorBag->has($resolvedRawName)) {
                $this->hasError = true;
                $this->errorMessage = $errorBag->first($resolvedRawName);
            } elseif ($this->dotName !== '' && $errorBag->has($this->dotName)) {
                $this->hasError = true;
                $this->errorMessage = $errorBag->first($this->dotName);
            }
        }
    }

    /**
     * Retorna as classes CSS resolvidas para o elemento, incluindo classe de erro.
     */
    public function resolvedClasses(): string
    {
        $base = trim($this->inputClass);
        if ($this->hasError) {
            $base .= ' is-invalid';
        }

        return trim($base);
    }
}

