<?php

declare(strict_types=1);

namespace Select\Components\Support;

/**
 * Classe de Suporte para parsing e identificação de User-Agents HTTP.
 *
 * Extrai Sistema Operacional, Navegador Web, ferramentas de CLI/API e bots/crawlers,
 * mapeando automaticamente os ícones correspondentes do Bootstrap Icons.
 */
class UserAgentSupport
{
    /**
     * Analisa a string de User-Agent e extrai metadados do Sistema Operacional e Navegador,
     * incluindo nomes amigáveis e classes de ícones Bootstrap Icons correspondentes.
     *
     * @return array{
     *     os: string,
     *     os_icon: string,
     *     browser: string,
     *     browser_icon: string,
     *     full: string,
     *     is_bot: bool,
     *     is_cli: bool
     * }
     */
    public static function parse(?string $userAgent): array
    {
        $raw = trim((string) $userAgent);

        if ($raw === '' || $raw === 'N/A' || $raw === '-') {
            return [
                'os' => 'Desconhecido',
                'os_icon' => 'bi-pc',
                'browser' => 'Desconhecido',
                'browser_icon' => 'bi-globe',
                'full' => $raw !== '' ? $raw : 'N/A',
                'is_bot' => false,
                'is_cli' => false,
            ];
        }

        $osData = static::detectOs($raw);
        $browserData = static::detectBrowser($raw);

        return [
            'os' => $osData['name'],
            'os_icon' => $osData['icon'],
            'browser' => $browserData['name'],
            'browser_icon' => $browserData['icon'],
            'full' => $raw,
            'is_bot' => $osData['is_bot'] || $browserData['is_bot'],
            'is_cli' => $osData['is_cli'] || $browserData['is_cli'],
        ];
    }

    /**
     * Detecta o Sistema Operacional e seu ícone Bootstrap Icon associado.
     *
     * @return array{name: string, icon: string, is_bot: bool, is_cli: bool}
     */
    public static function detectOs(string $ua): array
    {
        // 1. Bots / Spiders / Rastreadores
        if (preg_match('/(Googlebot|bingbot|Baiduspider|YandexBot|DuckDuckBot|Slurp|facebookexternalhit|Twitterbot|LinkedInBot|bot|spider|crawl)/i', $ua, $m)) {
            return [
                'name' => 'Bot / Rastreador ('.$m[1].')',
                'icon' => 'bi-robot',
                'is_bot' => true,
                'is_cli' => false,
            ];
        }

        // 2. Clientes HTTP / APIs / Scripts de linha de comando
        if (preg_match('/(PostmanRuntime|Postman|curl|Wget|GuzzleHttp|python-requests|Insomnia|axios|Apache-HttpClient|Go-http-client)/i', $ua, $m)) {
            return [
                'name' => 'Cliente API / CLI ('.$m[1].')',
                'icon' => 'bi-terminal',
                'is_bot' => false,
                'is_cli' => true,
            ];
        }

        // 3. Apple iOS (iPhone / iPad / iPod)
        if (preg_match('/iPhone/i', $ua)) {
            return ['name' => 'iOS (iPhone)', 'icon' => 'bi-apple', 'is_bot' => false, 'is_cli' => false];
        }
        if (preg_match('/iPad/i', $ua)) {
            return ['name' => 'iPadOS (iPad)', 'icon' => 'bi-apple', 'is_bot' => false, 'is_cli' => false];
        }
        if (preg_match('/iPod/i', $ua)) {
            return ['name' => 'iOS (iPod)', 'icon' => 'bi-apple', 'is_bot' => false, 'is_cli' => false];
        }

        // 4. Apple macOS
        if (preg_match('/(Macintosh|Mac OS X|macOS)/i', $ua)) {
            return ['name' => 'macOS', 'icon' => 'bi-apple', 'is_bot' => false, 'is_cli' => false];
        }

        // 5. Android
        if (preg_match('/Android(?:\s+([\d.]+))?/i', $ua, $m)) {
            $version = ! empty($m[1]) ? ' '.$m[1] : '';

            return ['name' => 'Android'.$version, 'icon' => 'bi-android2', 'is_bot' => false, 'is_cli' => false];
        }

        // 6. Windows
        if (preg_match('/Windows NT 10\.0/i', $ua)) {
            return ['name' => 'Windows 10/11', 'icon' => 'bi-windows', 'is_bot' => false, 'is_cli' => false];
        }
        if (preg_match('/Windows NT 6\.3/i', $ua)) {
            return ['name' => 'Windows 8.1', 'icon' => 'bi-windows', 'is_bot' => false, 'is_cli' => false];
        }
        if (preg_match('/Windows NT 6\.2/i', $ua)) {
            return ['name' => 'Windows 8', 'icon' => 'bi-windows', 'is_bot' => false, 'is_cli' => false];
        }
        if (preg_match('/Windows NT 6\.1/i', $ua)) {
            return ['name' => 'Windows 7', 'icon' => 'bi-windows', 'is_bot' => false, 'is_cli' => false];
        }
        if (preg_match('/Windows/i', $ua)) {
            return ['name' => 'Windows', 'icon' => 'bi-windows', 'is_bot' => false, 'is_cli' => false];
        }

        // 7. Linux & Distros
        if (preg_match('/Ubuntu/i', $ua)) {
            return ['name' => 'Ubuntu Linux', 'icon' => 'bi-ubuntu', 'is_bot' => false, 'is_cli' => false];
        }
        if (preg_match('/CrOS/i', $ua)) {
            return ['name' => 'ChromeOS', 'icon' => 'bi-laptop', 'is_bot' => false, 'is_cli' => false];
        }
        if (preg_match('/(Linux|X11)/i', $ua)) {
            return ['name' => 'Linux', 'icon' => 'bi-tux', 'is_bot' => false, 'is_cli' => false];
        }

        // 8. BSD
        if (preg_match('/(FreeBSD|OpenBSD|NetBSD)/i', $ua, $m)) {
            return ['name' => $m[1], 'icon' => 'bi-pc', 'is_bot' => false, 'is_cli' => false];
        }

        return [
            'name' => 'SO Desconhecido',
            'icon' => 'bi-pc',
            'is_bot' => false,
            'is_cli' => false,
        ];
    }

    /**
     * Detecta o Navegador Web / Cliente e seu ícone Bootstrap Icon associado.
     *
     * @return array{name: string, icon: string, is_bot: bool, is_cli: bool}
     */
    public static function detectBrowser(string $ua): array
    {
        // 1. Bots / Spiders
        if (preg_match('/(Googlebot|bingbot|Baiduspider|YandexBot|DuckDuckBot|Slurp|bot|spider|crawl)/i', $ua, $m)) {
            return [
                'name' => 'Bot ('.$m[1].')',
                'icon' => 'bi-robot',
                'is_bot' => true,
                'is_cli' => false,
            ];
        }

        // 2. Ferramentas CLI / API
        if (preg_match('/(PostmanRuntime|Postman|curl|Wget|GuzzleHttp|python-requests|Insomnia|axios)/i', $ua, $m)) {
            return [
                'name' => $m[1],
                'icon' => 'bi-terminal',
                'is_bot' => false,
                'is_cli' => true,
            ];
        }

        // 3. Microsoft Edge (Edg/ ou Edge/)
        if (preg_match('/(?:Edg|Edge)\/([\d.]+)/i', $ua, $m)) {
            $major = explode('.', $m[1])[0];

            return [
                'name' => "Microsoft Edge {$major}",
                'icon' => 'bi-browser-edge',
                'is_bot' => false,
                'is_cli' => false,
            ];
        }

        // 4. Opera / Opera GX (OPR/ ou Opera/)
        if (preg_match('/(?:OPR|Opera)\/([\d.]+)/i', $ua, $m)) {
            $major = explode('.', $m[1])[0];

            return [
                'name' => "Opera {$major}",
                'icon' => 'bi-globe',
                'is_bot' => false,
                'is_cli' => false,
            ];
        }

        // 5. Vivaldi
        if (preg_match('/Vivaldi\/([\d.]+)/i', $ua, $m)) {
            $major = explode('.', $m[1])[0];

            return [
                'name' => "Vivaldi {$major}",
                'icon' => 'bi-globe',
                'is_bot' => false,
                'is_cli' => false,
            ];
        }

        // 6. Brave
        if (preg_match('/Brave\/([\d.]+)/i', $ua, $m)) {
            $major = explode('.', $m[1])[0];

            return [
                'name' => "Brave {$major}",
                'icon' => 'bi-browser-chrome',
                'is_bot' => false,
                'is_cli' => false,
            ];
        }

        // 7. Samsung Internet
        if (preg_match('/SamsungBrowser\/([\d.]+)/i', $ua, $m)) {
            $major = explode('.', $m[1])[0];

            return [
                'name' => "Samsung Internet {$major}",
                'icon' => 'bi-browser-chrome',
                'is_bot' => false,
                'is_cli' => false,
            ];
        }

        // 8. Mozilla Firefox (Firefox/ ou FxiOS/)
        if (preg_match('/(?:Firefox|FxiOS)\/([\d.]+)/i', $ua, $m)) {
            $major = explode('.', $m[1])[0];

            return [
                'name' => "Mozilla Firefox {$major}",
                'icon' => 'bi-browser-firefox',
                'is_bot' => false,
                'is_cli' => false,
            ];
        }

        // 9. Google Chrome / Chromium (Chrome/ ou CriOS/)
        // Deve vir após Edge, Opera, Vivaldi, Brave, SamsungBrowser
        if (preg_match('/(?:Chrome|CriOS)\/([\d.]+)/i', $ua, $m)) {
            $major = explode('.', $m[1])[0];

            return [
                'name' => "Google Chrome {$major}",
                'icon' => 'bi-browser-chrome',
                'is_bot' => false,
                'is_cli' => false,
            ];
        }

        // 10. Apple Safari (Version/X.X Safari/)
        // Deve vir após Chrome, pois o Chrome também inclui a substring Safari no User-Agent
        if (preg_match('/Version\/([\d.]+).*Safari/i', $ua, $m)) {
            $major = explode('.', $m[1])[0];

            return [
                'name' => "Apple Safari {$major}",
                'icon' => 'bi-browser-safari',
                'is_bot' => false,
                'is_cli' => false,
            ];
        }

        // 11. Internet Explorer (MSIE ou Trident/)
        if (preg_match('/(?:MSIE\s+([\d.]+)|Trident\/.*rv:([\d.]+))/i', $ua, $m)) {
            $ver = ! empty($m[1]) ? $m[1] : ($m[2] ?? '');
            $major = explode('.', $ver)[0];

            return [
                'name' => "Internet Explorer {$major}",
                'icon' => 'bi-browser-edge',
                'is_bot' => false,
                'is_cli' => false,
            ];
        }

        return [
            'name' => 'Navegador Web',
            'icon' => 'bi-globe',
            'is_bot' => false,
            'is_cli' => false,
        ];
    }
}
