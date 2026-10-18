<?php

declare(strict_types=1);

namespace Select\Components\Tests\Unit;

use Select\Components\Components\Misc\Link;
use Illuminate\Support\Facades\Blade;
use Select\Components\Tests\TestCase;

class LinkComponentTest extends TestCase
{
    /**
     * Testa renderização básica de link com href e label.
     */
    public function test_link_renders_basic_href_and_label(): void
    {
        $html = Blade::render('<x-misc.link href="https://example.com" label="Acessar Portal" />');

        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('href="https://example.com"', $html);
        $this->assertStringContainsString('Acessar Portal', $html);
        $this->assertStringContainsString('text-decoration-none', $html);
    }

    /**
     * Testa link com href nulo não quebra e assume valor seguro padrão '#'.
     */
    public function test_link_handles_null_href_gracefully(): void
    {
        $html = Blade::render('<x-misc.link :href="null" label="Link Sem Destino" />');

        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('href="#"', $html);
        $this->assertStringContainsString('Link Sem Destino', $html);
    }

    /**
     * Testa link com target _blank computa automaticamente rel="noopener noreferrer".
     */
    public function test_link_with_blank_target_adds_secure_rel(): void
    {
        $html = Blade::render('<x-misc.link href="https://externo.com" target="_blank" label="Externo" />');

        $this->assertStringContainsString('target="_blank"', $html);
        $this->assertStringContainsString('rel="noopener noreferrer"', $html);
    }

    /**
     * Testa renderização de conteúdo do link via slot.
     */
    public function test_link_renders_via_slot(): void
    {
        $html = Blade::render('<x-misc.link href="/painel"><span>Texto do Slot</span></x-misc.link>');

        $this->assertStringContainsString('<span>Texto do Slot</span>', $html);
    }
}

