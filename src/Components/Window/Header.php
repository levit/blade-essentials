<?php

declare(strict_types=1);

namespace Select\Components\Components\Window;

use Select\Components\Enums\ComponentIdPrefixEnum;
use Select\Components\Support\StringSupport;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Componente Blade para Cabeçalho de Janela / Página (Header).
 *
 * Renderiza a barra superior da página no padrão SB Admin Pro compacto, contendo
 * ícone, título principal (caption), subtítulo, breadcrumbs e botões de ação à direita.
 */
class Header extends Component
{
    public string $name;

    public string $id;

    public bool $hasIcon;

    /**
     * @var array<int, array{label: string, url: ?string, active: bool}>
     */
    public array $resolvedBreadcrumbs;

    /**
     * Cria uma nova instância do componente Header de Janela.
     *
     * @param string|null $name Nome identificador do header.
     * @param string|null $id Identificador HTML único (se null, gerado automaticamente).
     * @param string $caption Título principal da página.
     * @param string|null $icon Ícone exibido ao lado do título.
     * @param string|null $subtitle Subtítulo contextual ou descritivo.
     * @param array $breadcrumbs Lista de itens de navegação breadcrumb: [['label' => '...', 'url' => '...']].
     * @param mixed $actions Slot ou botões de ação à direita do cabeçalho.
     */
    public function __construct(
        ?string $name = null,
        ?string $id = null,
        public string $caption = '',
        public ?string $icon = null,
        public ?string $subtitle = null,
        public array $breadcrumbs = [],
        public mixed $actions = null,
    ) {
        $this->name = $name ?? StringSupport::nameRandom();
        $this->id = StringSupport::hasValue($id)
            ? (string) $id
            : ($name !== null ? "windowHeader_{$this->name}_id" : ComponentIdPrefixEnum::WINDOW_HEADER->generateId());
        $this->hasIcon = !empty($this->icon) && trim((string) $this->icon) !== '';
        $this->resolvedBreadcrumbs = $this->resolveBreadcrumbs($this->breadcrumbs);
    }

    /**
     * Normaliza e resolve a coleção de itens de breadcrumb.
     *
     * @param array $breadcrumbs
     * @return array<int, array{label: string, url: ?string, active: bool}>
     */
    public function resolveBreadcrumbs(array $breadcrumbs): array
    {
        $resolved = [];

        foreach ($breadcrumbs as $key => $item) {
            if (is_array($item)) {
                $label = (string) ($item['label'] ?? $item['title'] ?? $key);
                $url = !empty($item['url']) ? (string) $item['url'] : (!empty($item['href']) ? (string) $item['href'] : null);
            } elseif (is_string($key) && !is_numeric($key)) {
                $label = $key;
                $url = !empty($item) ? (string) $item : null;
            } else {
                $label = (string) $item;
                $url = null;
            }

            $resolved[] = [
                'label' => $label,
                'url' => $url,
                'active' => empty($url),
            ];
        }

        return $resolved;
    }

    /**
     * Obter a visualização que representa o componente.
     */
    public function render(): View
    {
        return view('components.window.header');
    }
}
