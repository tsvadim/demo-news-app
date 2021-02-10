<?php

namespace frontend\modules\api\controllers;

use yii\web\Controller;

/**
 * @OA\Info(
 *   version="1.0",
 *   title="Application API",
 *   description="Server API"
 * ),
 * @OA\Server(
 *   url="/api",
 *   description="main server",
 * )
 */
/**
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT"
 * )
 * https://swagger.io/docs/specification/authentication/basic-authentication/
 *
 */
class DefaultController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }
}
