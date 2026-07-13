<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'admins',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    | Três guards: admin (web), empresa, aluno
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'empresa' => [
            'driver' => 'session',
            'provider' => 'empresas',
        ],

        'aluno' => [
            'driver' => 'session',
            'provider' => 'alunos',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'empresas' => [
            'driver' => 'eloquent',
            'model' => App\Models\Empresa::class,
        ],

        'alunos' => [
            'driver' => 'eloquent',
            'model' => App\Models\Aluno::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        // Cada guard usa sua própria tabela de tokens. Isso evita que um mesmo
        // e-mail cadastrado em duas tabelas diferentes (ex: um aluno e uma
        // empresa com o mesmo endereço) colida em um token compartilhado.
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens_admins',
            'expire' => 60,
            'throttle' => 60,
        ],
        'empresas' => [
            'provider' => 'empresas',
            'table' => 'password_reset_tokens_empresas',
            'expire' => 60,
            'throttle' => 60,
        ],
        'alunos' => [
            'provider' => 'alunos',
            'table' => 'password_reset_tokens_alunos',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => 10800,

];
