<?php

namespace backend\controllers;

use backend\models\rbac\AuthItem;
use backend\models\rbac\search\AuthItem as AuthItemSearch;
use common\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\rbac\Item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class RoleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'view', 'assign','role-search','create','update','delete'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('role.index');
                        }
                    ],
                    [
                        'actions' => ['view', 'assign','role-search'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->can('role.create') || \Yii::$app->user->can('role.update');

                        }
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->can('role.create');

                        }
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->can('role.update');

                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->can('role.delete');

                        }
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    // everything else is denied
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new AuthItemSearch(['type' => Item::TYPE_ROLE]);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param  string $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionView($id)
    {
            $model = $this->findModel($id);
            $authManager = Yii::$app->getAuthManager();
            $available = $assigned = [
                'Roles' => [],
                'Permission' => [],
                'Routes' => [],
            ];
            $children = array_keys($authManager->getChildren($id));
            $children[] = $id;
            foreach ($authManager->getRoles() as $name => $role) {
                if (in_array($name, $children)) {
                    continue;
                }
                $available['Roles'][$name] = "[{$name}] - {$role->description}";
            }
            foreach ($authManager->getPermissions() as $name => $role) {
                if (in_array($name, $children)) {
                    continue;
                }
                $available[$name[0] === '/' ? 'Routes' : 'Permission'][$name] = "[{$name}] - {$role->description}";
            }

            foreach ($authManager->getChildren($id) as $name => $child) {
                if ($child->type == Item::TYPE_ROLE) {
                    $assigned['Roles'][$name] = "[{$name}] - {$child->description}";
                } else {
                    $assigned[$name[0] === '/' ? 'Routes' : 'Permission'][$name] = "[{$name}] - {$child->description}";
                }
            }
            $available = array_filter($available);
            $assigned = array_filter($assigned);

            return $this->render('view', ['model' => $model, 'available' => $available, 'assigned' => $assigned]);


    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionCreate()
    {

        $model = new AuthItem(null);
        $model->type = Item::TYPE_ROLE;
        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('create', ['model' => $model,]);
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param  string $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $attrId = $model->name;

        if ($model->load(Yii::$app->getRequest()->post()))  {

            if(in_array($attrId,array_keys(User::getRoleLabels()))){
                $model->name = $attrId;
            }

            if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        }
        }

        return $this->render('update', ['model' => $model,]);
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  string $id
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        Yii::$app->getAuthManager()->remove($model->item);

        return $this->redirect(['index']);
    }

    /**
     * Assign or remove items
     * @param string $id
     * @param string $action
     * @return array
     */
    public function actionAssign($id, $action)
    {
            $post = Yii::$app->getRequest()->post();
            $roles = $post['roles'];
            $manager = Yii::$app->getAuthManager();
            $parent = $manager->getRole($id);
            $error = [];
            if ($action == 'assign') {
                foreach ($roles as $role) {
                    $child = $manager->getRole($role);
                    $child = $child ? : $manager->getPermission($role);
                    try {
                        $manager->addChild($parent, $child);
                    } catch (\Exception $e) {
                        $error[] = $e->getMessage();
                    }
                }
            } else {
                foreach ($roles as $role) {
                    $child = $manager->getRole($role);
                    $child = $child ? : $manager->getPermission($role);
                    try {
                        $manager->removeChild($parent, $child);
                    } catch (\Exception $e) {
                        $error[] = $e->getMessage();
                    }
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [$this->actionRoleSearch($id, 'available', $post['search_av']),
                $this->actionRoleSearch($id, 'assigned', $post['search_asgn']),
                $error];


    }

    /**
     * Search role
     * @param string $id
     * @param string $target
     * @param string $term
     * @return array
     */
    public function actionRoleSearch($id, $target, $term = '')
    {
        $result = [
            'Roles' => [],
            'Permission' => [],
            'Routes' => [],
        ];
        $authManager = Yii::$app->authManager;
        if ($target == 'available') {
            $children = array_keys($authManager->getChildren($id));
            $children[] = $id;
            foreach ($authManager->getRoles() as $name => $role) {
                if (in_array($name, $children)) {
                    continue;
                }
                if (empty($term) or mb_stripos($name, $term) !== false) {
                    $result['Roles'][$name] = "[{$name}] - {$role->description}";
                }
            }
            foreach ($authManager->getPermissions() as $name => $role) {
                if (in_array($name, $children)) {
                    continue;
                }
                if (empty($term) or mb_stripos($name, $term) !== false) {
                    $result[$name[0] === '/' ? 'Routes' : 'Permission'][$name] = "[{$name}] - {$role->description}";
                }
            }
        } else {
            foreach ($authManager->getChildren($id) as $name => $child) {
                if (empty($term) or mb_stripos($name, $term) !== false) {
                    if ($child->type == Item::TYPE_ROLE) {
                        $result['Roles'][$name] = "[{$name}] - {$child->description}";
                    } else {
                        $result[$name[0] === '/' ? 'Routes' : 'Permission'][$name] = "[{$name}] - {$child->description}";
                    }
                }
            }
        }

        return Html::renderSelectOptions('', array_filter($result));


   }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  string $id
     * @throws \yii\web\NotFoundHttpException
     * @return AuthItem      the loaded model
     */
    protected function findModel($id)
    {
        $item = Yii::$app->getAuthManager()->getRole($id);
        if ($item) {
            return new AuthItem($item);
        } else {
            throw new NotFoundHttpException(Yii::t('app','The requested page does not exist.'));
        }
    }
}