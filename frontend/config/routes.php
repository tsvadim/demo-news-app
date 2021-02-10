<?php
return [
    '/' => 'site/index',
    '<_a:(logout|login|signup|reset-password|verify-email)>' => 'site/<_a>',
    '<_a:(about|contact)>' => 'site/<_a>',
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'api/news',
        'except' => ['delete', 'create', 'update'],
        //'only' => ['index','view','is-exists','slug'],
        'extraPatterns' => [
            'GET,HEAD <id:\d[\d,]*>' => 'view',
            'GET,HEAD is-exists/<id:\d[\d,]*>' => 'is-exists',
            'GET,HEAD <slug:[\w\-]+>' => 'slug',
        ],
        'pluralize'=>false
    ],
    '<_m:(api)>/<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_m>/<_c>/<_a>',
    '<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_c>/<_a>',

//    'logout' => 'site/logout',
//    'login' => 'site/login',
//    'signup' => 'site/signup',
//    'reset-password' => 'site/reset-password',
//    'verify-email' => 'site/verify-email',
//    'request-password-reset' => 'site/request-password-reset',
//    'resend-verification-email' => 'site/resend-verification-email',
];