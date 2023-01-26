<?php
use yii\bootstrap\Tabs;

$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];


//$model->isNewRecord

$visibles=0;


echo Tabs::widget([
    'items' => [
        [
            'label' => '<i class="fas fa-file-alt fa-fw"></i> Datos',
            'url' => '/admin/user/'.$modelUsuario->id.'/update',
            'active' => strpos($url,'admin/user/'),//comprrobamos que la URL tenga la cadena
        ],
        [
            'label' => '<i class="fas fa-user-tag fa-fw"></i> Permisos y roles',
            'url' => '/admin/rbac/assign/'.$modelUsuario->id.'/update',
            'active' => strpos($url,'/rbac/assign'),//comprrobamos que la URL tenga la cadena
            'visible' => 1
        ],
    ],
    'encodeLabels'=>false,
    'headerOptions' => [
        'style' => "color:#7a7a7a;"
    ], 
]);
?>
