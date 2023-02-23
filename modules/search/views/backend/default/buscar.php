<style>
    .table-hover tbody tr:hover {
        background-color: #8abddb;
    }
</style>

<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use kartik\dynagrid\DynaGrid; //Para el grid avanzado
use kartik\grid\GridView; //Para el grid avanzado
use kartik\dynagrid\DynaGridStore; //Para el grid avanzado
use yii\helpers\Url;


use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel common\models\search\Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

//Configuramos el titulo
$this->title = 'Buscador';
//Configuramos el subtitulo
$this->params['title']['small'] = 'Buscar';
//Configuramos el breadcrumb
//$this->params['breadcrumbs'][] = ['label' => 'label', 'url' => ['view', 'id'=>'1']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="search-index">
    <div class="box box-primary">
        <p><strong>Resultados de la búsqueda rápida.</strong></p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'columns' => [
                [
                    'attribute' => 'userFullName',
                    'label' => 'Nombre de usuario',
                ],
                'email',
            ],
            'rowOptions' => function ($model, $key, $index, $grid) {
                return [
                    'data-url' => Url::to(['/user/'.$model->id.'/update' ]),
                    'onclick' => 'window.location = $(this).data("url");',
                    'style' => 'cursor: pointer;',
                ];
            },
        ]); ?>



        

    </div>
</div>
