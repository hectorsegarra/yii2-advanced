<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use kartik\dynagrid\DynaGrid; //Para el grid avanzado
use kartik\grid\GridView; //Para el grid avanzado
use kartik\dynagrid\DynaGridStore; //Para el grid avanzado
use yii\helpers\Url;


use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel modules\conversacion\models\search\ConversacionMensajeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//Configuramos el titulo
$this->title = 'Conversacion Mensajes';
//Configuramos el subtitulo
$this->params['title']['small'] = '';
//Configuramos el breadcrumb
//$this->params['breadcrumbs'][] = ['label' => 'label', 'url' => ['view', 'id'=>'1']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="conversacion-mensaje-index">
    <div class="box">
        <?php Pjax::begin(); ?>
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                                        <div class="container-fluid">
                    <div class="row">        
                        <div class="pull-left">
                            <?= Html::a('<span class="fa fa-plus"></span> Crear', ['create'], [
                                    'class' => 'btn btn-block btn-success',
                                    'title' => 'Create',
                                    'data' => [
                                        'toggle' => 'tooltip',
                                        'placement' => 'right',
                                        'pjax' => 0
                                    ]
                                ]) ?>
                        </div>
                                                <div class="pull-right">
                            
                            <?= common\widgets\PageSize::widget([
                                'label' => '',
                                'defaultPageSize' => 25,
                                'sizes' => [10 => 10, 15 => 15, 20 => 20, 25 => 25, 50 => 50, 100 => 100, 200 => 200],
                                'options' => [
                                    'class' => 'form-control'
                                ]
                            ]) ?>                        </div>
                    </div>
                </div>
    <?= DynaGrid::widget([
        'theme'=>'panel-primary',
        'showPersonalize'=>true,
        'storage'=>DynaGrid::TYPE_DB,
        'options'=>['id'=>'grid-inmueble-estadio'], // a unique identifier is important
        //'theme'=>'panel-danger',
        'gridOptions'=>[
            'dataProvider'=>$dataProvider,
            'filterModel'=>$searchModel,
            'panel'=>['heading'=>'<h3 class="panel-title">'.$this->title.'</h3>',],
            /*TOOLBAR configuración*/
            'toolbar' =>  [
                '{export}',
                '{toggleData}',
                '{dynagridFilter}',
                '{dynagridSort}',
                '{dynagrid}',
            ],
            /*------------------------*/
            'pjax' => true,
            'pjaxSettings' =>[
                'neverTimeout'=>true,
            ],
            //'bordered' => true,
            // 'striped' => false,
            // 'condensed' => false,
            'responsiveWrap'=>false,
            'hover' => true,
            'floatHeader' => false,
            'floatHeaderOptions' => ['scrollingTop' => '50'],
            'showPageSummary' => false,
            'pager' => [
                'firstPageLabel' => 'Inicio',
                'lastPageLabel' => 'Final',
                'maxButtonCount' => 10,
            ],
        ],
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'id',
                        'format'=>'text',
                        'visible' => false,
                    ],
                    [
                        'attribute' => 'conversacion_id',
                        'format'=>'text',
                        'visible' => true,
                    ],
                    [
                        'attribute' => 'sender_id',
                        'format'=>'text',
                        'visible' => true,
                    ],
                    [
                        'attribute' => 'mensaje',
                        'format'=>'ntext',
                        'visible' => true,
                    ],
                    [
                        'attribute' => 'created_at',
                        'format'=>'text',
                        'visible' => true,
                    ],
                    [
                        'attribute' => 'grupal',
                        'format'=>'text',
                        'visible' => false,
                    ],
                    [
                        'class'=>'kartik\grid\ActionColumn',
                        'dropdown'=>false,//true
                        //'dropdownOptions' => ['class' => 'pull-right'],
                        'order'=>DynaGrid::ORDER_FIX_RIGHT,
                        //'options' => ['style' => 'width:170px;'],
                        // 'deleteOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        //                     'label'=>'<i class="fa fa-trash-o" aria-hidden="true"></i> '.Yii::t('app', 'Delete')],
                        // 'viewOptions' => ['label'=> '<i class="fa fa-eye" aria-hidden="true"></i> '.Yii::t('app', 'View')],
                        // 'updateOptions' => ['label'=> '<i class="fa fa-pencil" aria-hidden="true"></i> '.Yii::t('app', 'Update')],


                        'template' => '{update}{borrar}',
                        'buttons' => [
                            'update' => function($url, $model) {
                                return Html::a(
                                        '<i class="fas fa-fw fa-pencil-alt"></i>',
                                        Url::to(['update', 'id' => $model->id]),
                                        [
                                            'title'=>\Yii::t('yii', 'Editar'),
                                            'data-pjax' => '0',
                                            'data-toggle' => 'tooltip',
                                            'class'=>'btn btn-default btn-xs',
                                        ]
                                    );
                            },
                            'borrar' => function($url, $model) {
                                return Html::a(
                                        '<i class="fas fa-fw fa-trash-alt"></i>',
                                        '#',
                                        [
                                            'title'=>\Yii::t('yii', 'Borrar'),
                                            'data-pjax' => '1',
                                            'onclick' =>'borrarConAjax('.$model->id.',"'.Url::to(['deleteajax', 'id' => $model->id]).'")',
                                            'data-toggle' => 'tooltip',
                                            'class'=>'btn btn-danger btn-xs',
                                        ]
                                    );
                            },
                        ]
                    ],
                ],
            ]) ?>
        </div>
        <?php Pjax::end(); ?>
    </div>
</div>
