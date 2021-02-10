<?php
/**
 * @copyright Copyright (c) 2020 VadimTs
 * @link http://good-master.com.ua/
 * Creator: VadimTs
 * Date: 18.02.2020
 */

namespace common\helpers;

use Yii;

/**
 * ParamHelper provide system params
 * @package common\helpers
 */
class ParamHelper
{
    /**
     * Необходимость E-mail подтверждения  для новых пользователей
     * @return bool
     */
    public static function isEnableEmailVerification()
    {
        return Yii::$app->params['enableEmailVerification'];
    }
}