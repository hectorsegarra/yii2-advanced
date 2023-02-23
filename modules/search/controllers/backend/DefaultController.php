<?php

namespace modules\search\controllers\backend;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use modules\users\models\User;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * Esto se utiliza para el widget Search.
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
                    'roles' => ['@'] //todos los usuarios autenticados.
                    //'roles' => ['rol']
                ]
            ]
        ];
    }

    /**
     * Lists all Cargador models.
     * @return mixed
     */
    public function actionInfo()
    {
        return $this->render('info');
    }

    

    public function actionBuscar(){
        $q = Yii::$app->request->get('q');
        $query = User::find()
            ->joinWith('profile as p')
            ->where(['like', 'CONCAT(p.first_name, ", ", p.last_name)', $q])
            ->orWhere(['like', 'email', $q]);
            
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $this->render('buscar', [
            'dataProvider' => $dataProvider,
            'q' => $q,
        ]);
    }
}