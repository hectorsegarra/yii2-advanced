<?php

namespace modules\users\controllers\backend;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use modules\users\models\LoginForm;
use modules\rbac\models\Permission;
use modules\rbac\models\Assignment;
use modules\users\models\User;
use modules\users\models\UserProfile;
use modules\users\models\search\UserSearch;
use modules\users\Module;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * Class DefaultController
 * @package modules\users\controllers\backend
 *
 * @property array $access
 * @property  array $verb
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => $this->getVerb(),
            'access' => $this->getAccess()
        ];
    }

    /**
     * @return array
     */
    private function getVerb()
    {
        return [
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['POST'],
                'logout' => ['POST']
            ]
        ];
    }

    /**
     * @return array
     */
    private function getAccess()
    {
        return [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'actions' => ['login'],
                    'allow' => true,
                    'roles' => ['?']
                ],
                [
                    'actions' => ['logout'],
                    'allow' => true,
                    'roles' => ['@']
                ],
                [
                    'allow' => true,
                    'roles' => [Permission::PERMISSION_MANAGER_USERS]
                ]
            ]
        ];
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = '//login';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->processCheckPermissionLogin();
        }
        return $this->render('login', [
            'model' => $model
        ]);
    }

    /**
     * @return Response
     */
    protected function processCheckPermissionLogin()
    {
        // If access to Backend is denied, reset authorization, write a message to the session
        // and move it to the login page
        /** @var yii\web\User $user */
        $user = Yii::$app->user;
        if (!$user->can(Permission::PERMISSION_VIEW_ADMIN_PAGE)) {
            $user->logout();
            /** @var yii\web\Session $session */
            $session = Yii::$app->session;
            $session->setFlash(
                'error',
                Module::translate(
                    'module',
                    'You do not have rights, access is denied.'
                )
            );
            return $this->goHome();
        }
        return $this->goBack();
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        $model = new LoginForm();
        $model->logout();
        return $this->goHome();
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $assignModel = new Assignment();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'assignModel' => $assignModel
        ]);
    }

    /**
     * Displays a single User model.
     * @param int|string $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        if ($model = $this->findModel($id)) {
            $assignModel = new Assignment([
                'user' => $model
            ]);
            return $this->render('view', [
                'model' => $model,
                'assignModel' => $assignModel
            ]);
        }
        return $this->redirect(['index']);
    }

    


    public function actionCreate()
    {
        $model = new User();
        $profile = new UserProfile();
        $model->scenario = $model::SCENARIO_ADMIN_CREATE;
        $model->status = $model::STATUS_WAIT;

        
        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password);
            if ($model->save()) {
                //guardo el perfil
                $profile->load(Yii::$app->request->post());
                $profile->user_id=$model->id;
                $profile->save();

                //Creamos el permiso de usuario en los permisos de Yii2
                $modelAssignment = new Assignment();
                $modelAssignment->userId=$model->id;
                $modelAssignment->roleName="user";
                $modelAssignment->save();
                
                return $this->redirect(['index', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'profile' => $profile,
        ]);
    }

    /**
     * @param int|string $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws Exception
     */
    /**
     * @param int|string $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $uploadFormModel = new UploadForm(['user_id'=>$id]);
        

        if ($model->load(Yii::$app->request->post()) && $model->profile->load(Yii::$app->request->post())) {
            if (!empty($model->password)) {
                $model->setPassword($model->password);
            }

            if ($model->save()) 
            echo "1";
            {
                if ($model->profile->validate()) {
                    // all inputs are valid
                    $model->profile->save();
                    } else {
                    // validation failed: $errors is an array containing error messages
                    print_r($model->profile->errors);
                    exit;
                    }
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'uploadFormModel' => $uploadFormModel,
        ]);
    }

    /**
     * @param int|string $id
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function actionSetStatus($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $result = $this->processChangeStatus($id);
            return [
                'result' => $result->statusLabelName
            ];
        }
        $this->processChangeStatus($id);
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param int|string $id
     * @return User
     * @throws NotFoundHttpException
     */
    protected function processChangeStatus($id)
    {
        $model = $this->findModel($id);
        /** @var User $identity */
        $identity = Yii::$app->user->identity;
        if ($model->id !== $identity->id && !$model->isSuperAdmin($model->id)) {
            $model->setStatus();
            $model->save(false);
        }
        return $model;
    }

    /**
     * @param $id
     * @return array|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionSendConfirmEmail($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $result = $this->processSendEmail($id);
            $name = (!$result->errors) ? 'success' : 'danger';
            return [
                'result' => $result->getLabelMailConfirm($name)
            ];
        }
        $this->processSendEmail($id);
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param $id
     * @return User
     * @throws Exception
     * @throws NotFoundHttpException
     */
    protected function processSendEmail($id)
    {
        $model = $this->findModel($id);
        $model->generateEmailConfirmToken();
        $model->save(false);
        $model->sendConfirmEmail();
        return $model;
    }

    /**
     * Action Generate new auth key
     * @param $id
     * @return array|Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionGenerateAuthKey($id)
    {
        $model = $this->processGenerateAuthKey($id);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'success' => $model->auth_key
            ];
        }
        return $this->redirect(['index']);
    }

    /**
     * Generate new auth key
     * @param $id
     * @return User
     * @throws Exception
     * @throws NotFoundHttpException
     */
    private function processGenerateAuthKey($id)
    {
        $model = $this->findModel($id);
        $model->generateAuthKey();
        $model->save();
        return $model;
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int|string $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws Exception
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!$model->isSuperAdmin()) {
            /** @var yii\web\Session $session */
            $session = Yii::$app->session;
            if ($model->isDeleted()) {
                $model->delete();
                $session->setFlash(
                    'success',
                    Module::translate(
                        'module',
                        'The user "{:name}" have been successfully deleted.',
                        [
                            ':name' => $model->username
                        ]
                    )
                );
            } else {
                /** @var $model SoftDeleteBehavior */
                $model->softDelete();
                $session->setFlash(
                    'success',
                    Module::translate(
                        'module',
                        'The user "{:name}" are marked as deleted.',
                        [
                            ':name' => $model->username
                        ]
                    )
                );
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int|string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Module::translate('module', 'The requested page does not exist.'));
    }
}
