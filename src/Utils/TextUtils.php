<?php

namespace App\Utils;

class TextUtils
{
    public static function getStringLoweredAndUCFirst(string $string): string
    {
        return ucfirst(strtolower($string));
    }
}
