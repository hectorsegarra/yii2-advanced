<?php

namespace modules\conversacion\controllers\backend;

use yii\web\Controller;
use yii\filters\AccessControl;
use modules\rbac\models\Permission;
use modules\conversacion\models\Conversacion;
use modules\conversacion\models\ConversacionMensaje;
use modules\conversacion\models\ConversacionParticipantes;
use Yii;


/**
 * Class DefaultController
 * @package modules\conversacion\controllers\backend
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
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Permission::PERMISSION_VIEW_ADMIN_PAGE]
                    ]
                ]
            ]
        ];
    }

    /**
     * Displays index page.
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionChat($conversacion_id)
    {
        $conversacion=Conversacion::findOne($conversacion_id);

        //actualizar el campo ultima_leida del modelo ConversacionParticipantes
        $conversacionParticipantes=ConversacionParticipantes::find()->where(['conversacion_id'=>$conversacion_id,'usuario_id'=>Yii::$app->user->id])->one();
        $conversacionParticipantes->ultima_leida=date("Y-m-d H:i:s");
        $conversacionParticipantes->save();
        

        if (Yii::$app->request->post()){
            $mensaje = new ConversacionMensaje();
            $mensaje->conversacion_id=$conversacion_id;
            $mensaje->sender_id=Yii::$app->user->id;
            $mensaje->mensaje=Yii::$app->request->post("mensaje");
            $mensaje->created_at=date("Y-m-d H:i:s");
            $mensaje->save();
        }
        return $this->render('chat',[
            'conversacion'=>$conversacion,
        ]);
    }

    
    public function actionVerTodas($tipo)
    {   
        $conversaciones=Conversacion::getMisConversaciones($tipo);
        
        return $this->render('ver_todas',[
            'conversaciones'=>$conversaciones,
            'tipo'=>$tipo,
        ]);
    }

    public function actionConvertirEnGrupal($conversacion_id)
    {
        //ver si el usuario actual tiene permiso apra administrar la conversacion
        $usuario_id=Yii::$app->user->id;
        $participante=ConversacionParticipantes::find()->where('conversacion_id=:conversacion_id and usuario_id=:usuario_id and administrador=1', [':conversacion_id' => $conversacion_id, ':usuario_id' => $usuario_id])->one();
        if($participante==null){
            throw new \yii\web\HttpException(403, 'No tiene permiso para administrar esta conversación');
        } 

        $conversacion=Conversacion::findOne($conversacion_id);
        $conversacion->grupal=1;
        $conversacion->save();
        return $this->redirect(['/conversacion/ver-todas','tipo'=>'grupales']);
    }


    public function actionSalirDeLaConversacion($conversacion_id)
    {
        $conversacion=Conversacion::findOne($conversacion_id);
        $conversacion->getConversacionParticipante(Yii::$app->user->id)->one()->delete();

        //si no quedan mas participanes elimina la conversacion
        if($conversacion->conversacionParticipantes==null){
            $conversacion->delete();
        }

        return $this->redirect(['/conversacion/ver-todas','tipo'=>'grupales']);
    }

    

    public function actionQuitarAdministracion()
    {
        if(Yii::$app->request->isAjax) {
            $request = Yii::$app->request;

            $conversacion_id = $request->post('conversacionId');
            $user_id = $request->post('participanteId');

            //si solo hay un usuario que sea administrador no se puede eliminar
            $participantes=ConversacionParticipantes::find()->where('conversacion_id=:conversacion_id and administrador=1', [':conversacion_id' => $conversacion_id])->all();
            if(count($participantes)==1){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $session = Yii::$app->session;
                $session->setFlash('error', 'No se puede quitar el permiso de administración porque es el unico administrador de esta conversacion.');
                return ['success' => false, 'message' => 'No se puede quitar el permiso de administración porque es el unico administrador de esta conversacion.' ];
            }

            //ver si el usuario actual tiene permiso apra administrar la conversacion
            $usuario_id=Yii::$app->user->id;
            $participante=ConversacionParticipantes::find()->where('conversacion_id=:conversacion_id and usuario_id=:usuario_id and administrador=1', [':conversacion_id' => $conversacion_id, ':usuario_id' => $usuario_id])->one();
            if($participante==null){
                throw new \yii\web\HttpException(403, 'No tiene permiso para administrar esta conversación');
            }

            $participanteConversacion = ConversacionParticipantes::find()->where('conversacion_id=:conversacion_id and usuario_id=:user_id', [':conversacion_id' => $conversacion_id, ':user_id' => $user_id])->one();

            if($participanteConversacion) {
                $participanteConversacion->administrador=0;
                if ($participanteConversacion->save()) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    $session = Yii::$app->session;
                    $session->setFlash('success', 'Administracion retirada correctamente');
                    return ['success' => true, 'message' => 'Administracion retirada correctamente'];
                } else {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    $session = Yii::$app->session;
                    $session->setFlash('error', $participanteConversacion->getErrors());
                    return ['success' => false, 'message' => $participanteConversacion->getErrors()];
                }
            } else {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $session = Yii::$app->session;
                $session->setFlash('error', 'Error al retirar administracion, no hay conversación con ese participante');
                return ['success' => false, 'message' => 'Error al retirar administracion, no hay conversación con ese participante'];
            }
        }
    }

    public function actionDarAdministracion()
    {
        if(Yii::$app->request->isAjax) {
            $request = Yii::$app->request;

            $conversacion_id = $request->post('conversacionId');
            $user_id = $request->post('participanteId');

            //ver si el usuario actual tiene permiso apra administrar la conversacion
            $usuario_id=Yii::$app->user->id;
            $participante=ConversacionParticipantes::find()->where('conversacion_id=:conversacion_id and usuario_id=:usuario_id and administrador=1', [':conversacion_id' => $conversacion_id, ':usuario_id' => $usuario_id])->one();
            if($participante==null){
                throw new \yii\web\HttpException(403, 'No tiene permiso para administrar esta conversación');
            }

            $participanteConversacion = ConversacionParticipantes::find()->where('conversacion_id=:conversacion_id and usuario_id=:user_id', [':conversacion_id' => $conversacion_id, ':user_id' => $user_id])->one();

            if($participanteConversacion) {
                $participanteConversacion->administrador=1;
                if ($participanteConversacion->save()) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    $session = Yii::$app->session;
                    $session->setFlash('success', 'Administracion otorgada correctamente');
                    return ['success' => true, 'message' => 'Administracion otorgada correctamente'];
                } else {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    $session = Yii::$app->session;
                    $session->setFlash('error', $participanteConversacion->getErrors());
                    return ['success' => false, 'message' => $participanteConversacion->getErrors()];
                }
            } else {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $session = Yii::$app->session;
                $session->setFlash('error', 'Error al otorgar administracion, no hay conversación con ese participante');
                return ['success' => false, 'message' => 'Error al otorgar administracion, no hay conversación con ese participante'];
            }
        }
    }



    public function actionGestionarParticipantes($conversacion_id)
    {
        //ver si el usuario actual tiene permiso apra administrar la conversacion
        $usuario_id=Yii::$app->user->id;
        $participante=ConversacionParticipantes::find()->where('conversacion_id=:conversacion_id and usuario_id=:usuario_id and administrador=1', [':conversacion_id' => $conversacion_id, ':usuario_id' => $usuario_id])->one();
        if($participante==null){
            throw new \yii\web\HttpException(403, 'No tiene permiso para administrar esta conversación');
        }

        $conversacion=Conversacion::findOne($conversacion_id);

        return $this->render('gestionar_participantes',[
            'conversacion'=>$conversacion,
            'tipo'=>'grupales',
        ]);
    }

    public function actionEliminarParticipante()
    {
        if(Yii::$app->request->isAjax) {
            $request = Yii::$app->request;

            $conversacion_id = $request->post('conversacionId');
            $user_id = $request->post('participanteId');

            //si solo hay un usuario que sea administrador no se puede eliminar
            $participantes=ConversacionParticipantes::find()->where('conversacion_id=:conversacion_id and administrador=1', [':conversacion_id' => $conversacion_id])->all();
            if(count($participantes)==1){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $session = Yii::$app->session;
                $session->setFlash('error', 'No se puede eliminar el participante porque es el único administrador de la conversación, si quiere borrarlo puede salir a la vista donde estan todas las conversaciones y pulsar el boton salir de la conversacion.');
                return ['success' => false, 'message' => 'No se puede eliminar el participante porque es el único administrador de la conversación'];
            }

            //ver si el usuario actual tiene permiso apra administrar la conversacion
            $usuario_id=Yii::$app->user->id;
            $participante=ConversacionParticipantes::find()->where('conversacion_id=:conversacion_id and usuario_id=:usuario_id and administrador=1', [':conversacion_id' => $conversacion_id, ':usuario_id' => $usuario_id])->one();
            if($participante==null){
                throw new \yii\web\HttpException(403, 'No tiene permiso para administrar esta conversación');
            }

            $participanteConversacion = ConversacionParticipantes::find()->where('conversacion_id=:conversacion_id and usuario_id=:user_id', [':conversacion_id' => $conversacion_id, ':user_id' => $user_id])->one();

            if($participanteConversacion) {
                $participanteConversacion->delete();

            //si no quedan mas participanes elimina la conversacion
            $conversacion=Conversacion::findOne($conversacion_id);
            if($conversacion->conversacionParticipantes==null){
                if($conversacion->delete()){
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    $session = Yii::$app->session;
                    $session->setFlash('success', 'Conversación eliminada correctamente');
                    return ['success' => true, 'message' => 'Conversación eliminada correctamente'];
                } else {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    $session = Yii::$app->session;
                    $session->setFlash('error', $conversacion->getErrors());
                    return ['success' => false, 'message' => $conversacion->getErrors()];
                }
            }
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    $session = Yii::$app->session;
                    $session->setFlash('success', 'Participante eliminado correctamente');
                    return ['success' => true, 'message' => 'Participante eliminado correctamente'];
            } else {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $session = Yii::$app->session;
                $session->setFlash('error', 'Error al eliminar participante, no hay conversación con ese participante');
                return ['success' => false, 'message' => 'Error al eliminar participante, no hay conversación con ese participante'];
            }
        }
    }

    public function actionAnadirParticipante()
    {
        $request = Yii::$app->request;

        $conversacion_id = $request->post('conversacion_id');
        $user_id = $request->post('usuario_id');
        $ver_mensajes_anteriores= $request->post('ver_mensajes_anteriores');
        $administrador= $request->post('administrador');

        if($ver_mensajes_anteriores==null){
            $ver_mensajes_anteriores=0;
        }
        if($administrador==null){
            $administrador=0;
        }

        //ver si el usuario actual tiene permiso apra administrar la conversacion
        $usuario_id=Yii::$app->user->id;
        $participante=ConversacionParticipantes::find()->where('conversacion_id=:conversacion_id and usuario_id=:usuario_id and administrador=1', [':conversacion_id' => $conversacion_id, ':usuario_id' => $usuario_id])->one();
        if($participante==null){
            throw new \yii\web\HttpException(403, 'No tiene permiso para administrar esta conversación');
        }

        $modelParticipantes = new ConversacionParticipantes();
        $modelParticipantes->usuario_id=$user_id;
        $modelParticipantes->conversacion_id=$conversacion_id;
        $modelParticipantes->entra_el = date("Y-m-d H:i:s");
        $modelParticipantes->administrador=$administrador;
        $modelParticipantes->ultima_leida = null;
        $modelParticipantes->ver_mensajes_anteriores = $ver_mensajes_anteriores;
        if($modelParticipantes->save()){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $session = Yii::$app->session;
            $session->setFlash('success', 'Participante añadido correctamente');
            return $this->redirect(['/conversacion/gestionar-participantes', 'conversacion_id' => $conversacion_id]);
        }else{
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $session = Yii::$app->session;
            $session->setFlash('error', $modelParticipantes->getErrors());
            return $this->redirect(['/conversacion/gestionar-participantes', 'conversacion_id' => $conversacion_id]);
        } 
        
    }

    public function actionUserList($conversacion_id=null, $q = null, $id = null) {
        //si $conversacion_id no es null
        if($conversacion_id!=null){
            //busco la conversacion
            $conversacion=Conversacion::findOne($conversacion_id);
            //saco los partipiantes de la conversacion
            $participantes=$conversacion->conversacionParticipantes;
            //convierto $partcipantes en un array de ids
            $participantes_ids=[];
            foreach ($participantes as $participante){
                $participantes_ids[]=$participante->usuario_id;
            }
        } else {
            //si es nula el unico participante es el usuario actual de yii
            $participantes_ids=[Yii::$app->user->id];
        }

        //saco la lista de usuarios que no estan en la conversacion y que ademas coincidan con el nombre buscado
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query;
            $query->select(['user.id','CONCAT( profile.first_name,", ",profile.last_name ) AS text'])
                ->from('tbl_user as user, tbl_user_profile as profile')
                ->where('user.id=profile.user_id')
                ->andWhere(['like', 'CONCAT( profile.first_name,", ",profile.last_name )', $q])
                ->andWhere(['not in', 'user.id', $participantes_ids])
                ->limit(20);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => User::find($id)->userFullName];
        }
        return $out;
    }
}
