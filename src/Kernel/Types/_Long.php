<?php
namespace PHPJava\Kernel\Types;

class _Long extends Type
{
    const DEFAULT_VALUE = 0;

    protected $nameInJava = 'long';
    protected $nameInPHP = 'integer';

    const MIN = -9223372036854775808;
    const MAX = 9223372036854775807;

    public static function isValid($value): bool
    {
        if (!ctype_digit((string) abs($value))) {
            return false;
        }
        return $value >= static::MIN && $value <= static::MAX;
    }

    protected static function filter($value)
    {
        return (int) $value;
    }
}
