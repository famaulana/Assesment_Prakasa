<?php

namespace App\Lib;

class GlobalFunction
{

    public function __construct()
    {
    }

    public static function responseFormat(ReturnCode $returnCode, mixed $data, null|string $customMessage): array
    {
        return array('code' => $returnCode->code(), 'info' => !empty($customMessage) ? $customMessage : $returnCode->msg(), 'data' => $data);
    }
}
