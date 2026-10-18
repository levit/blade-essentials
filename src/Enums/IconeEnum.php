<?php

namespace Select\Components\Enums;

use Select\Components\Support\StringSupport;

enum IconeEnum: string
{
    case FEATHER = 'feather';
    case BOOTSTRAP = 'bootstrap';
    case FONTAWESOME = 'fontawesome';
    case MATERIAL = 'material';
    case IONICONS = 'ionicons';
    case REMIXICON = 'remixicon';
    case HEROICONS = 'heroicons';

    public function label(): string
    {
        return match ($this) {
            self::FEATHER => 'Feather',
            self::BOOTSTRAP => 'Bootstrap',
            self::FONTAWESOME => 'FontAwesome',
            self::MATERIAL => 'Material',
            self::IONICONS => 'IonIcons',
            self::REMIXICON => 'RemixIcon',
            self::HEROICONS => 'HeroIcons',
        };
    }

    public function site(): string
    {
        return match ($this) {
            self::FEATHER => 'https://feathericons.com/',
            self::BOOTSTRAP => 'https://icons.getbootstrap.com/',
            self::FONTAWESOME => 'https://fontawesome.com/',
            self::MATERIAL => 'https://material.io/resources/icons/',
            self::IONICONS => 'https://ionic.io/ionicons',
            self::REMIXICON => 'https://remixicon.com/',
            self::HEROICONS => 'https://heroicons.com/',
        };
    }

    public function cssFile(): string
    {
        return match ($this) {
            self::FEATHER => '',
            self::BOOTSTRAP => asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css'),
            self::FONTAWESOME => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
            self::MATERIAL => 'https://cdn.jsdelivr.net/npm/material-icons@1.13.12/iconfont/material-icons.min.css',
            self::IONICONS => '',
            self::REMIXICON => 'https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css',
            self::HEROICONS => 'https://cdn.jsdelivr.net/npm/heroicons-css@0.1.1/heroicons.min.css',
        };
    }

    public function jsFile(): string
    {
        return match ($this) {
            self::FEATHER => 'https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js',
            self::BOOTSTRAP => '',
            self::FONTAWESOME => '',
            self::MATERIAL => '',
            self::IONICONS => 'https://cdnjs.cloudflare.com/ajax/libs/ionicons/8.0.13/esm/ionicons.min.js',
            self::REMIXICON => '',
            self::HEROICONS => '',
        };
    }

    public function version(): string
    {
        return match ($this) {
            self::FEATHER => '4.29.0',
            self::BOOTSTRAP => '1.13.1',
            self::FONTAWESOME => '6.5.1',
            self::MATERIAL => '1.13.12',
            self::IONICONS => '8.0.13',
            self::REMIXICON => '4.6.0',
            self::HEROICONS => '0.1.1',
        };
    }

    public function integrity(): string
    {
        return match ($this) {
            self::FEATHER => 'sha512-24XP4a9KVoIinPFUbcnjIjAjtS59PUoxQj3GNVpWc86bCqPuy3YxAcxJrxFCxXe4GHtAumCbO2Ze2bddtuxaRw==',
            self::BOOTSTRAP => '',
            self::FONTAWESOME => 'sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==',
            self::MATERIAL => 'sha512-L11YampkUyrugx1fxE1yX+WdTy1WRSqHsIm1aaMT/YDNvOamYVq8M/INJgd4KXoHj6NLSuSWsJa+8PbqPFehbQ==',
            self::IONICONS => 'sha512-FmSOv8ykH6wXPQvMmIjbOq9VOgcm6ksJ8tm7IsPXYUeTKTck/fCdyyAxiP5HZeowzqBelKf/Q9bspX/RtRjdHg==',
            self::REMIXICON => 'sha512-XcIsjKMcuVe0Ucj/xgIXQnytNwBttJbNjltBV18IOnru2lDPe9KRRyvCXw6Y5H415vbBLRm8+q6fmLUU7DfO6Q==',
            self::HEROICONS => 'sha512-SowWVa2mz9FLbfzLdYUin0agG8E2P5aemzz6Tp+i14NTOvcSpPgi44w5QqtiAUgULo8O4zEKxFRhKEtPRxfoQA==',
        };
    }

    public function mediaType(): string
    {
        return match ($this) {
            self::FEATHER => 'javascript',
            self::BOOTSTRAP => 'stylesheet',
            self::FONTAWESOME => 'stylesheet',
            self::MATERIAL => 'stylesheet',
            self::IONICONS => 'javascript',
            self::REMIXICON => 'stylesheet',
            self::HEROICONS => 'stylesheet',
        };
    }

    public static function options(): array
    {
        return [
            self::FEATHER->value => self::FEATHER->label(),
            self::BOOTSTRAP->value => self::BOOTSTRAP->label(),
            self::FONTAWESOME->value => self::FONTAWESOME->label(),
            self::MATERIAL->value => self::MATERIAL->label(),
            self::IONICONS->value => self::IONICONS->label(),
            self::REMIXICON->value => self::REMIXICON->label(),
            self::HEROICONS->value => self::HEROICONS->label(),
        ];
    }

    public static function default(): self
    {
        return self::FEATHER;
    }

    public static function values(): array
    {
        return array_map(fn (self $status) => $status->value, self::cases());
    }

    public static function labels(): array
    {
        return array_map(fn (self $status) => $status->label(), self::cases());
    }

    /**
     * Verifica se o nome do ícone possui um prefixo de biblioteca válido.
     */
    public static function hasPrefix(string $iconName): bool
    {
        // Se a string não tem hífen, com certeza não tem prefixo.
        if (! str_contains($iconName, '-')) {
            return false;
        }

        // Pega apenas a primeira parte antes do hífen
        $prefix = explode('-', $iconName)[0];

        // Retorna true se for um prefixo que nós conhecemos, false caso contrário.
        return self::tryFromAlias($prefix) !== null;
    }

    /**
     * Extrai apenas o nome do ícone, removendo o prefixo se existir.
     */
    public static function getNameWithoutPrefix(string $iconName): string
    {
        // Usa o método que criamos anteriormente para garantir
        // que só vamos remover se for um prefixo válido
        if (self::hasPrefix($iconName)) {
            // O parâmetro '2' garante que 'fa-user-circle' vire ['fa', 'user-circle']
            $parts = explode('-', $iconName, 2);

            return $parts[1]; // Retorna apenas o que vem depois do primeiro hífen
        }

        // Se não tem prefixo, o nome informado já é o nome final
        return $iconName;
    }

    /**
     * Tenta encontrar o Enum baseado em um apelido.
     */
    public static function tryFromAlias(string $alias): ?self
    {
        return match (strtolower($alias)) {
            'fea','feather' => self::FEATHER,
            'md','material' => self::MATERIAL,
            'fa','fas','fab','far','fontawesome' => self::FONTAWESOME,
            'ri','remixicon' => self::REMIXICON,
            'bs','bi','bootstrap' => self::BOOTSTRAP,
            'ion','ionicons' => self::IONICONS,
            'hero','heroicons' => self::HEROICONS,
            default => null, // Retorna null se não encontrar nenhum
        };
    }

    /**
     * Descobre a biblioteca baseada no prefixo do ícone ou na indicação explícita.
     */
    public static function resolveLibrary(string $iconName, IconeEnum|string|null $explicitLibrary = null): self
    {
        if ($explicitLibrary instanceof self) {
            return $explicitLibrary;
        }

        if (! empty($explicitLibrary)) {
            $lib = self::tryFromAlias((string) $explicitLibrary) ?? self::tryFrom((string) $explicitLibrary);
            if ($lib !== null) {
                return $lib;
            }
        }

        if (self::hasPrefix($iconName)) {
            $prefix = explode('-', $iconName)[0];
            $lib = self::tryFromAlias($prefix);
            if ($lib !== null) {
                return $lib;
            }
        }

        return self::default();
    }

    /**
     * Traduz termos e nomes de ícones de ações e elementos comuns para Português amigável.
     */
    public static function translateAction(string $iconName): string
    {
        $clean = strtolower(self::getNameWithoutPrefix($iconName));

        return match ($clean) {
            'eye', 'eye-fill', 'show', 'view' => 'Visualizar',
            'eye-slash', 'eye-slash-fill', 'hide' => 'Ocultar',
            'pencil', 'pencil-square', 'pencil-fill', 'edit', 'edit-2', 'edit-3' => 'Editar',
            'trash', 'trash-fill', 'trash2', 'trash3', 'trash3-fill', 'delete', 'remove' => 'Excluir',
            'plus', 'plus-lg', 'plus-circle', 'plus-circle-fill', 'plus-square', 'plus-square-fill', 'add' => 'Adicionar',
            'check', 'check-lg', 'check-circle', 'check-circle-fill', 'check-square', 'check2', 'check2-circle' => 'Confirmar',
            'x', 'x-lg', 'x-circle', 'x-circle-fill', 'x-square', 'close' => 'Fechar',
            'filter', 'funnel', 'funnel-fill' => 'Filtrar',
            'search', 'magnifying-glass' => 'Pesquisar',
            'download', 'arrow-down-tray' => 'Baixar',
            'upload', 'arrow-up-tray' => 'Enviar',
            'arrow-left', 'arrow-return-left', 'chevron-left' => 'Voltar',
            'arrow-right', 'chevron-right' => 'Avançar',
            'printer', 'printer-fill' => 'Imprimir',
            'copy', 'files' => 'Copiar',
            'share', 'share-fill' => 'Compartilhar',
            'key', 'key-fill' => 'Redefinir Senha',
            'lock', 'lock-fill' => 'Bloquear',
            'unlock', 'unlock-fill' => 'Desbloquear',
            'person-check', 'person-check-fill' => 'Aprovar',
            'person-x', 'person-x-fill', 'person-dash' => 'Reprovar',
            'shield-check', 'shield-lock' => 'Segurança',
            'info-circle', 'info' => 'Informação',
            'question-circle', 'question' => 'Ajuda',
            'exclamation-triangle', 'exclamation-circle', 'alert-triangle' => 'Alerta',
            'card-checklist', 'clipboard-check' => 'Conferência',
            'file-earmark-bar-graph', 'bar-chart', 'bar-chart-2' => 'Relatório',
            default => StringSupport::humanize($clean),
        };
    }
}
