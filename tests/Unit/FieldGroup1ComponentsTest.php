<?php

declare(strict_types=1);

namespace Select\Components\Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Select\Components\Tests\TestCase;

class FieldGroup1ComponentsTest extends TestCase
{
    public function test_input_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.input nameField="usuario" label="Usuário" value="levi" required="true" placeholder="Digite seu usuário" />');

        $this->assertStringContainsString('id="input_usuario_id"', $html);
        $this->assertStringContainsString('name="usuario"', $html);
        $this->assertStringContainsString('value="levi"', $html);
        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('placeholder="Digite seu usuário"', $html);
        $this->assertStringContainsString('Usuário', $html);
    }

    public function test_input_disabled_with_value_renders_hidden(): void
    {
        $html = Blade::render('<x-field.input nameField="usuario" value="levi" disabled="true" />');

        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('type="hidden"', $html);
        $this->assertStringContainsString('value="levi"', $html);
    }

    public function test_textarea_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.textarea nameField="bio" label="Biografia" rows="5" value="Dev sênior" required="true" />');

        $this->assertStringContainsString('id="textarea_bio_id"', $html);
        $this->assertStringContainsString('name="bio"', $html);
        $this->assertStringContainsString('rows="5"', $html);
        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('>Dev sênior</textarea>', $html);
        $this->assertStringContainsString('Biografia', $html);
    }

    public function test_checkbox_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.checkbox nameField="termos" label="Aceito os termos" checked="true" switch="true" />');

        $this->assertStringContainsString('id="check_termos_id"', $html);
        $this->assertStringContainsString('name="termos"', $html);
        $this->assertStringContainsString('checked', $html);
        $this->assertStringContainsString('form-switch', $html);
        $this->assertStringContainsString('Aceito os termos', $html);
    }

    public function test_checkbox_direct_view_rendering(): void
    {
        $html = view('components.field.checkbox', [
            'id' => 'checkTodosModal',
            'checkboxClass' => 'form-check-input m-0',
            'wrapperClass' => 'd-flex justify-content-center',
            'label' => 'Selecionar todos os listados',
            'useOldValue' => false,
        ])->render();

        $this->assertStringContainsString('id="checkTodosModal"', $html);
        $this->assertStringContainsString('d-flex justify-content-center', $html);
        $this->assertStringContainsString('form-check-input m-0', $html);
        $this->assertStringContainsString('Selecionar todos os listados', $html);
    }

    public function test_radio_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.radio nameField="tipo" label="Tipo de Pessoa" :options="[\'pf\' => \'Física\', \'pj\' => \'Jurídica\']" selected="pf" inline="true" />');

        $this->assertStringContainsString('Tipo de Pessoa', $html);
        $this->assertStringContainsString('id="radio_tipo_id_pf"', $html);
        $this->assertStringContainsString('id="radio_tipo_id_pj"', $html);
        $this->assertStringContainsString('form-check-inline', $html);
        $this->assertStringContainsString('value="pf"', $html);
        $this->assertStringContainsString('checked', $html);
    }

    public function test_select_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.select nameField="uf" label="Estado" :options="[\'SP\' => \'São Paulo\', \'RJ\' => \'Rio de Janeiro\']" selected="SP" required="true" />');

        $this->assertStringContainsString('id="select_uf_id"', $html);
        $this->assertStringContainsString('name="uf"', $html);
        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('value="SP" selected', $html);
        $this->assertStringContainsString('São Paulo', $html);
    }

    public function test_select_component_readonly_mode(): void
    {
        $html = Blade::render('<x-field.select nameField="uf" label="Estado" :options="[\'SP\' => \'São Paulo\', \'RJ\' => \'Rio de Janeiro\']" selected="SP" readonly="true" />');

        $this->assertStringContainsString('readonly', $html);
        $this->assertStringContainsString('value="São Paulo"', $html);
        $this->assertStringContainsString('type="hidden" name="uf" value="SP"', $html);
    }

    public function test_select_component_dynamic_cascade_attributes_rendering(): void
    {
        $html = Blade::render('<x-field.select id="filtro_uf" nameField="uf" label="Estado" :options="[\'RO\' => \'Rondônia\']" targetUpdate="filtro_cidade" apiUrl="/api/cidades/:param" dependsOn="filtro_pais" />@stack(\'scripts\')');

        $this->assertStringContainsString('data-dynamic-select="true"', $html);
        $this->assertStringContainsString('data-target-update="filtro_cidade"', $html);
        $this->assertStringContainsString('data-api-url="/api/cidades/:param"', $html);
        $this->assertStringContainsString('data-depends-on="filtro_pais"', $html);
        $this->assertStringContainsString('buildApiUrl', $html);
        $this->assertStringContainsString('resetCascade', $html);
    }

    public function test_select_checkbox_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.select-checkbox nameField="tags" label="Tags" :options="[\'php\' => \'PHP\', \'laravel\' => \'Laravel\']" :selected="[\'php\']" />');

        $this->assertStringContainsString('id="select_chk_tags_id"', $html);
        $this->assertStringContainsString('select-checkbox-dropdown', $html);
        $this->assertStringContainsString('dropdown-menu', $html);
        $this->assertStringContainsString('PHP', $html);
        $this->assertStringContainsString('name="tags[]"', $html);
        $this->assertStringContainsString('value="php"', $html);
        $this->assertStringContainsString('checked', $html);
    }

    public function test_button_component_renders_correctly(): void
    {
        $buttonHtml = Blade::render('<x-field.button label="Salvar Registro" icon="save" color="primary" />');
        $this->assertStringContainsString('btn-primary', $buttonHtml);
        $this->assertStringContainsString('Salvar Registro', $buttonHtml);
        $this->assertStringContainsString('data-feather="save"', $buttonHtml);

        $linkHtml = Blade::render('<x-field.button label="Voltar" route="/dashboard" type="link" color="secondary" />');
        $this->assertStringContainsString('<a href="/dashboard"', $linkHtml);
        $this->assertStringContainsString('btn-secondary', $linkHtml);
        $this->assertStringContainsString('Voltar', $linkHtml);

        $closeHtml = Blade::render('<x-field.button type="close" />');
        $this->assertStringContainsString('btn-close', $closeHtml);
    }

    public function test_hidden_component_renders_correctly(): void
    {
        $htmlWithoutId = Blade::render('<x-field.hidden nameField="csrf_custom" value="token123" />');

        $this->assertStringContainsString('type="hidden"', $htmlWithoutId);
        $this->assertStringContainsString('name="csrf_custom"', $htmlWithoutId);
        $this->assertStringContainsString('value="token123"', $htmlWithoutId);
        $this->assertStringNotContainsString('id=', $htmlWithoutId);

        $htmlWithId = Blade::render('<x-field.hidden id="meu_hidden_id" nameField="csrf_custom" value="token123" />');
        $this->assertStringContainsString('id="meu_hidden_id"', $htmlWithId);
    }
}
