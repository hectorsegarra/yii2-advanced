<?php

namespace modules\rbac\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\InvalidConfigException;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\base\Action;
use yii\web\Response; 
use Exception;
use modules\rbac\models\Assignment;
use modules\users\models\User;
use modules\rbac\Module;

/**
 * Class AssignController
 * @package modules\rbac\controllers
 */
class AssignController extends Controller
{
    /** @var User */
    private $user;

    /**
     * @param Action $action
     * @return bool
     * @throws InvalidConfigException
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (empty(Yii::$app->controller->module->params['userClass'])) {
            throw new InvalidConfigException(
                Module::translate(
                    'module',
                    'You must specify the User class in the module settings.'
                )
            );
        }
        $this->user = new Yii::$app->controller->module->params['userClass']();
        if (!($this->user instanceof IdentityInterface)) {
            throw new InvalidArgumentException(
                Module::translate(
                    'module',
                    'Class {:userClassName} does not implement interface yii\web\IdentityInterface.',
                    [
                        ':userClassName' => get_class($this->user)
                    ]
                )
            );
        }
        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['managerRbac']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'revoke' => ['POST']
                ]
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $assignModel = new Assignment();
        $userModel = $this->user;
        $users = $userModel::find()->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $users,
            'sort' => [
                'attributes' => ['userId', 'role']
            ],
            'pagination' => [
                'defaultPageSize' => 25
            ]
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'assignModel' => $assignModel
        ]);
    }

    /**
     * @param string|int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $assignModel = new Assignment();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'assignModel' => $assignModel
        ]);
    }

    /**
     * @param string|int $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionUpdate($id)
    {
        $modelUsuario=User::findOne($id);
        $assignModel = new Assignment();
        $model = new Assignment([
            'user' => $this->findModel($id)
        ]);
        if ($model->load(Yii::$app->request->post())) {
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($model->role);
            // отвязываем роли если есть
            if ($auth->getRolesByUser($model->user->id)) {
                $auth->revokeAll($model->user->id);
            }
            // Привязываем новую роль
            if ($auth->assign($role, $model->user->id)) {
                return $this->redirect(['view', 'id' => $model->user->id]);
            }
        }
        $model->role = $model->getRoleUser($id);
        return $this->render('update', [
            'model' => $model,
            'modelUsuario'=>$modelUsuario,
            'assignModel' => $assignModel
        ]);
    }

    /**
     * @param string|int $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionRevoke($id)
    {
        $model = $this->findModel($id);
        $auth = Yii::$app->authManager;
        /** @var yii\web\Session $session */
        $session = Yii::$app->session;
        if ($auth->getRolesByUser($model->id)) {
            if ($auth->revokeAll($model->id)) {
                $session->setFlash(
                    'success',
                    Module::translate(
                        'module',
                        'User "{:userFullName}" successfully unassigned.',
                        [
                            ':userFullName' => $model->userFullName
                        ]
                    )
                );
            } else {
                $session->setFlash(
                    'error',
                    Module::translate(
                        'module',
                        'Error!'
                    )
                );
            }
        } else {
            $session->setFlash(
                'warning',
                Module::translate(
                    'module',
                    'User "{:userFullName}" is not attached to any role!',
                    [
                        ':userFullName' => $model->userFullName
                    ]
                )
            );
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string|int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $userModel = $this->user;
        if (($model = $userModel::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(
            Module::translate(
                'module',
                'The requested page does not exist.'
            )
        );
    }
}
