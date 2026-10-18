<?php

declare(strict_types=1);

namespace Select\Components\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ViewErrorBag;
use Select\Components\Tests\TestCase;

class FieldGroup2ComponentsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        view()->share('errors', new ViewErrorBag);
    }

    public function test_datepicker_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.datepicker nameField="data_nascimento" label="Data de Nascimento" value="2026-10-10" required="true" /> @stack("scripts")');

        $this->assertStringContainsString('id="datepicker_data-nascimento_id"', $html);
        $this->assertStringContainsString('name="data_nascimento"', $html);
        $this->assertStringContainsString('value="2026-10-10"', $html);
        $this->assertStringContainsString('data-datepicker="true"', $html);
        $this->assertStringContainsString('data-format="dd/mm/yyyy"', $html);
        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('Data de Nascimento', $html);
        $this->assertStringContainsString('flatpickr', $html);
    }

    public function test_datepicker_formats_carbon_instance(): void
    {
        $carbon = Carbon::create(2026, 12, 25);
        $html = Blade::render('<x-field.datepicker nameField="data_fim" :value="$dt" />', ['dt' => $carbon]);

        $this->assertStringContainsString('value="25/12/2026"', $html);
    }

    public function test_datepicker_disabled_with_value_renders_hidden(): void
    {
        $html = Blade::render('<x-field.datepicker nameField="data_disabled" value="2026-10-10" disabled="true" />');

        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('type="hidden"', $html);
        $this->assertStringContainsString('name="data_disabled"', $html);
        $this->assertStringContainsString('value="2026-10-10"', $html);
    }

    public function test_datetime_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.datetime nameField="criado_em" label="Criado em" value="2026-10-10 15:30:00" />');

        $this->assertStringContainsString('id="datetime_criado-em_id"', $html);
        $this->assertStringContainsString('name="criado_em"', $html);
        $this->assertStringContainsString('value="10/10/2026 15:30"', $html);
        $this->assertStringContainsString('Criado em', $html);
        $this->assertStringContainsString('d-flex align-items-center', $html);
    }

    public function test_datetime_formats_carbon_instance(): void
    {
        $carbon = Carbon::create(2026, 5, 1, 9, 15, 30);
        $html = Blade::render('<x-field.datetime nameField="evento_at" :value="$dt" format="d/m/Y H:i:s" />', ['dt' => $carbon]);

        $this->assertStringContainsString('value="01/05/2026 09:15:30"', $html);
    }

    public function test_cep_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.cep nameField="cep" label="CEP Residencial" value="88705610" /> @stack("scripts")');

        $this->assertStringContainsString('id="cep_cep_id"', $html);
        $this->assertStringContainsString('name="cep"', $html);
        $this->assertStringContainsString('placeholder="00000-000"', $html);
        $this->assertStringContainsString('maxlength="9"', $html);
        $this->assertStringContainsString('CEP Residencial', $html);
        $this->assertStringContainsString('formatCep', $html);
    }

    public function test_cpf_cnpj_component_renders_default_both(): void
    {
        $html = Blade::render('<x-field.cpf-cnpj nameField="documento" label="Documento" />');

        $this->assertStringContainsString('id="input_documento_id"', $html);
        $this->assertStringContainsString('name="documento"', $html);
        $this->assertStringContainsString('placeholder="CPF ou CNPJ"', $html);
        $this->assertStringContainsString('maxlength="18"', $html);
        $this->assertStringContainsString('data-cpf-cnpj="true"', $html);
        $this->assertStringContainsString('data-doc-type="both"', $html);
        $this->assertStringContainsString('data-feedback-for="input_documento_id"', $html);
    }

    public function test_cpf_cnpj_component_specific_cpf(): void
    {
        $html = Blade::render('<x-field.cpf-cnpj nameField="cpf" type="cpf" />');

        $this->assertStringContainsString('placeholder="000.000.000-00"', $html);
        $this->assertStringContainsString('maxlength="14"', $html);
        $this->assertStringContainsString('data-doc-type="cpf"', $html);
    }

    public function test_cpf_cnpj_component_specific_cnpj(): void
    {
        $html = Blade::render('<x-field.cpf-cnpj nameField="cnpj" type="cnpj" />');

        $this->assertStringContainsString('placeholder="00.000.000/0000-00"', $html);
        $this->assertStringContainsString('maxlength="18"', $html);
        $this->assertStringContainsString('data-doc-type="cnpj"', $html);
    }

    public function test_money_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.money nameField="salario" label="Salário Base" value="5000.00" /> @stack("scripts")');

        $this->assertStringContainsString('id="money_salario_id"', $html);
        $this->assertStringContainsString('name="salario"', $html);
        $this->assertStringContainsString('value="5000.00"', $html);
        $this->assertStringContainsString('class="input-group"', $html);
        $this->assertStringContainsString('input-group-text', $html);
        $this->assertStringContainsString('R$', $html);
        $this->assertStringContainsString('data-money="true"', $html);
        $this->assertStringContainsString('data-decimals="2"', $html);
        $this->assertStringContainsString('formatMoney', $html);
    }

    public function test_number_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.number nameField="quantidade" label="Quantidade" value="5" min="1" max="20" step="1" required="true" />');

        $this->assertStringContainsString('id="number_quantidade_id"', $html);
        $this->assertStringContainsString('name="quantidade"', $html);
        $this->assertStringContainsString('type="number"', $html);
        $this->assertStringContainsString('value="5"', $html);
        $this->assertStringContainsString('min="1"', $html);
        $this->assertStringContainsString('max="20"', $html);
        $this->assertStringContainsString('step="1"', $html);
        $this->assertStringContainsString('required', $html);
    }

    public function test_phone_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.phone nameField="telefone_celular" label="Celular" value="11987654321" /> @stack("scripts")');

        $this->assertStringContainsString('id="phone_telefone-celular_id"', $html);
        $this->assertStringContainsString('name="telefone_celular"', $html);
        $this->assertStringContainsString('type="tel"', $html);
        $this->assertStringContainsString('value="11987654321"', $html);
        $this->assertStringContainsString('placeholder="(00) 00000-0000"', $html);
        $this->assertStringContainsString('maxlength="15"', $html);
        $this->assertStringContainsString('data-phone="true"', $html);
        $this->assertStringContainsString('data-phone-type="mobile"', $html);
        $this->assertStringContainsString('formatPhone', $html);
    }

    public function test_phone_component_fixed_type(): void
    {
        $html = Blade::render('<x-field.phone nameField="telefone_fixo" type="phone" />');

        $this->assertStringContainsString('placeholder="(00) 0000-0000"', $html);
        $this->assertStringContainsString('maxlength="14"', $html);
        $this->assertStringContainsString('data-phone-type="phone"', $html);
    }

    public function test_switch_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.switch nameField="status" label="Ativo" :checked="true" />');

        $this->assertStringContainsString('form-check form-switch', $html);
        $this->assertStringContainsString('type="checkbox"', $html);
        $this->assertStringContainsString('role="switch"', $html);
        $this->assertStringContainsString('name="status"', $html);
        $this->assertStringContainsString('value="1"', $html);
        $this->assertStringContainsString('checked', $html);
        $this->assertStringContainsString('Ativo', $html);
    }

    public function test_switch_disabled_with_value_renders_hidden(): void
    {
        $html = Blade::render('<x-field.switch nameField="notificacoes" :checked="true" :disabled="true" />');

        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('type="hidden"', $html);
        $this->assertStringContainsString('name="notificacoes"', $html);
        $this->assertStringContainsString('value="1"', $html);
    }
}
