<?php
/**
 * @copyright Copyright (c) 2016 VadimTs
 * @link http://good-master.com.ua/
 * Creator: VadimTs
 * Date: 28.05.2016
 */

namespace console\controllers;


use yii\console\Controller;

class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }
}