<?php

declare(strict_types=1);

namespace Select\Components\Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Select\Components\Tests\TestCase;

class FieldGroup3ComponentsTest extends TestCase
{
    public function test_file_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.file nameField="comprovante" label="Comprovante de Inscrição" accept="application/pdf" maxSize="10" />');

        $this->assertStringContainsString('id="file_comprovante_id"', $html);
        $this->assertStringContainsString('name="comprovante"', $html);
        $this->assertStringContainsString('accept="application/pdf"', $html);
        $this->assertStringContainsString('data-max-size="10"', $html);
        $this->assertStringContainsString('Comprovante de Inscrição', $html);
        $this->assertStringContainsString('name="comprovante_remove"', $html);
        $this->assertStringContainsString('Selecionar arquivo', $html);
    }

    public function test_file_component_disabled_mode(): void
    {
        $html = Blade::render('<x-field.file nameField="anexo" disabled="true" />');

        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('file_anexo_id', $html);
    }

    public function test_image_component_renders_with_src(): void
    {
        $html = Blade::render('<x-field.image id="logotipo" src="https://example.com/logo.png" title="Logotipo da Instituição" />');

        $this->assertStringContainsString('id="preview_img_logotipo"', $html);
        $this->assertStringContainsString('id="preview_container_logotipo"', $html);
        $this->assertStringContainsString('src="https://example.com/logo.png"', $html);
        $this->assertStringContainsString('Logotipo da Instituição', $html);
    }

    public function test_image_component_renders_placeholder_when_empty(): void
    {
        $html = Blade::render('<x-field.image id="foto" placeholderText="Nenhuma foto carregada" />');

        $this->assertStringContainsString('id="preview_placeholder_foto"', $html);
        $this->assertStringContainsString('Nenhuma foto carregada', $html);
        $this->assertStringContainsString('d-none', $html); // container oculto
    }

    public function test_markdown_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.markdown nameField="conteudo" label="Enunciado" value="Texto em **Markdown**" :showMdBadge="true" :showMathBadge="true" />');

        $this->assertStringContainsString('id="markdown_conteudo_id"', $html);
        $this->assertStringContainsString('name="conteudo"', $html);
        $this->assertStringContainsString('Texto em **Markdown**', $html);
        $this->assertStringContainsString('Markdown', $html);
        $this->assertStringContainsString('LaTeX/KaTeX', $html);
        $this->assertStringContainsString('Enunciado', $html);
    }

    public function test_ip_component_renders_public_ip(): void
    {
        $html = Blade::render('<x-field.ip nameField="ip_origem" ip="200.189.113.34" />');

        $this->assertStringContainsString('name="ip_origem"', $html);
        $this->assertStringContainsString('value="200.189.113.34"', $html);
        $this->assertStringContainsString('https://ipinfo.io/200.189.113.34', $html);
        $this->assertStringContainsString('bi-geo-alt-fill', $html);
    }

    public function test_ip_component_renders_localhost_ip(): void
    {
        $html = Blade::render('<x-field.ip ip="127.0.0.1" />');

        $this->assertStringContainsString('value="127.0.0.1"', $html);
        $this->assertStringContainsString('Servidor', $html);
        $this->assertStringContainsString('bi-server', $html);
        $this->assertStringNotContainsString('https://ipinfo.io', $html);
    }

    public function test_url_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.url nameField="portal" label="Portal Oficial" value="https://concursos.gov.br" required="true" />');

        $this->assertStringContainsString('id="input_portal_id"', $html);
        $this->assertStringContainsString('name="portal"', $html);
        $this->assertStringContainsString('type="url"', $html);
        $this->assertStringContainsString('value="https://concursos.gov.br"', $html);
        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('Portal Oficial', $html);
    }

    public function test_url_component_disabled_with_value_renders_hidden(): void
    {
        $html = Blade::render('<x-field.url nameField="portal" value="https://exemplo.com" disabled="true" />');

        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('type="hidden"', $html);
        $this->assertStringContainsString('value="https://exemplo.com"', $html);
    }

    public function test_user_agent_component_renders_correctly(): void
    {
        $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36';
        $html = Blade::render('<x-field.user-agent :agent="$agent" />', ['agent' => $agent]);

        $this->assertStringContainsString('name="user_agent"', $html);
        $this->assertStringContainsString('Windows 10/11', $html);
        $this->assertStringContainsString('Google Chrome 120', $html);
        $this->assertStringContainsString('bi-windows', $html);
        $this->assertStringContainsString('bi-browser-chrome', $html);
    }

    public function test_input_group_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.input-group label="Preço" :joined="true"><x-slot:prepend><span class="input-group-text">R$</span></x-slot:prepend><input class="form-control" type="text" /></x-field.input-group>');

        $this->assertStringContainsString('input-group-joined', $html);
        $this->assertStringContainsString('Preço', $html);
        $this->assertStringContainsString('R$', $html);
    }

    public function test_tags_component_renders_correctly(): void
    {
        $html = Blade::render('<x-field.tags nameField="palavras_chave" label="Palavras-chave" value="edital, fiscal, prova" />');

        $this->assertStringContainsString('id="tags_palavras-chave_id_wrapper"', $html);
        $this->assertStringContainsString('name="palavras_chave"', $html);
        $this->assertStringContainsString('value="edital, fiscal, prova"', $html);
        $this->assertStringContainsString('data-tag-value="edital"', $html);
        $this->assertStringContainsString('data-tag-value="fiscal"', $html);
        $this->assertStringContainsString('data-tag-value="prova"', $html);
        $this->assertStringContainsString('btn-remove-tag', $html);
    }

    public function test_tags_component_disabled_mode(): void
    {
        $html = Blade::render('<x-field.tags nameField="tags" value="vestibular, enem" disabled="true" />');

        $this->assertStringContainsString('data-tag-value="vestibular"', $html);
        $this->assertStringContainsString('data-tag-value="enem"', $html);
        $this->assertStringNotContainsString('btn-remove-tag', $html);
        $this->assertStringNotContainsString('id="tags_tags_id_input"', $html);
    }
}
