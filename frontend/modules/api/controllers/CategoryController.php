<?php
/**
 * @copyright Copyright (c) 2021 VadimTs
 * @link http://good-master.com.ua/
 * Creator: VadimTs
 * Date: 10.02.2021
 */

namespace frontend\modules\api\controllers;


use frontend\modules\api\models\Category;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\Response;

class CategoryController extends ActiveController
{
    public $modelClass = Category::class;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function  actions()
    {
        $params = parent::actions();
        unset($params['index']);
        // Yii::debug(Yii::$app->request->hostInfo);
        return $params;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        $behaviors['contentNegotiator']['formats'] = ['application/json'=>Response::FORMAT_JSON];
        return $behaviors;
    }
    /**
     * @OA\Get(path="/category",
     *   summary="Category",
     *   tags={"Category"},
     *   @OA\Parameter(
     *     name="expand",
     *     in="header",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns News objects",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/Category"),
     *     ),
     *   ),
     * )
     */

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Category::find()->roots(),
        ]);
        $dataProvider->sort->defaultOrder = ['root'=>SORT_ASC,'lft'=>SORT_ASC];
        //$dataProvider->pagination->defaultPageSize = 1;
        return $dataProvider;
    }
}