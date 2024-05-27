<?php

namespace App\Lib;

class GlobalValue
{
    public const PAGINATION = 10;

    public static function get(string $const)
    {
        $constArr = [
            self::PAGINATION => 'pagination',
        ];

        if (!isset($const)) {
            return $constArr;
        }

        return in_array($const, $constArr) ? intval(array_keys($constArr, $const)[0]) : null;
    }
}
