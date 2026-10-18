<?php

declare(strict_types=1);

namespace Select\Components\Support;

/**
 * Utilitário especializado em validação semântica, diagnóstico de erros
 * e sugestão inteligente de correções para endereços de e-mail.
 */
class EmailValidator
{
    /**
     * Expressão regular padrão W3C/HTML5 para formato geral de e-mail.
     */
    public const string REGEX_RFC = '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/';

    /**
     * Dicionário de provedores populares de e-mail.
     */
    public const array PROVEDORES_POPULARES = [
        'gmail.com',
        'hotmail.com',
        'outlook.com',
        'yahoo.com',
        'yahoo.com.br',
        'icloud.com',
        'live.com',
        'bol.com.br',
        'uol.com.br',
        'terra.com.br',
    ];

    /**
     * Mapeamento direto de erros conhecidos de digitação em domínios completos.
     */
    public const array DOMINIOS_TYPOS = [
        'gnail.com' => 'gmail.com',
        'gamil.com' => 'gmail.com',
        'gmial.com' => 'gmail.com',
        'gmai.com' => 'gmail.com',
        'gmaill.com' => 'gmail.com',
        'gmeil.com' => 'gmail.com',
        'gmail.com.br' => 'gmail.com',
        'gmail.coml' => 'gmail.com',
        'gmail.con' => 'gmail.com',
        'gmail.comm' => 'gmail.com',
        'gmail.coom' => 'gmail.com',
        'gmail.co' => 'gmail.com',
        'hotmial.com' => 'hotmail.com',
        'hotmai.com' => 'hotmail.com',
        'hotmeil.com' => 'hotmail.com',
        'hotmil.com' => 'hotmail.com',
        'hotmal.com' => 'hotmail.com',
        'hotmail.coml' => 'hotmail.com',
        'hotmail.con' => 'hotmail.com',
        'hotmail.co' => 'hotmail.com',
        'outlok.com' => 'outlook.com',
        'outllok.com' => 'outlook.com',
        'outlock.com' => 'outlook.com',
        'otlook.com' => 'outlook.com',
        'outlook.coml' => 'outlook.com',
        'outlook.con' => 'outlook.com',
        'yaho.com' => 'yahoo.com',
        'yahooo.com' => 'yahoo.com',
        'yaho.com.br' => 'yahoo.com.br',
        'yahoo.coml' => 'yahoo.com',
        'yahoo.con' => 'yahoo.com',
        'iclou.com' => 'icloud.com',
        'icoud.com' => 'icloud.com',
        'bol.com.b' => 'bol.com.br',
        'bol.coml' => 'bol.com.br',
        'bol.con.br' => 'bol.com.br',
        'uol.com.b' => 'uol.com.br',
        'uol.coml' => 'uol.com.br',
        'uol.con.br' => 'uol.com.br',
        'live.con' => 'live.com',
        'live.coml' => 'live.com',
    ];

    /**
     * Mapeamento de typos comuns em extensões de domínio (TLD).
     */
    public const array TLD_TYPOS = [
        'coml' => 'com',
        'con' => 'com',
        'comm' => 'com',
        'cpm' => 'com',
        'coom' => 'com',
        'cm' => 'com',
        'comn' => 'com',
        'bre' => 'com.br',
        'brr' => 'br',
        'brl' => 'com.br',
        'ogr' => 'org',
        'orgg' => 'org',
        'ney' => 'net',
        'nett' => 'net',
        'gvo' => 'gov.br',
        'gove' => 'gov.br',
        'eddu' => 'edu.br',
    ];

    /**
     * Valida se um e-mail possui sintaxe correta e sem anomalias conhecidas de domínio.
     *
     * @param string|null $email Endereço de e-mail a validar.
     * @return bool True se válido, False caso contrário.
     */
    public static function validar(?string $email): bool
    {
        return self::diagnosticar($email) === null;
    }

    /**
     * Analisa o e-mail e retorna mensagem explicativa de diagnóstico em caso de erro.
     *
     * @param string|null $email Endereço de e-mail a analisar.
     * @return string|null Mensagem explicativa do erro ou null se o e-mail for válido.
     */
    public static function diagnosticar(?string $email): ?string
    {
        $valor = trim((string) $email);

        if ($valor === '') {
            return 'O endereço de e-mail não pode estar vazio.';
        }

        if (str_contains($valor, ' ')) {
            return 'O e-mail não pode conter espaços.';
        }

        if (!str_contains($valor, '@')) {
            return "O endereço de e-mail deve conter o caractere '@'.";
        }

        $partes = explode('@', $valor);
        if (count($partes) > 2) {
            return "O e-mail deve conter apenas um caractere '@'.";
        }

        $usuario = $partes[0];
        $dominio = mb_strtolower($partes[1] ?? '');

        if ($usuario === '') {
            return "Informe o nome de usuário antes do '@'.";
        }

        if ($dominio === '') {
            return "Informe o domínio após o '@' (ex: gmail.com).";
        }

        if (str_contains($valor, '..')) {
            return 'O e-mail não pode conter pontos consecutivos (..).';
        }

        if (str_starts_with($dominio, '.') || str_ends_with($dominio, '.')) {
            return 'O domínio não pode iniciar ou terminar com ponto.';
        }

        if (!str_contains($dominio, '.')) {
            return 'O domínio deve possuir uma extensão (ex: .com ou .com.br).';
        }

        // Diagnóstico de erro em domínio popular conhecido
        if (isset(self::DOMINIOS_TYPOS[$dominio])) {
            $correto = self::DOMINIOS_TYPOS[$dominio];
            return "O domínio '{$dominio}' parece incorreto. Você quis dizer '{$correto}'?";
        }

        // Diagnóstico de TLD incorreto (ex: .coml, .bre)
        $partesDominio = explode('.', $dominio);
        $tld = end($partesDominio);

        if (isset(self::TLD_TYPOS[$tld])) {
            $sugestaoTld = self::TLD_TYPOS[$tld];
            return "A extensão '.{$tld}' é inválida. Você quis dizer '.{$sugestaoTld}'?";
        }

        // TLD muito curto (1 letra, ex: .b, .c)
        if (strlen($tld) < 2) {
            return "A extensão '.{$tld}' é muito curta e inválida.";
        }

        // Validação RFC global final
        if (!preg_match(self::REGEX_RFC, $valor)) {
            return 'O formato do endereço de e-mail é inválido.';
        }

        return null;
    }

    /**
     * Analisa o e-mail e gera sugestão inteligente de correção de digitação caso aplicável.
     *
     * @param string|null $email Endereço de e-mail a verificar.
     * @return string|null Sugestão de e-mail corrigido ou null se não houver sugestão.
     */
    public static function sugerirCorrecao(?string $email): ?string
    {
        $valor = trim((string) $email);
        if ($valor === '' || !str_contains($valor, '@')) {
            return null;
        }

        $partes = explode('@', $valor);
        if (count($partes) !== 2 || $partes[0] === '' || $partes[1] === '') {
            return null;
        }

        $usuario = $partes[0];
        $dominio = mb_strtolower($partes[1]);

        // 1. Erro mapeado diretamente no dicionário de domínios
        if (isset(self::DOMINIOS_TYPOS[$dominio])) {
            return $usuario . '@' . self::DOMINIOS_TYPOS[$dominio];
        }

        // 2. Erro mapeado no dicionário de TLDs (ex: usuario@empresa.coml -> usuario@empresa.com)
        $partesDominio = explode('.', $dominio);
        $tld = end($partesDominio);
        if (isset(self::TLD_TYPOS[$tld])) {
            $sugestaoTld = self::TLD_TYPOS[$tld];
            $tldCorrigido = explode('.', $sugestaoTld);
            array_pop($partesDominio);
            $novoDominio = implode('.', array_merge($partesDominio, $tldCorrigido));
            return $usuario . '@' . $novoDominio;
        }

        // 3. Verificação por distância de Levenshtein contra provedores populares
        $menorDistancia = 999;
        $melhorProvedor = null;

        foreach (self::PROVEDORES_POPULARES as $provedor) {
            $distancia = levenshtein($dominio, $provedor);
            if ($distancia <= 2 && $distancia > 0 && $distancia < $menorDistancia) {
                $menorDistancia = $distancia;
                $melhorProvedor = $provedor;
            }
        }

        if ($melhorProvedor !== null) {
            return $usuario . '@' . $melhorProvedor;
        }

        return null;
    }
}

