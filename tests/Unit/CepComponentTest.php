<?php

declare(strict_types=1);

namespace Select\Components\Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ViewErrorBag;
use Select\Components\Tests\TestCase;

class CepComponentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        view()->share('errors', new ViewErrorBag);
    }

    public function test_cep_component_renders_default_maxlength_of_nine(): void
    {
        $html = Blade::render('<x-field.cep nameField="cep" />');

        $this->assertStringContainsString('maxlength="9"', $html);
        $this->assertStringContainsString('placeholder="00000-000"', $html);
    }

    public function test_cep_component_renders_full_formatted_cep_value(): void
    {
        $cepCompleto = '88705-610';
        $html = Blade::render('<x-field.cep nameField="cep" :value="$cep" />', ['cep' => $cepCompleto]);

        $this->assertStringContainsString('value="88705-610"', $html);
        $this->assertStringContainsString('maxlength="9"', $html);
    }

    public function test_cep_component_allows_custom_maxlength(): void
    {
        $html = Blade::render('<x-field.cep nameField="cep" :maxlength="10" />');

        $this->assertStringContainsString('maxlength="10"', $html);
    }

    public function test_cep_component_renders_autofill_attributes_by_default(): void
    {
        $html = Blade::render('<x-field.cep nameField="cep" />');

        $this->assertStringContainsString('data-field-cep="true"', $html);
        $this->assertStringContainsString('data-autofill="true"', $html);
        $this->assertStringContainsString('data-search-url=', $html);
    }

    public function test_cep_component_allows_disabling_autofill(): void
    {
        $html = Blade::render('<x-field.cep nameField="cep" :autofill="false" />');

        $this->assertStringContainsString('data-autofill="false"', $html);
    }

    public function test_cep_component_allows_custom_search_url_and_container_selector(): void
    {
        $html = Blade::render('<x-field.cep nameField="cep" searchUrl="/custom/cep/__CEP__" containerSelector="#form-custom" />');

        $this->assertStringContainsString('data-search-url="/custom/cep/__CEP__"', $html);
        $this->assertStringContainsString('data-container-selector="#form-custom"', $html);
    }
}
