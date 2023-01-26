<?php

use yii\helpers\Html;
use modules\rbac\Module;

/* @var $this yii\web\View */
/* @var $model modules\rbac\models\Assignment */

$this->title = Module::translate('module', 'Permisos y roles');
$this->params['title']['small'] = $modelUsuario->userFullName;

//Configuramos el breadcrumb
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['/user/index']];
$this->params['breadcrumbs'][] = ['label' => $modelUsuario->userFullName, 'url' => ['/user/update', 'id' => $modelUsuario->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="rbac-assign-update">

    <?=$this->render('@modules/users/views/backend/default/tabs/_menu_tabs',['modelUsuario'=>$modelUsuario]);?>

    <div class="box box-primary">        
        <div class="box-body">
            <div class="row">    
                <div class="col-md-6">
                    <?= $this->render('_form', [
                        'model' => $model
                    ]) 
                    ?>
                </div>

                <div class="col-md-6">
                    <?php
                    /** @var string $role */
                    $role = $assignModel->getRoleUser($modelUsuario->id);
                    $auth = Yii::$app->authManager;
                    if ($permissionsRole = $auth->getPermissionsByRole($role)) : ?>
                        <strong><?= Module::translate('module', 'Permisos otorgados por su rol actual.') ?></strong>
                        <ul>
                            <?php foreach ($permissionsRole as $value) {
                                echo Html::tag(
                                    'li',
                                    $value->description . '(' . $value->name .' )'
                                ) . PHP_EOL;
                            } ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <div class="box-footer"></div>
    </div>
</div>
