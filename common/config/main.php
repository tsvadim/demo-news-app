<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'class'=>'yii\web\UrlManager',
            'enablePrettyUrl' => false,
            'showScriptName' => true,
            'rules' => [],
        ],
        'authManager'=>require __DIR__ . '/auth-manager.php',
    ],
];