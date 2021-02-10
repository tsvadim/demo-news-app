<?php

namespace backend\controllers;

use backend\models\rbac\AuthItem;
use backend\models\rbac\search\AuthItem as AuthItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use Yii;
use yii\web\Response;
use yii\helpers\Html;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class PermissionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'view', 'assign','role-search'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('permission.index');
                        }
                    ],
                    [
                        'actions' => ['view', 'assign','role-search'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('permission.view');
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
        $searchModel = new AuthItemSearch(['type' => Item::TYPE_PERMISSION]);
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);
        $authManager = Yii::$app->getAuthManager();
        $available = $assigned = [
            'Permission' => [],
            'Routes' => [],
        ];
        $children = array_keys($authManager->getChildren($id));
        $children[] = $id;
        foreach ($authManager->getPermissions() as $name => $role) {
            if (in_array($name, $children)) {
                continue;
            }
            $available[$name[0] === '/' ? 'Routes' : 'Permission'][$name] = "[{$name}] - {$role->description}";
        }
        foreach ($authManager->getChildren($id) as $name => $child) {
            $assigned[$name[0] === '/' ? 'Routes' : 'Permission'][$name] = "[{$name}] - {$child->description}";
        }

        $available = array_filter($available);
        $assigned = array_filter($assigned);

        return $this->render('view', ['model' => $model, 'available' => $available, 'assigned' => $assigned]);
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
            'Permission' => [],
            'Routes' => [],
        ];
        $authManager = Yii::$app->getAuthManager();
        if ($target == 'available') {
            $children = array_keys($authManager->getChildren($id));
            $children[] = $id;
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
                    $result[$name[0] === '/' ? 'Routes' : 'Permission'][$name] = "[{$name}] - {$child->description}";
                }
            }
        }

        return Html::renderSelectOptions('', array_filter($result));
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
/*
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
*/

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param  string $id
     * @return mixed
     */
/*
public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->name]);
        }

        return $this->render('update', ['model' => $model,]);
    }
*/

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  string $id
     * @return mixed
     */
/*
public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->getAuthManager()->remove($model->item);

        return $this->redirect(['index']);
    }
*/

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
        $parent = $manager->getPermission($id);
        $error = [];
        if ($action == 'assign') {
            foreach ($roles as $role) {
                $child = $manager->getPermission($role);
                try {
                    $manager->addChild($parent, $child);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        } else {
            foreach ($roles as $role) {
                $child = $manager->getPermission($role);
                try {
                    $manager->removeChild($parent, $child);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        }

        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        return [$this->actionRoleSearch($id, 'available', $post['search_av']),
            $this->actionRoleSearch($id, 'assigned', $post['search_asgn']),
            $error];
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
        $item = Yii::$app->getAuthManager()->getPermission($id);
        if ($item) {
            return new AuthItem($item);
        } else {
            throw new NotFoundHttpException(Yii::t('app','The requested page does not exist.'));
        }
    }
}
