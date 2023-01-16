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
            'active' => strpos($url,'update'),//comprrobamos que la URL tenga la cadena
        ],
    ],
    'encodeLabels'=>false,
    'headerOptions' => [
        'style' => "color:#7a7a7a;"
    ], 
]);
?>
