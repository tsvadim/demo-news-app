<?php
/**
 * @copyright Copyright (c) 2021 VadimTs
 * @link http://good-master.com.ua/
 * Creator: VadimTs
 * Date: 10.02.2021
 */

namespace frontend\modules\api\controllers;


use frontend\modules\api\models\Category;
use frontend\modules\api\models\News;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\Response;

class NewsController extends ActiveController
{
    public $modelClass = News::class;
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
     * @OA\Get(path="/news",
     *   summary="News",
     *   tags={"News"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page",
     *         required=false,
     *         @OA\Schema(
     *           type="integer",
     *           default="1"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="per-page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(
     *           type="integer",
     *           default="20"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Category Id",
     *         required=false,
     *         @OA\Schema(
     *           type="integer"
     *         ),
     *     ),
     *   @OA\Parameter(
     *         description="Add relation items (example: categories)",
     *         in="query",
     *         name="expand",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *           example="categories"
     *         )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns News objects",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/News"),
     *     ),
     *   ),
     * )
     */

    public function actionIndex()
    {
        $topicId = \Yii::$app->request->get('category_id');
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->joinWith('categories'),
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id' => SORT_DESC,
                ],
                'attributes' => ['id', 'created_at'],
            ],
        ]);
        $dataProvider->query->andfilterWhere(['tree_id'=>$topicId]);
//        $dataProvider->sort->defaultOrder = ['root'=>SORT_ASC,'lft'=>SORT_ASC];
        //$dataProvider->pagination->defaultPageSize = 1;
        return $dataProvider;
    }
}