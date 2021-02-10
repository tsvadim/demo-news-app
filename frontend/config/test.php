<?php
return [
    'id' => 'app-frontend-tests',
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,
            'showScriptName' => true,
            'rules' => [],
            'enableStrictParsing' => false,
           // 'normalizer' => false,
           // 'scriptUrl' => null,
        ],

        'request' => [
            'cookieValidationKey' => 'test',
        ],
    ],
];
