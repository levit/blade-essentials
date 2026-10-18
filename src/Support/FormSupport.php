<?php

declare(strict_types=1);

namespace Select\Components\Support;

use Select\Components\Support\Forms\FormBuilder;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Classe de Suporte para preenchimento de campos e inferência de metadados de Formulários no sistema.
 *
 * Facilita a recuperação de valores com precedência inteligente:
 * old() da sessão > atributo do model (com suporte a enums) > valor padrão.
 *
 * Provê também resolução em cascata (Fallback Cascade) para obrigatoriedade de campos (required):
 * 1. Override explícito na View Blade (:required="true|false")
 * 2. FormRequest associado ao FormBuilder ou Rota ativa (regras de validação)
 * 3. Schema DDL do Banco de Dados via Model (NOT NULL, sem DEFAULT, presente no $fillable, ignorando colunas de sistema)
 * 4. Fallback padrão: false
 */
class FormSupport
{
    /**
     * Instância ativa do FormBuilder no ciclo de renderização da view atual.
     */
    private static ?FormBuilder $activeForm = null;

    /**
     * Instância ativa do Model Eloquent no ciclo de renderização da view atual.
     */
    private static ?Model $activeModel = null;

    /**
     * Colunas técnicas de infraestrutura que nunca devem ser inferidas como campos required no frontend.
     *
     * @var list<string>
     */
    private const SYSTEM_COLUMNS = [
        'id',
        'uuid',
        'instituicao_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'email_verified_at',
        'created_by',
        'updated_by',
    ];

    /**
     * Cache estático em memória durante a requisição (evita múltiplos acessos a cache/banco).
     *
     * @var array<string, array<string, bool>>
     */
    private static array $schemaCache = [];

    /**
     * Define o formulário ativo na renderização.
     */
    public static function setActiveForm(?FormBuilder $form): void
    {
        self::$activeForm = $form;
    }

    /**
     * Retorna o formulário ativo.
     */
    public static function getActiveForm(): ?FormBuilder
    {
        return self::$activeForm;
    }

    /**
     * Define o model ativo na renderização.
     */
    public static function setActiveModel(?Model $model): void
    {
        self::$activeModel = $model;
    }

    /**
     * Retorna o model ativo.
     */
    public static function getActiveModel(): ?Model
    {
        return self::$activeModel;
    }

    /**
     * Limpa o estado ativo (útil entre requisições ou testes).
     */
    public static function clear(): void
    {
        self::$activeForm = null;
        self::$activeModel = null;
    }

    /**
     * Limpa o cache de schema estático e persistente.
     */
    public static function clearSchemaCache(?string $table = null): void
    {
        if ($table !== null) {
            unset(self::$schemaCache[$table]);
            try {
                Cache::forget("schema_required_cols_{$table}");
            } catch (Throwable) {
                // Silencioso em caso de falha de conexão com cache
            }
            return;
        }

        self::$schemaCache = [];
    }

    /**
     * Recupera o valor atual para um campo de formulário considerando old input, model e enums.
     *
     * @param  string  $name  Nome do campo de formulário (ex: 'nome', 'endereco.cidade').
     * @param  Model|null  $model  Instância do Model associada à tela ou null para buscar do contexto ativo.
     * @param  mixed  $default  Valor padrão caso não haja input anterior ou dado no model.
     * @return mixed Valor resolvido para exibição no campo do formulário.
     */
    public static function value(string $name, ?Model $model = null, mixed $default = null): mixed
    {
        $targetModel = $model ?? self::getActiveModel() ?? self::getActiveForm()?->getModelClass();

        if ($targetModel === null) {
            return old($name, $default);
        }

        $modelValue = data_get($targetModel, $name, $default);

        if ($modelValue instanceof BackedEnum) {
            $modelValue = $modelValue->value;
        }

        return old($name, $modelValue);
    }

    /**
     * Resolve se um campo de formulário é obrigatório utilizando resolução em cascata.
     *
     * @param  string  $name  Nome do campo (ex: 'nome', 'titulo', 'dados[cpf]').
     * @param  Model|null  $model  Model associado ou null para inferir do contexto ativo.
     * @param  FormBuilder|null  $form  FormBuilder associado ou null para inferir do contexto ativo.
     * @param  bool|null  $explicit  Valor explícito passado pela view Blade (:required="...").
     * @return bool True se o campo for obrigatório, False caso contrário.
     */
    public static function isRequired(
        string $name,
        ?Model $model = null,
        ?FormBuilder $form = null,
        ?bool $explicit = null
    ): bool {
        // Nível 1: Override explícito na View Blade (prioridade máxima)
        if ($explicit !== null) {
            return $explicit;
        }

        $activeForm = $form ?? self::getActiveForm();
        $targetModel = $model ?? self::getActiveModel() ?? $activeForm?->getModelClass();

        // Nível 2: Inspecionar regras do FormRequest (se vinculado ao FormBuilder)
        if ($activeForm !== null && ($requestClass = $activeForm->getRequestClass())) {
            $fromRequest = self::checkRequestRules($name, $requestClass);
            if ($fromRequest !== null) {
                return $fromRequest;
            }
        }

        // Nível 3: Inspecionar DDL do Banco de Dados via Schema do Model
        if ($targetModel instanceof Model) {
            $fromSchema = self::checkModelSchema($name, $targetModel);
            if ($fromSchema !== null) {
                return $fromSchema;
            }
        }

        // Nível 4: Fallback padrão
        return false;
    }

    /**
     * Inspeciona se as regras de validação de um FormRequest determinam o campo como obrigatório.
     */
    private static function checkRequestRules(string $name, string $requestClass): ?bool
    {
        if (!class_exists($requestClass)) {
            return null;
        }

        try {
            /** @var \Illuminate\Foundation\Http\FormRequest $instance */
            $instance = new $requestClass();
            $rules = method_exists($instance, 'rules') ? $instance->rules() : [];

            $normalizedName = trim(str_replace(['[', ']'], ['.', ''], $name), '.');
            $fieldRules = $rules[$name] ?? $rules[$normalizedName] ?? null;

            if ($fieldRules === null) {
                // Tenta buscar por wildcard ou campo base (ex: 'vagas.*' ou 'vagas')
                $baseField = explode('.', $normalizedName)[0];
                $fieldRules = $rules[$baseField] ?? null;
            }

            if ($fieldRules === null) {
                return null;
            }

            if (!is_array($fieldRules)) {
                $fieldRules = explode('|', (string) $fieldRules);
            }

            $hasRequired = false;
            $hasNullable = false;

            foreach ($fieldRules as $rule) {
                if (is_string($rule)) {
                    foreach (explode('|', $rule) as $subRule) {
                        $trimmed = strtolower(trim($subRule));
                        if ($trimmed === 'required') {
                            $hasRequired = true;
                        } elseif ($trimmed === 'nullable') {
                            $hasNullable = true;
                        }
                    }
                } elseif (is_object($rule)) {
                    $ruleClass = get_class($rule);
                    if (str_contains(strtolower($ruleClass), 'required')) {
                        $hasRequired = true;
                    }
                }
            }

            if ($hasRequired && !$hasNullable) {
                return true;
            }

            if ($hasNullable) {
                return false;
            }
        } catch (Throwable) {
            return null;
        }

        return null;
    }

    /**
     * Inspeciona o schema da tabela do Model no Banco de Dados.
     */
    private static function checkModelSchema(string $name, Model $model): ?bool
    {
        try {
            $table = $model->getTable();
            if (empty($table)) {
                return null;
            }

            // Normaliza nome do campo (remove notação de array / dot notation)
            $normalizedName = trim(str_replace(['[', ']'], ['.', ''], $name), '.');
            $columnName = str_contains($normalizedName, '.')
                ? substr($normalizedName, strrpos($normalizedName, '.') + 1)
                : $normalizedName;

            // Ignora colunas de sistema / timestamps
            if (in_array($columnName, self::SYSTEM_COLUMNS, true)) {
                return false;
            }

            // Se o model tiver fillable definido e o campo não constar nele, não inferir como required
            $fillable = $model->getFillable();
            if (!empty($fillable) && !in_array($columnName, $fillable, true)) {
                return false;
            }

            if (!isset(self::$schemaCache[$table])) {
                self::$schemaCache[$table] = Cache::remember(
                    "schema_required_cols_{$table}",
                    now()->addHours(24),
                    function () use ($table): array {
                        $columns = Schema::getColumns($table);
                        $requiredMap = [];
                        foreach ($columns as $col) {
                            $colName = $col['name'] ?? null;
                            if (!$colName) {
                                continue;
                            }
                            $isNullable = (bool) ($col['nullable'] ?? true);
                            $hasDefault = ($col['default'] !== null);
                            $extra = (string) ($col['extra'] ?? '');
                            $isAuto = str_contains($extra, 'auto_increment');
                            $isGenerated = !empty($col['generation_expression'] ?? null);

                            $requiredMap[$colName] = (!$isNullable && !$hasDefault && !$isAuto && !$isGenerated);
                        }
                        return $requiredMap;
                    }
                );
            }

            return self::$schemaCache[$table][$columnName] ?? false;
        } catch (Throwable) {
            // Em caso de tabela inexistente, conexão indisponível ou driver não suportado, retorna null
            return null;
        }
    }
}
