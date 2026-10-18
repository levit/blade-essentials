<?php

declare(strict_types=1);

namespace Select\Components\Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Select\Components\Tests\TestCase;

class IpComponentTest extends TestCase
{
    public function test_renderiza_ip_publico_com_link_ipinfo(): void
    {
        $html = Blade::render('<x-misc.ip ip="200.189.113.34" />');

        $this->assertStringContainsString('https://ipinfo.io/200.189.113.34', $html);
        $this->assertStringContainsString('target="_blank"', $html);
        $this->assertStringContainsString('rel="noopener noreferrer"', $html);
        $this->assertStringContainsString('200.189.113.34', $html);
        $this->assertStringContainsString('bi-geo-alt-fill', $html);
        $this->assertStringContainsString('bi-box-arrow-up-right', $html);
    }

    public function test_renderiza_ip_local_como_servidor_sem_link_e_com_icone(): void
    {
        // IPv4 Loopback
        $htmlV4 = Blade::render('<x-misc.ip ip="127.0.0.1" />');

        $this->assertStringContainsString('127.0.0.1', $htmlV4);
        $this->assertStringContainsString('bi-server', $htmlV4);
        $this->assertStringContainsString('(Servidor)', $htmlV4);
        $this->assertStringNotContainsString('https://ipinfo.io', $htmlV4);
        $this->assertStringNotContainsString('href=', $htmlV4);
        $this->assertStringNotContainsString('bi-box-arrow-up-right', $htmlV4);

        // IPv6 Loopback
        $htmlV6 = Blade::render('<x-misc.ip ip="::1" />');

        $this->assertStringContainsString('::1', $htmlV6);
        $this->assertStringContainsString('bi-server', $htmlV6);
        $this->assertStringContainsString('(Servidor)', $htmlV6);
        $this->assertStringNotContainsString('https://ipinfo.io', $htmlV6);
        $this->assertStringNotContainsString('href=', $htmlV6);

        // Sub-rede 127.*
        $htmlSub = Blade::render('<x-misc.ip ip="127.0.0.8" />');
        $this->assertStringContainsString('bi-server', $htmlSub);
        $this->assertStringContainsString('(Servidor)', $htmlSub);
        $this->assertStringNotContainsString('href=', $htmlSub);
    }

    public function test_renderiza_ip_privado_com_icone_de_roteador(): void
    {
        $html = Blade::render('<x-misc.ip ip="192.168.1.50" />');

        $this->assertStringContainsString('192.168.1.50', $html);
        $this->assertStringContainsString('bi-router', $html);
        $this->assertStringContainsString('Rede Privada', $html);
    }

    public function test_renderiza_traco_quando_ip_for_nulo_ou_vazio(): void
    {
        $htmlNull = Blade::render('<x-misc.ip :ip="null" />');
        $this->assertStringContainsString('-', $htmlNull);
        $this->assertStringNotContainsString('https://ipinfo.io', $htmlNull);

        $htmlEmpty = Blade::render('<x-misc.ip ip="" />');
        $this->assertStringContainsString('-', $htmlEmpty);

        $htmlDash = Blade::render('<x-misc.ip ip="-" />');
        $this->assertStringContainsString('-', $htmlDash);
    }

    public function test_renderiza_como_badge(): void
    {
        $html = Blade::render('<x-misc.ip ip="8.8.8.8" as="badge" badgeColor="info" />');

        $this->assertStringContainsString('badge', $html);
        $this->assertStringContainsString('8.8.8.8', $html);
        $this->assertStringContainsString('https://ipinfo.io/8.8.8.8', $html);
    }

    public function test_renderiza_com_outro_provedor(): void
    {
        $html = Blade::render('<x-misc.ip ip="1.1.1.1" provider="ip-api" />');

        $this->assertStringContainsString('https://ip-api.com/#1.1.1.1', $html);
    }

    public function test_renderiza_ip_local_como_badge_sem_link(): void
    {
        $html = Blade::render('<x-misc.ip ip="127.0.0.1" as="badge" />');

        $this->assertStringContainsString('badge', $html);
        $this->assertStringContainsString('127.0.0.1', $html);
        $this->assertStringContainsString('(Servidor)', $html);
        $this->assertStringContainsString('bi-server', $html);
        $this->assertStringNotContainsString('href=', $html);
    }
}
