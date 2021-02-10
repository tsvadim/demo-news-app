<?php

namespace backend\controllers;

use Yii;
use backend\models\users\User;
use backend\models\users\search\UsersSearch;
use yii\base\ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UsersController implements the CRUD actions for ManageUsers model.
 */
class UsersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view','update','create','delete'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('users.index');
                        }
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->can('users.index') || \Yii::$app->user->can('users.create') || \Yii::$app->user->can('users.update');
                        }
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('users.update');
                        }
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('users.create');
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('users.delete');
                        }
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ManageUsers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ManageUsers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ManageUsers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        $model->setScenario(User::SCENARIO_CREATE_PASSWORD);
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->post('ajax') == $model->formName()) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return \yii\widgets\ActiveForm::validate($model);
            }
            if($model->save()){
                $auth = Yii::$app->authManager;
                $auth->revokeAll($model->getId());
                if(is_array($model->roles) && count($model->roles)>0)
                {
                    foreach($model->roles as $role){
                        if($authRole =  $auth->getRole($role))
                            $auth->assign($authRole, $model->getId());
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else
                return $this->render('create', [
                    'model' => $model,
                ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $postForm = Yii::$app->request->post($model->formName());

        //If set new pass
        if( (isset($postForm['new_pass']) && !empty($postForm['new_pass'])) || (isset($postForm['new_pass_repeat']) && !empty($postForm['new_pass_repeat'])))
            $model->setScenario(User::SCENARIO_CREATE_PASSWORD);

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->post('ajax') == $model->formName()) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return \yii\widgets\ActiveForm::validate($model);
            }

            if($model->save())
            {
                $auth = Yii::$app->authManager;
                $auth->revokeAll($model->getId());
                if(is_array($model->roles) && count($model->roles)>0)
                {
                    foreach($model->roles as $role){
                        if($authRole =  $auth->getRole($role))
                            $auth->assign($authRole, $model->getId());
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else     return $this->render('update', [
                'model' => $model,
            ]);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        /** @var User $model */
        $model = $this->findModel($id);

        if($model->id === Yii::$app->user->id)
           throw new HttpException(400,Yii::t('app','You can not delete yourself!'));


        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app','The requested page does not exist.'));
        }
    }
}
