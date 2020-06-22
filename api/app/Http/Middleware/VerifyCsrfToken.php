<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    // protected $localApi = 'http://lite.ru/faximile/api/public';

    protected $except = [
        'stripe/*',

        'http://bolderfest.ru/faximile/api/public/post/*',
        'http://lite.ru/faximile/api/public/post/*',

        'http://bolderfest.ru/faximile/api/public/documents/add/*',
        'http://lite.ru/faximile/api/public/documents/add/*',

        'http://bolderfest.ru/faximile/api/public/faximile/auth/*',
        'http://bolderfest.ru/faximile/api/public/upload/documents/*',

    ];
}
