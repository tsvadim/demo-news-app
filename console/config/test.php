<?php
return [
    'components'=>[
        'urlManager' => [
            'enablePrettyUrl' => false,
            'showScriptName' => true,
            'rules' => require  dirname(dirname(__DIR__)).'/frontend/config/routes.php',
            //'scriptUrl'=>null
        ],
    ]
];