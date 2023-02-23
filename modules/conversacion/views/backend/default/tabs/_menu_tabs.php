<?php
use yii\bootstrap\Tabs;

$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

echo Tabs::widget([
    'items' => [
        [
            'label' => '<i class="fas fa-user fa-fw"></i> Personales',
            'url' => '/admin/conversacion/ver-todas?tipo=personales',
            'active' => strpos($url,'?tipo=personales'),//comprrobamos que la URL tenga la cadena
        ],
        [
            'label' => '<i class="fas fa-users fa-fw"></i> Grupales',
            'url' => '/admin/conversacion/ver-todas?tipo=grupales',
            'active' => strpos($url,'?tipo=grupales'),//comprrobamos que la URL tenga la cadena
            'visible' => 1
        ],
    ],
    'encodeLabels'=>false,
    'headerOptions' => [
        'style' => "color:#7a7a7a;"
    ], 
]);
?>
