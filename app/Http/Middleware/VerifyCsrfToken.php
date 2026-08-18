<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/',
        'finish',
        'notification/handler',
        'notification/moota',
        'notification/xendit',
        'notification/duitku',
        'notification/doku',
        'notification/muamalat',
        'api/v1/check-expired',
        'api/oauth/token',
        'va/bills',
        'va/payments'
    ];
}
