<?php

declare(strict_types=1);

namespace Select\Components\Support;

use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Classe de Suporte para Manipulação de Strings.
 */
class StringSupport
{
    /**
     * Lista padrão de siglas e termos que devem obrigatoriamente permanecer em maiúsculo.
     *
     * @var array<int, string>
     */
    public const array PALAVRAS_MAIUSCULAS = [
        'BB', 'BBI', 'BMG', 'BMP', 'BNY', 'BRB', 'BS2', 'BTG',
        'BV', 'CCTVM', 'CSF', 'DTVM', 'IP', 'NU', 'UBS', 'XP',
    ];

    /**
     * Trunca um texto limitando ao número máximo de caracteres especificado, adicionando sufixo.
     *
     * @param  string|null  $texto  Texto original a ser truncado.
     * @param  int  $limite  Limite máximo de caracteres.
     * @param  string  $sufixo  Sufixo indicador de truncamento (padrão: '...').
     * @return string|null Texto truncado ou null se o texto for vazio.
     */
    public static function truncar(?string $texto, int $limite = 50, string $sufixo = '...'): ?string
    {
        if (empty($texto)) {
            return null;
        }

        if (mb_strlen($texto, 'UTF-8') <= $limite) {
            return $texto;
        }

        return mb_substr($texto, 0, $limite, 'UTF-8').$sufixo;
    }

    /**
     * Gera uma versão em formato slug (URL-friendly) para uma string informada.
     *
     * @param  string|null  $texto  Texto de entrada a ser convertido.
     * @return string|null Slug gerado ou null se a string for vazia.
     */
    public static function slug(?string $texto): ?string
    {
        if (empty($texto)) {
            return null;
        }

        return Str::slug($texto, '-');
    }

    /**
     * Remove acentuação e caracteres diacríticos de uma string, normalizando apóstrofos.
     *
     * @param  string|null  $texto  Texto a ser normalizado.
     * @param  bool  $substituirApostrofosPorEspaco  Se true, substitui apóstrofos por espaço; se false, apenas remove.
     * @return string|null Texto sem acentos ou null se o texto de entrada for null.
     */
    public static function removerAcentos(?string $texto, bool $substituirApostrofosPorEspaco = true): ?string
    {
        if ($texto === null || $texto === '') {
            return $texto;
        }

        $apostrofosPattern = "/[´`'’]/u";

        if ($substituirApostrofosPorEspaco) {
            $texto = preg_replace($apostrofosPattern, ' ', $texto);
        } else {
            $texto = preg_replace($apostrofosPattern, '', $texto);
        }

        $semAcento = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);

        if ($semAcento === false) {
            $semAcento = $texto;
        }

        return preg_replace('/\s+/', ' ', trim($semAcento));
    }

    /**
     * Gera um localizador ou código aleatório alfanumérico seguro.
     *
     * @param  int  $tamanho  Comprimento do código gerado (maior que zero).
     * @param  bool  $apenasLetras  Se true, gera apenas letras maiúsculas; se false, inclui números.
     * @return string Código aleatório gerado.
     *
     * @throws InvalidArgumentException Caso o tamanho informado seja menor ou igual a zero.
     */
    public static function gerarLocalizador(int $tamanho = 6, bool $apenasLetras = true): string
    {
        if ($tamanho <= 0) {
            throw new InvalidArgumentException('O tamanho deve ser maior que zero.');
        }

        $caracteres = $apenasLetras
            ? 'ABCDEFGHJKLMNPRSTUVWXYZ' // Removido caracteres confusos: I, O, Q
            : 'ABCDEFGHJKLMNPRSTUVWXYZ23456789'; // Removido caracteres confusos: I, O, Q, 0, 1

        $localizador = '';
        $max = strlen($caracteres) - 1;

        for ($i = 0; $i < $tamanho; $i++) {
            $localizador .= $caracteres[random_int(0, $max)];
        }

        return $localizador;
    }

    /**
     * Gera um nome/identificador aleatório para elementos de formulário ou referências DOM.
     *
     * @param  int  $size  Quantidade de caracteres do identificador gerado.
     * @param  bool  $lower  Se true, converte o resultado para minúsculas.
     * @return string Identificador aleatório gerado.
     */
    public static function nameRandom(int $size = 7, bool $lower = true): string
    {
        $rand = static::gerarLocalizador($size, false);

        return $lower ? Str::lower($rand) : $rand;
    }

    /**
     * Converte identificadores em camelCase ou kebab-case para formato legível por humanos (Title Case).
     *
     * @param  string  $text  Texto em formato camelCase ou kebab-case (ex: 'usuarioNome', 'user-profile').
     * @return string Texto convertido em palavras legíveis (ex: 'Usuario Nome', 'User Profile').
     */
    public static function humanize(string $text): string
    {
        return Str::title(Str::replace('-', ' ', preg_replace('/([a-z])([A-Z])/', '$1 $2', $text)));
    }

    /**
     * Formata um nome próprio ou razão social em Title Case,
     * preservando siglas, abreviações (ex: S.A., S/A) e numerais romanos,
     * além de forçar palavras específicas em maiúsculo.
     *
     * @param  array<int, string>  $palavrasMaiusculas  Palavras adicionais para manter em maiúsculo
     */
    public static function nomeProprio(?string $nome, array $palavrasMaiusculas = []): string
    {
        if ($nome === null || trim($nome) === '') {
            return '';
        }

        // Lista de conectivos que devem ficar em minúsculo
        $excecoes = ['de', 'da', 'das', 'do', 'dos', 'e', 'ou', 'com'];

        // Lista de palavras que devem obrigatoriamente ficar em maiúsculo
        $todasMaiusculas = ! empty($palavrasMaiusculas)
            ? array_unique(array_merge(self::PALAVRAS_MAIUSCULAS, $palavrasMaiusculas))
            : self::PALAVRAS_MAIUSCULAS;

        $maiusculasMap = array_fill_keys(
            array_map(static fn (string $w): string => mb_strtoupper($w, 'UTF-8'), $todasMaiusculas),
            true
        );

        // Divide o nome em palavras ignorando espaços múltiplos
        $palavras = preg_split('/\s+/u', trim($nome), -1, PREG_SPLIT_NO_EMPTY);

        if (empty($palavras)) {
            return '';
        }

        // Mapeia e formata cada palavra
        $palavrasFormatadas = array_map(function (string $palavra, int $index) use ($excecoes, $maiusculasMap): string {
            // 1. Siglas e abreviações com pontos ou barras (ex: S.A., S/A, C.F.I., D.T.V.M.)
            if (preg_match('/^([^\p{L}\p{N}]*)([a-z](?:[.\/][a-z])+\.?)([^\p{L}\p{N}]*)$/iu', $palavra, $matches)) {
                $prefix = $matches[1];
                $sigla = mb_strtoupper($matches[2], 'UTF-8');
                if (strcasecmp($sigla, 'S.A') === 0 && ! str_ends_with($sigla, '.')) {
                    $sigla .= '.';
                }
                $suffix = $matches[3];

                return $prefix.$sigla.$suffix;
            }

            // 2. Palavras que devem obrigatoriamente ficar em maiúsculo (ex: BRB, BV, UBS, etc.)
            if (preg_match('/^([^\p{L}\p{N}]*)([\p{L}\p{N}]+)([^\p{L}\p{N}]*)$/iu', $palavra, $matches)) {
                $termoUpper = mb_strtoupper($matches[2], 'UTF-8');
                if (isset($maiusculasMap[$termoUpper])) {
                    return $matches[1].$termoUpper.$matches[3];
                }
            }

            // 3. Numerais romanos (ex: II, III, IV, IX, XVI, XXI, etc.)
            if (preg_match('/^([^\p{L}\p{N}]*)(i{1,3}|iv|v|vi{0,3}|ix|x{1,3}(?:ix|iv|vi{0,3}|v|i{1,3})?|xiv|xix)([^\p{L}\p{N}]*)$/iu', $palavra, $matches)) {
                return $matches[1].mb_strtoupper($matches[2], 'UTF-8').$matches[3];
            }

            $palavraLower = mb_strtolower($palavra, 'UTF-8');

            // 4. Conectivos em minúsculo (exceto quando é a primeira palavra)
            if ($index > 0 && in_array($palavraLower, $excecoes, true)) {
                return $palavraLower;
            }

            // 5. Capitalização preservando acentos e tratando apóstrofos (ex: D'Ávila)
            $convertido = mb_convert_case($palavraLower, MB_CASE_TITLE, 'UTF-8');

            return (string) preg_replace_callback("/(^|[\\'’])(\p{L})/u", static function (array $m): string {
                return $m[1].mb_strtoupper($m[2], 'UTF-8');
            }, $convertido);
        }, $palavras, array_keys($palavras));

        return implode(' ', $palavrasFormatadas);
    }

    /**
     * Determina se o valor informado possui conteúdo (não é nulo e não é uma string vazia).
     *
     * Diferente de empty(), este método preserva valores numéricos ou strings '0' como conteúdo válido.
     *
     * @param  mixed  $value  Valor a ser verificado.
     * @return bool True se contiver valor/conteúdo válido, false caso contrário.
     */
    public static function hasValue(mixed $value): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_string($value)) {
            return $value !== '';
        }

        return true;
    }
}
