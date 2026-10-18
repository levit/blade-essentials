<?php

declare(strict_types=1);

namespace Select\Components\Tests\Unit;

use Select\Components\Enums\BadgeColorEnum;
use Select\Components\Enums\BadgeSizeEnum;
use Select\Components\Enums\BadgeVariantEnum;
use Select\Components\Enums\BoletoStatusEnum;
use Select\Components\Enums\MessageTypeEnum;
use Illuminate\Support\Facades\Blade;
use Select\Components\Tests\TestCase;

class BadgeComponentTest extends TestCase
{
    /**
     * Testa renderização básica via slot com cor padrão e soft badge.
     */
    public function test_renderiza_badge_com_slot_e_cor_padrao(): void
    {
        $html = Blade::render('<x-badge>Texto do Badge</x-badge>');

        $this->assertStringContainsString('badge', $html);
        $this->assertStringContainsString('bg-secondary-soft', $html);
        $this->assertStringContainsString('text-secondary', $html);
        $this->assertStringContainsString('rounded', $html);
        $this->assertStringContainsString('Texto do Badge', $html);
    }

    /**
     * Testa renderização através de x-misc.badge diretamente.
     */
    public function test_renderiza_badge_via_namespace_misc(): void
    {
        $html = Blade::render('<x-misc.badge text="Misc Badge" color="info" />');

        $this->assertStringContainsString('badge', $html);
        $this->assertStringContainsString('bg-info-soft', $html);
        $this->assertStringContainsString('text-info', $html);
        $this->assertStringContainsString('Misc Badge', $html);
    }

    /**
     * Testa suporte às props text, label e value.
     */
    public function test_renderiza_badge_com_props_text_label_value(): void
    {
        $htmlText = Blade::render('<x-badge text="Prop Text" />');
        $this->assertStringContainsString('Prop Text', $htmlText);

        $htmlLabel = Blade::render('<x-badge label="Prop Label" />');
        $this->assertStringContainsString('Prop Label', $htmlLabel);

        $htmlValue = Blade::render('<x-badge value="Prop Value" />');
        $this->assertStringContainsString('Prop Value', $htmlValue);
    }

    /**
     * Testa cores via string e via Enum BadgeColorEnum.
     */
    public function test_renderiza_badge_com_cores_via_string_e_enum(): void
    {
        $htmlString = Blade::render('<x-badge color="success" text="Aprovado" />');
        $this->assertStringContainsString('bg-success-soft', $htmlString);
        $this->assertStringContainsString('text-success', $htmlString);

        $htmlEnum = Blade::render('<x-badge :color="$color" text="Índigo" />', [
            'color' => BadgeColorEnum::INDIGO,
        ]);
        $this->assertStringContainsString('bg-indigo-soft', $htmlEnum);
        $this->assertStringContainsString('text-indigo', $htmlEnum);
    }

    /**
     * Testa que badges com cor light e dark utilizam classes visíveis sem falha de -soft inexistente.
     */
    public function test_renderiza_badge_com_cor_light_e_dark(): void
    {
        $htmlLight = Blade::render('<x-badge color="light" text="Claro" />');
        $this->assertStringContainsString('bg-light', $htmlLight);
        $this->assertStringContainsString('text-dark', $htmlLight);
        $this->assertStringContainsString('border', $htmlLight);
        $this->assertStringNotContainsString('bg-light-soft', $htmlLight);
        $this->assertStringNotContainsString('text-light', $htmlLight);

        $htmlDark = Blade::render('<x-badge color="dark" text="Escuro" />');
        $this->assertStringContainsString('bg-dark', $htmlDark);
        $this->assertStringContainsString('text-white', $htmlDark);
        $this->assertStringNotContainsString('bg-dark-soft', $htmlDark);
    }

    /**
     * Testa variantes visuais: soft (padrão), solid e outline.
     */
    public function test_renderiza_variantes_soft_solid_e_outline(): void
    {
        // 1. Soft (default)
        $htmlSoft = Blade::render('<x-badge color="primary" text="Soft" />');
        $this->assertStringContainsString('bg-primary-soft', $htmlSoft);
        $this->assertStringContainsString('text-primary', $htmlSoft);

        // 2. Solid via :soft="false"
        $htmlSolid1 = Blade::render('<x-badge color="danger" :soft="false" text="Sólido" />');
        $this->assertStringContainsString('bg-danger text-white', $htmlSolid1);
        $this->assertStringNotContainsString('bg-danger-soft', $htmlSolid1);

        // 3. Solid via variant="solid"
        $htmlSolid2 = Blade::render('<x-badge color="warning" variant="solid" text="Atenção" />');
        $this->assertStringContainsString('bg-warning text-dark', $htmlSolid2);

        // 4. Solid via BadgeVariantEnum::SOLID
        $htmlSolid3 = Blade::render('<x-badge color="success" :variant="$variant" text="Concluído" />', [
            'variant' => BadgeVariantEnum::SOLID,
        ]);
        $this->assertStringContainsString('bg-success text-white', $htmlSolid3);

        // 5. Outline via prop outline
        $htmlOutline1 = Blade::render('<x-badge color="primary" outline text="Contorno" />');
        $this->assertStringContainsString('bg-transparent border border-primary text-primary', $htmlOutline1);

        // 6. Outline via variant="outline"
        $htmlOutline2 = Blade::render('<x-badge color="danger" variant="outline" text="Cancelado" />');
        $this->assertStringContainsString('bg-transparent border border-danger text-danger', $htmlOutline2);
    }

    /**
     * Testa formato pill e tamanhos sm, md, lg.
     */
    public function test_renderiza_formato_pill_e_tamanhos(): void
    {
        // Pill
        $htmlPill = Blade::render('<x-badge pill text="Arredondado" />');
        $this->assertStringContainsString('rounded-pill', $htmlPill);

        // Tamanhos
        $htmlSm = Blade::render('<x-badge size="sm" text="Pequeno" />');
        $this->assertStringContainsString('badge-sm', $htmlSm);

        $htmlLg = Blade::render('<x-badge :size="$size" text="Grande" />', [
            'size' => BadgeSizeEnum::LG,
        ]);
        $this->assertStringContainsString('badge-lg', $htmlLg);
    }

    /**
     * Testa resolução automática a partir de Enums de domínio com label e cor contextual.
     */
    public function test_resolucao_automatica_de_enum_de_status(): void
    {
        // BoletoStatusEnum::PAGO possui label() = 'Pago / Baixado' e badgeClass() com bg-success
        $htmlBoleto = Blade::render('<x-badge :status="$status" />', [
            'status' => BoletoStatusEnum::PAGO,
        ]);

        $this->assertStringContainsString('Pago / Baixado', $htmlBoleto);
        $this->assertStringContainsString('bg-success-soft', $htmlBoleto);
        $this->assertStringContainsString('text-success', $htmlBoleto);
        $this->assertStringContainsString('role="status"', $htmlBoleto);
    }

    /**
     * Testa resolução de MessageTypeEnum com ícone contextual.
     */
    public function test_resolucao_de_status_com_icone(): void
    {
        $htmlMessage = Blade::render('<x-badge :status="$status" />', [
            'status' => MessageTypeEnum::SUCCESS,
        ]);

        $this->assertStringContainsString('Success', $htmlMessage);
        $this->assertStringContainsString('bg-success-soft', $htmlMessage);
        // MessageTypeEnum::SUCCESS tem getIcon() = 'check-circle'
        $this->assertStringContainsString('check-circle', $htmlMessage);
    }

    /**
     * Testa renderização de dot indicador e animação de pulso.
     */
    public function test_renderiza_com_dot_indicador_e_pulse(): void
    {
        $htmlDot = Blade::render('<x-badge dot color="success" text="Online" />');
        $this->assertStringContainsString('badge-dot', $htmlDot);
        $this->assertStringContainsString('bg-success', $htmlDot);
        $this->assertStringNotContainsString('spinner-grow', $htmlDot);

        $htmlPulse = Blade::render('<x-badge dot pulse color="danger" text="Ao Vivo" />');
        $this->assertStringContainsString('spinner-grow spinner-grow-sm', $htmlPulse);
    }

    /**
     * Testa renderização como link (tag <a>).
     */
    public function test_renderiza_como_link_com_href(): void
    {
        $htmlLink = Blade::render('<x-badge href="/concursos/1" target="_blank" text="Ver Concurso" />');

        $this->assertStringContainsString('<a href="/concursos/1"', $htmlLink);
        $this->assertStringContainsString('target="_blank"', $htmlLink);
        $this->assertStringContainsString('rel="noopener noreferrer"', $htmlLink);
        $this->assertStringContainsString('text-decoration-none lift-sm', $htmlLink);
    }

    /**
     * Testa renderização de badge com botão de remoção (dismissible).
     */
    public function test_renderiza_badge_dismissible(): void
    {
        $html = Blade::render('<x-badge dismissible text="Filtro Ativo" />');

        $this->assertStringContainsString('btn-close', $html);
        $this->assertStringContainsString('aria-label="Remover"', $html);
    }
}
