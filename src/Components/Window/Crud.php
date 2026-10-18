<?php

declare(strict_types=1);

namespace Select\Components\Components\Window;

use Select\Components\Support\Forms\FormBuilder;
use Select\Components\Support\FormSupport;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use InvalidArgumentException;
use Throwable;

/**
 * Componente Blade para Janela CRUD Completa (Crud).
 *
 * Envelopa a tela de formulário no layout padrão (layouts.app), configurando título,
 * cabeçalho (window.header), container de conteúdo (window.content) e formulário integrado.
 */
class Crud extends Component
{
    /**
     * Cria uma nova instância do componente Crud.
     *
     * @param FormBuilder|null $form Instância do FormBuilder associada à tela CRUD.
     * @throws InvalidArgumentException Se o model associado ao form não for uma subclasse de Model.
     */
    public function __construct(
        public ?FormBuilder $form = null,
    ) {
        if ($this->form !== null) {
            $modelClass = null;
            try {
                $modelClass = $this->form->getModelClass();
            } catch (Throwable) {
                $modelClass = null;
            }

            if ($modelClass !== null && !is_subclass_of($modelClass, Model::class)) {
                throw new InvalidArgumentException("A variável 'modelClass' do formulário deve ser uma instância de uma classe que seja extendida de Illuminate\Database\Eloquent\Model.");
            }

            FormSupport::setActiveForm($this->form);
            if ($modelClass) {
                FormSupport::setActiveModel($modelClass);
            }
        }
    }

    /**
     * Obtém o título da tela através do formulário ativo.
     */
    public function getTitle(): string
    {
        return $this->form?->getTitle() ?? '';
    }

    /**
     * Obtém o ícone do cabeçalho da janela através do formulário ativo.
     */
    public function getCaptionIcon(): ?string
    {
        return $this->form?->getCaptionIcon();
    }

    /**
     * Obtém o método HTTP através do formulário ativo.
     */
    public function getMethod(): string
    {
        try {
            return $this->form?->getMethod() ?? 'POST';
        } catch (Throwable) {
            return 'POST';
        }
    }

    /**
     * Obtém o enctype de upload através do formulário ativo.
     */
    public function getEnctype(): ?string
    {
        try {
            return $this->form?->getEnctype();
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Determina se o formulário ativo requer proteção CSRF.
     */
    public function needsCsrf(): bool
    {
        try {
            return $this->form?->needsCsrf() ?? true;
        } catch (Throwable) {
            return true;
        }
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.window.crud');
    }
}
