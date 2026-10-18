<?php

declare(strict_types=1);

namespace Select\Components\Tests\Unit;

use Select\Components\Components\Field\Input;
use Select\Components\Components\Misc\Label;
use Illuminate\Support\Facades\Blade;
use Select\Components\Tests\TestCase;

class LabelComponentTest extends TestCase
{
    /**
     * Testa renderização básica de label com atributo for e texto.
     */
    public function test_label_renders_basic_text_and_for_attribute(): void
    {
        $html = Blade::render('<x-misc.label for="input_evento-nome_id" label="Evento Vinculado" />');

        $this->assertStringContainsString('<label', $html);
        $this->assertStringContainsString('for="input_evento-nome_id"', $html);
        $this->assertStringContainsString('class="small mb-1"', $html);
        $this->assertStringContainsString('Evento Vinculado', $html);
        $this->assertStringNotContainsString('text-danger', $html);
    }

    /**
     * Testa renderização de label obrigatório exibindo o asterisco vermelho.
     */
    public function test_label_renders_required_asterisk(): void
    {
        $html = Blade::render('<x-misc.label for="input_titulo_id" label="Título do Edital" :required="true" />');

        $this->assertStringContainsString('Título do Edital', $html);
        $this->assertStringContainsString('<span class="text-danger">*</span>', $html);
    }

    /**
     * Testa renderização de label através do slot.
     */
    public function test_label_renders_via_slot(): void
    {
        $html = Blade::render('<x-misc.label for="input_custom_id">Rótulo Customizado via Slot</x-misc.label>');

        $this->assertStringContainsString('for="input_custom_id"', $html);
        $this->assertStringContainsString('Rótulo Customizado via Slot', $html);
    }

    /**
     * Testa suporte a strings numéricas como '0' garantindo que não são tratadas como vazias/falsy.
     */
    public function test_label_renders_numeric_zero_string(): void
    {
        $html = Blade::render('<x-misc.label for="input_num_id" label="0" />');

        $this->assertStringContainsString('<label', $html);
        $this->assertStringContainsString('0', $html);
    }

    /**
     * Testa que quando label é nulo ou vazio e sem slot, nenhuma tag label é renderizada.
     */
    public function test_label_renders_nothing_when_empty_and_no_slot(): void
    {
        $html = Blade::render('<x-misc.label for="input_vazio_id" :label="null" />');
        $this->assertEmpty(trim($html));

        $htmlEmpty = Blade::render('<x-misc.label for="input_vazio_id" label="" />');
        $this->assertEmpty(trim($htmlEmpty));
    }

    /**
     * Testa integração entre x-field.input e seu rótulo label semântico vinculado.
     */
    public function test_input_component_integrates_matching_label_and_input(): void
    {
        $html = Blade::render('<x-field.input nameField="evento_nome" label="Evento Vinculado" value="Concurso Chapecó" :readonly="true" />');

        $this->assertStringContainsString('<label', $html);
        $this->assertStringContainsString('for="input_evento-nome_id"', $html);
        $this->assertStringContainsString('Evento Vinculado', $html);

        $this->assertStringContainsString('<input', $html);
        $this->assertStringContainsString('id="input_evento-nome_id"', $html);
        $this->assertStringContainsString('name="evento_nome"', $html);
        $this->assertStringContainsString('value="Concurso Chapecó"', $html);
        $this->assertStringContainsString('readonly', $html);
        $this->assertStringNotContainsString('disabled', $html);
    }

    /**
     * Testa método hasLabel() na classe Label.
     */
    public function test_label_class_has_label_method(): void
    {
        $labelComTexto = new Label(label: 'Evento Vinculado');
        $this->assertTrue($labelComTexto->hasLabel());

        $labelZero = new Label(label: '0');
        $this->assertTrue($labelZero->hasLabel());

        $labelVazio = new Label(label: '');
        $this->assertFalse($labelVazio->hasLabel());

        $labelNull = new Label(label: null);
        $this->assertFalse($labelNull->hasLabel());
    }
}

