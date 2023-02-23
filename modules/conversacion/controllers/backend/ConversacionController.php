<?php

namespace modules\conversacion\controllers\backend;

use Yii;
use modules\conversacion\models\Conversacion;
use modules\conversacion\models\ConversacionParticipantes;
use modules\conversacion\models\search\ConversacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ConversacionController implements the CRUD actions for Conversacion model.
 */
class ConversacionController extends Controller
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
     * Lists all Conversacion models.
     * @return mixed
     */
    // public function actionIndex()
    // {
    //     $searchModel = new ConversacionSearch();
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }

    // /**
    //  * Displays a single Conversacion model.
    //  * @param integer $id
    //  * @return mixed
    //  * @throws NotFoundHttpException if the model cannot be found
    //  */
    // public function actionView($id)
    // {
    //     return $this->render('view', [
    //         'model' => $this->findModel($id),
    //     ]);
    // }

    /**
     * Creates a new Conversacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($tipo)
    {
        $model = new Conversacion();
        $model->fecha_creacion = date("Y-m-d H:i:s");

        if($tipo=="grupales"){
            $model->grupal=1;

        }else{
            $model->grupal=0;

        }

        //comprueba el modleo antes de guardarlo en la base de datos
        if($model->load(Yii::$app->request->post())){
            if($model->validate()){
                if($model->save()){
                    $modelParticipantes = new ConversacionParticipantes();
                    $modelParticipantes->conversacion_id = $model->id;
                    $modelParticipantes->usuario_id = Yii::$app->user->id;
                    $modelParticipantes->entra_el = date("Y-m-d H:i:s");
                    $modelParticipantes->administrador=1;
                    $modelParticipantes->ver_mensajes_anteriores=1;
                    $modelParticipantes->ultima_leida = date("Y-m-d H:i:s");
                    if($modelParticipantes->save()){
                        if($tipo=="personales"){
                            //si es personal hay que crear el participante del otro usuario
                            $modelParticipantes = new ConversacionParticipantes();
                            $modelParticipantes->conversacion_id = $model->id;
                            $modelParticipantes->usuario_id = Yii::$app->request->post("otro_usuario_id");
                            $modelParticipantes->entra_el = date("Y-m-d H:i:s");
                            $modelParticipantes->administrador=0;
                            $modelParticipantes->ver_mensajes_anteriores=1;
                            $modelParticipantes->ultima_leida = null;
                            if($modelParticipantes->save()){
                                return $this->redirect(['/conversacion/ver-todas','tipo'=>$tipo]);
                            }else{
                                print_r($modelParticipantes->getErrors());
                                die;
                            }
                        }
                        return $this->redirect(['/conversacion/ver-todas','tipo'=>$tipo]);
                    }else{
                        print_r($modelParticipantes->getErrors());
                        die;
                    }
                }
            }else{
                print_r($model->getErrors());
                die;
            }
        }
        return $this->render('create', [
            'model' => $model,
            'tipo' => $tipo,
        ]);
    }

    

    
    /**
     * Finds the Conversacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Conversacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Conversacion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
