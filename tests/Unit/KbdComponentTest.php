<?php

declare(strict_types=1);

namespace Select\Components\Tests\Unit;

use Select\Components\Support\Forms\ButtonBuilder;
use Select\Components\Support\Forms\FormBuilder;
use Select\Components\Components\Field\Button;
use Select\Components\Components\Misc\Kbd;
use Illuminate\Support\Facades\Blade;
use Select\Components\Tests\TestCase;

class KbdComponentTest extends TestCase
{
    /**
     * Testa renderização básica do componente Kbd com atalho simples.
     */
    public function test_kbd_renders_basic_shortcut(): void
    {
        $html = Blade::render('<x-misc.kbd keys="Alt+N" />');

        $this->assertStringContainsString('<kbd', $html);
        $this->assertStringContainsString('class="kbd kbd-sm kbd-default"', $html);
        $this->assertStringContainsString('Alt+N', $html);
    }

    /**
     * Testa renderização de tecla única com modificador inferido.
     */
    public function test_kbd_renders_single_key_with_modifier(): void
    {
        $html = Blade::render('<x-misc.kbd key="s" modifier="Alt" />');

        $this->assertStringContainsString('<kbd', $html);
        $this->assertStringContainsString('Alt+S', $html);
    }

    /**
     * Testa renderização segmentada de combinação de teclas.
     */
    public function test_kbd_renders_segmented_combination(): void
    {
        $html = Blade::render('<x-misc.kbd :keys="[\'Ctrl\', \'Shift\', \'P\']" :segmented="true" />');

        $this->assertStringContainsString('class="kbd-combo', $html);
        $this->assertStringContainsString('Ctrl', $html);
        $this->assertStringContainsString('Shift', $html);
        $this->assertStringContainsString('P', $html);
        $this->assertStringContainsString('class="kbd-separator', $html);
    }

    /**
     * Testa variantes visuais e tamanhos do Kbd.
     */
    public function test_kbd_variants_and_sizes(): void
    {
        $htmlSubtle = Blade::render('<x-misc.kbd keys="Alt+N" variant="subtle" size="xs" />');
        $this->assertStringContainsString('kbd-subtle', $htmlSubtle);
        $this->assertStringContainsString('kbd-xs', $htmlSubtle);

        $htmlDark = Blade::render('<x-misc.kbd keys="Esc" variant="dark" size="md" />');
        $this->assertStringContainsString('kbd-dark', $htmlDark);
        $this->assertStringContainsString('kbd-md', $htmlDark);
    }

    /**
     * Testa renderização de botão com atalho integrado.
     */
    public function test_button_component_integrates_shortcut(): void
    {
        $html = Blade::render('<x-field.button label="Novo Registro" shortcut="n" />');

        $this->assertStringContainsString('accesskey="n"', $html);
        $this->assertStringContainsString('data-shortcut="alt+n"', $html);
        $this->assertStringContainsString('title="Novo Registro (Alt+N)"', $html);
        $this->assertStringContainsString('aria-label="Novo Registro (Alt+N)"', $html);
        $this->assertStringContainsString('<kbd class="kbd kbd-xs kbd-subtle btn-shortcut-kbd ms-2">Alt+N</kbd>', $html);
    }

    /**
     * Testa botão com atalho ativo mas sem exibição visual do Kbd.
     */
    public function test_button_component_hides_shortcut_visually_when_requested(): void
    {
        $html = Blade::render('<x-field.button label="Pesquisar" shortcut="p" :showShortcut="false" />');

        $this->assertStringContainsString('accesskey="p"', $html);
        $this->assertStringContainsString('data-shortcut="alt+p"', $html);
        $this->assertStringNotContainsString('<kbd', $html);
    }

    /**
     * Testa integração de atalhos automáticos do ButtonBuilder e FormBuilder.
     */
    public function test_button_builder_default_shortcuts_in_form_actions(): void
    {
        $btnSubmit = ButtonBuilder::submit('Salvar');
        $btnCancel = ButtonBuilder::cancel('Voltar');

        $this->assertSame('s', $btnSubmit->getShortcut());
        $this->assertSame('v', $btnCancel->getShortcut());

        $form = FormBuilder::create('Formulário', 'Título', 'cargos')->createDefaultButtons();
        $html = Blade::render('<x-form.actions :form="$form" />', ['form' => $form]);

        $this->assertStringContainsString('accesskey="s"', $html);
        $this->assertStringContainsString('data-shortcut="alt+s"', $html);
        $this->assertStringContainsString('Alt+S', $html);

        $this->assertStringContainsString('accesskey="v"', $html);
        $this->assertStringContainsString('data-shortcut="alt+v"', $html);
        $this->assertStringContainsString('Alt+V', $html);
    }
}

