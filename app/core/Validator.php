<?php
namespace App\Core;

class Validator
{
    private static array $errors = [];
    private static $instance = null;

    private static array $rules;

    public function __construct()
    {
        self::$errors = [];
        self::$rules = [];
        $this->initRules();
    }

    private function initRules(): void
    {
        self::$rules['required'] = function ($field, $value) {
            if (empty($value)) {
                self::addError($field, "Le champ {$field} est requis");
            }
        };

        self::$rules['string'] = function ($field, $value) {
            if (!is_string($value)) {
                self::addError($field, "Le champ {$field} doit être une chaîne de caractères");
            }
        };

        self::$rules['minLength'] = function ($field, $value, $minLength) {
            if (strlen($value) < $minLength) {
                self::addError($field, "Le champ {$field} doit contenir au moins {$minLength} caractères");
            }
        };

        self::$rules['numeric'] = function ($field, $value) {
            if (!is_numeric($value)) {
                self::addError($field, "Le champ {$field} doit être numérique");
            }
        };
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Validator();
        }
        return self::$instance;
    }

    public function validate(array $data, array $rules): bool
    {
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                if (is_string($rule)) {
                    $callback = self::$rules[$rule] ?? null;
                    if ($callback) {
                        $callback($field, $value);
                    }
                }
                elseif (is_array($rule)) {
                    $ruleName = $rule[0];
                    $params = array_slice($rule, 1);
                    $callback = self::$rules[$ruleName] ?? null;

                    if ($callback) {
                        $callback($field, $value, ...$params);
                    }
                }
            }
        }

        return empty(self::$errors);
    }

    public static function addError(string $field, string $message)
    {
        self::$errors[$field] = $message;
    }

    public static function getErrors()
    {
        return self::$errors;
    }

}
