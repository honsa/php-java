<?php
namespace PHPJava\Kernel\Types;

class _Byte extends Type
{
    const DEFAULT_VALUE = 0;

    protected $nameInJava = 'byte';
    protected $nameInPHP = 'int';

    const MIN = -128;
    const MAX = 127;

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
