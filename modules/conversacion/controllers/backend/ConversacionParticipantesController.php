<?php

namespace modules\conversacion\controllers\backend;

use Yii;
use modules\conversacion\models\ConversacionParticipantes;
use modules\conversacion\models\search\ConversacionParticipantesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ConversacionParticipantesController implements the CRUD actions for ConversacionParticipantes model.
 */
class ConversacionParticipantesController extends Controller
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
     * Lists all ConversacionParticipantes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ConversacionParticipantesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ConversacionParticipantes model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionView($id)
    // {
    //     return $this->render('view', [
    //         'model' => $this->findModel($id),
    //     ]);
    // }

    /**
     * Creates a new ConversacionParticipantes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new ConversacionParticipantes();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['index']);
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    // /**
    //  * Updates an existing ConversacionParticipantes model.
    //  * If update is successful, the browser will be redirected to the 'view' page.
    //  * @param integer $id
    //  * @return mixed
    //  * @throws NotFoundHttpException if the model cannot be found
    //  */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['index']);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    // /**
    //  * Deletes an existing ConversacionParticipantes model.
    //  * If deletion is successful, the browser will be redirected to the 'index' page.
    //  * @param integer $id
    //  * @return mixed
    //  * @throws NotFoundHttpException if the model cannot be found
    //  */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the ConversacionParticipantes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConversacionParticipantes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConversacionParticipantes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
