<?php

declare(strict_types=1);

namespace Select\Components\Components\Form;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Select\Components\Support\StringSupport;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Formulário HTML (Form).
 *
 * Renderiza o elemento <form> encapsulando método HTTP simulado (@method),
 * proteção CSRF (@csrf), suporte a upload (enctype) e identificadores sanitizados.
 */
class Form extends Component
{
    public string $name;

    public string $id;

    public ?string $rawAction;

    public string $rawMethod;

    public string $htmlMethod;

    public bool $hasCsrf;

    public bool $isSpoofed;

    /**
     * Cria uma nova instância do componente Form.
     *
     * @param string|null $name Atributo name do formulário.
     * @param string|null $id Atributo id do formulário.
     * @param string $method Método HTTP (POST, GET, PUT, PATCH, DELETE).
     * @param string|null $enctype Tipo de codificação (ex: multipart/form-data).
     * @param string|null $action URL de submissão do formulário.
     * @param bool $needsCsrf Se deve incluir a diretiva @csrf para proteção contra CSRF.
     * @param bool|null $csrf Alias de needsCsrf para compatibilidade.
     */
    public function __construct(
        ?string $name = null,
        ?string $id = null,
        public string $method = 'POST',
        public ?string $enctype = null,
        public ?string $action = null,
        public bool $needsCsrf = true,
        public ?bool $csrf = null,
    ) {
        $this->name = $name ?? StringSupport::nameRandom();
        $this->id = StringSupport::hasValue($id)
            ? (string) $id
            : ($name !== null ? "form_{$this->name}_id" : ComponentIdPrefixEnum::FORM->generateId());

        $this->rawAction = ($this->action === '' || $this->action === '#') ? null : $this->action;
        $this->rawMethod = strtoupper((string) ($this->method ?: 'POST'));
        $this->htmlMethod = in_array($this->rawMethod, ['GET', 'POST'], true) ? $this->rawMethod : 'POST';
        $this->hasCsrf = $this->csrf ?? $this->needsCsrf;
        $this->isSpoofed = !in_array($this->rawMethod, ['GET', 'POST'], true);
    }

    /**
     * Determina se o token CSRF deve ser emitido.
     */
    public function shouldRenderCsrf(): bool
    {
        return $this->hasCsrf && $this->htmlMethod !== 'GET';
    }

    /**
     * Retorna o método HTTP original a ser emitido no spoofing (@method).
     */
    public function spoofMethod(): string
    {
        return $this->rawMethod;
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.form.form');
    }
}
