<?php

namespace Root\Application\Providers;

use Exception;

class ValidatorProviders
{

    private array $values;
    private array $roles;
    private array $errors;

    /**
     * Sets all values for validation
     *
     * @param array $values
     *
     * @return $this
     */
    public function values(array $values): self
    {
        $this->values = $values;
        return $this;
    }

    /**
     * Defines all rules for validation
     *
     * @param array $roles
     *
     * @return $this
     */
    public function roles(array $roles): self
    {
        $this->roles = array_map(function ($key) {
            return explode('|', $key);
        }, $roles);

        return $this;
    }

    /**
     * Validate information based on rules
     *
     * @return array
     * @throws \Exception
     */
    public function validate(): self
    {
        if (empty($this->roles)) {
            throw new Exception('No rules have been defined for validation.');
        }

        if (empty($this->values)) {
            throw new Exception('Validation values have not been defined.');
        }

        foreach ($this->roles as $keys => $role) {
            if (array_key_exists($keys, $this->values) === false) {
                $this->errors[$keys]['*'] = 'Invalid parameter or value';
                continue;
            }

            $value = $this->values[$keys];

            $this->errors[$keys] = array_reduce($role, function ($result, $function) use ($value, $keys) {
                $numberLimit = 0;
                if (str_contains($function, ":")) {
                    $function = explode(':', $function);
                    $numberLimit = $function[1];
                    $function = $function[0];
                }
                $result[] = self::$function($value, $numberLimit);
                return $result;
            });

            foreach ($this->errors[$keys] ?? [] as $key => $val) {
                if (empty($val)) {
                    unset($this->errors[$keys][$key]);
                }
            }
        }

        return $this;
    }

    /**
     * An alias for the empty validation condition for errors
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->showErrors());
    }

    /**
     * Return existing errors
     * in validation
     *
     * @param array $arr
     *
     * @return array
     */
    public function showErrors(): array
    {
        return array_filter($this->errors);
    }

    /**
     * Validates if there is a defined
     * value for the variable
     *
     * @param mixed|null $value
     *
     * @return string
     */
    private function required(mixed $value): string
    {
        return !empty($value) ? "" : 'Empty mandatory field.';
    }

    /**
     * Checks if the value
     * is really a string
     *
     * @param mixed|null $value
     *
     * @return string
     */
    private function string(mixed $value): string
    {
        return is_string($value) ? "" : 'The field is not text.';
    }

    /**
     * Checks if the value contains a minimum
     * number of characters
     *
     * @param int $min
     * @param string $value
     *
     * @return string
     */
    private function min(string $value, int $min): string
    {
        return !empty($value) && strlen($value) > $min ? "" : 'The value does not contain the expected minimum.';
    }

    /**
     * Checks if the value contains a maximum
     * number of characters
     *
     * @param int $max
     * @param string $value
     *
     * @return string
     */
    private function max(string $value, int $max): string
    {
        return !empty($value) && strlen($value) <= $max ? "" : 'This value exceeds the limit stipulated for the field.';
    }

    /**
     * Check if the value is an email
     *
     * @param string $value
     *
     * @return string
     */
    private function email(string $value): string
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) ? "" : 'No valid email was found.';
    }

    /**
     * Check if the value is a number
     *
     * @param mixed $value
     *
     * @return string
     */
    private function number(mixed $value): string
    {
        return is_numeric($value) ? "" : 'It is not a numerical value.';
    }

}