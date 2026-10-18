<?php

declare(strict_types=1);

namespace Select\Components\Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Select\Components\Tests\TestCase;

class EmailComponentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        view()->share('errors', new ViewErrorBag);
    }

    public function test_email_component_renders_default_attributes(): void
    {
        $html = Blade::render('<x-field.email nameField="email" />');

        $this->assertStringContainsString('type="email"', $html);
        $this->assertStringContainsString('id="input_email_id"', $html);
        $this->assertStringContainsString('name="email"', $html);
        $this->assertStringContainsString('placeholder="exemplo@dominio.com"', $html);
        $this->assertStringContainsString('data-field-email="true"', $html);
        $this->assertStringContainsString('data-suggest="true"', $html);
        $this->assertStringContainsString('data-strict-tld="true"', $html);
        $this->assertStringContainsString('email-suggestion-box', $html);
        $this->assertStringContainsString('Corrigir', $html);
        $this->assertStringContainsString('data-error-msg="Por favor, informe um endereço de e-mail válido."', $html);
        $this->assertStringContainsString('id="input_email_id_feedback"', $html);
        $this->assertStringContainsString('data-feedback-for="input_email_id"', $html);
        $this->assertStringContainsString('E-mail', $html);
    }

    public function test_email_component_can_disable_suggestion(): void
    {
        $html = Blade::render('<x-field.email nameField="email" :enableSuggestion="false" />');

        $this->assertStringContainsString('data-suggest="false"', $html);
        $this->assertStringNotContainsString('email-suggestion-box', $html);
    }

    public function test_email_component_renders_custom_props(): void
    {
        $html = Blade::render(
            '<x-field.email nameField="contato_email" label="E-mail de Contato" placeholder="contato@empresa.com" error-message-custom="E-mail corporativo inválido" />'
        );

        $this->assertStringContainsString('id="input_contato-email_id"', $html);
        $this->assertStringContainsString('name="contato_email"', $html);
        $this->assertStringContainsString('placeholder="contato@empresa.com"', $html);
        $this->assertStringContainsString('data-error-msg="E-mail corporativo inválido"', $html);
        $this->assertStringContainsString('E-mail de Contato', $html);
    }

    public function test_email_component_renders_required_and_value(): void
    {
        $html = Blade::render(
            '<x-field.email nameField="email" value="usuario@teste.com" :required="true" />'
        );

        $this->assertStringContainsString('value="usuario@teste.com"', $html);
        $this->assertStringContainsString('required', $html);
    }

    public function test_email_component_disabled_with_value_renders_hidden(): void
    {
        $html = Blade::render(
            '<x-field.email nameField="email" value="bloqueado@teste.com" :disabled="true" />'
        );

        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('type="hidden"', $html);
        $this->assertStringContainsString('value="bloqueado@teste.com"', $html);
    }

    public function test_email_component_readonly(): void
    {
        $html = Blade::render(
            '<x-field.email nameField="email" value="leitura@teste.com" :readonly="true" />'
        );

        $this->assertStringContainsString('readonly', $html);
        $this->assertStringContainsString('value="leitura@teste.com"', $html);
    }

    public function test_email_component_renders_with_validation_error(): void
    {
        $errorBag = new ViewErrorBag;
        $errorBag->put('default', new MessageBag([
            'email' => ['O campo de e-mail é inválido.'],
        ]));
        view()->share('errors', $errorBag);

        $html = Blade::render('<x-field.email nameField="email" />');

        $this->assertStringContainsString('is-invalid', $html);
        $this->assertStringContainsString('d-block', $html);
        $this->assertStringContainsString('O campo de e-mail é inválido.', $html);
    }
}
