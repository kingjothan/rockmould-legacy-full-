<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\ValidatePostSize as BaseValidatePostSize;

class ValidatePostSize extends BaseValidatePostSize
{
    protected function getPostMaxSize(): int
    {
        // Allow up to 300MB posts for large image/video uploads
        $iniMax = parent::getPostMaxSize();
        $appMax = 300 * 1024 * 1024; // 300MB in bytes

        return max($iniMax, $appMax);
    }
}
