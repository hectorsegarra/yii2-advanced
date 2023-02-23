<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use kartik\dynagrid\DynaGrid; //Para el grid avanzado
use kartik\grid\GridView; //Para el grid avanzado
use kartik\dynagrid\DynaGridStore; //Para el grid avanzado
use yii\helpers\Url;


use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CargadorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//Configuramos el titulo
$this->title = 'Buscador';
//Configuramos el subtitulo
$this->params['title']['small'] = 'Información y ayuda';
//Configuramos el breadcrumb
//$this->params['breadcrumbs'][] = ['label' => 'label', 'url' => ['view', 'id'=>'1']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="cargador-index">
    <div class="box box-primary">
        <p><strong>Cosas que puedo buscar en este buscador del menú lateral.</strong></p>
        <br>
        <p>
            Hay que tener en cuenta que el menú superior sólo es un menú para buscar de forma rápida y en ningún caso sustituye a la sección de usuarios donde hay búsquedas por muchos más campos.
            <br><br>
            Aquí en la búsqueda rápida podemos buscar por:
            <br>
            <ul>
                <li>Nombre y apellidos</li>
                <li>E-Mail</li>
            </ul>

        </p>


    </div>
</div>
