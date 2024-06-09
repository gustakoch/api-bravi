<?php

namespace App\Http;

class Response
{
    public static function array(bool $error, string $message = '', array $data = []): array
    {
        return [compact('error', 'message', 'data')];
    }
}
