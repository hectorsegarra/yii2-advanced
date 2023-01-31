<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\LinkPager;
use kartik\dynagrid\DynaGrid; //Para el grid avanzado
use kartik\grid\GridView; //Para el grid avanzado
use kartik\dynagrid\DynaGridStore; //Para el grid avanzado
use yii\helpers\Url;


<?= $generator->enablePjax ? "use yii\widgets\Pjax;\r\n" : '' ?>


/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

//Configuramos el titulo
$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
//Configuramos el subtitulo
$this->params['title']['small'] = '';
//Configuramos el breadcrumb
//$this->params['breadcrumbs'][] = ['label' => 'label', 'url' => ['view', 'id'=>'1']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <div class="box">
<?= $generator->enablePjax ? "        <?php Pjax::begin(); ?>\n" : '' ?>
        <div class="box-body">
            <?php if (!empty($generator->searchModelClass)) : ?>
<?= "<?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
            <?php endif; ?>
            <?php if (($generator->indexWidgetType === 'grid') && $generator->enablePageSize) : ?>
                <div class="container-fluid">
                    <div class="row">        
                        <div class="pull-left">
                            <?= "<?= " ?>Html::a('<span class="fa fa-plus"></span> Crear', ['create'], [
                                    'class' => 'btn btn-block btn-success',
                                    'title' => 'Create',
                                    'data' => [
                                        'toggle' => 'tooltip',
                                        'placement' => 'right',
                                        'pjax' => 0
                                    ]
                                ]) ?>
                        </div>
                        <?php endif; ?><?= "\r" ?>
                        <div class="pull-right">
                            
                            <?= "<?= " ?>common\widgets\PageSize::widget([
                                'label' => '',
                                'defaultPageSize' => 25,
                                'sizes' => [10 => 10, 15 => 15, 20 => 20, 25 => 25, 50 => 50, 100 => 100, 200 => 200],
                                'options' => [
                                    'class' => 'form-control'
                                ]
                            ])<?= " ?>\r" ?>
                        </div>
                    </div>
                </div>
<?php if ($generator->indexWidgetType === 'grid') : ?>
    <?= "<?= " ?>DynaGrid::widget([
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
                    <?php
                    $count = 0;
                    if (($tableSchema = $generator->getTableSchema()) === false) {
                        foreach ($generator->getColumnNames() as $name) {
                            if ($name=='id') {
                                echo "                    //'" . $name . "',\n";
                                ++$count;
                            }
                            else if (++$count < 6) {
                                echo "                    '" . $name . "',\n";
                            } else {
                                echo "                    //'" . $name . "',\n";
                            }
                        }
                    } else {
                        foreach ($tableSchema->columns as $column) {
                            $format = $generator->generateColumnFormat($column);
                            if ($column->name=='id') {?>
[
                        'attribute' => '<?=$column->name?>',
                        'format'=>'<?=$format?>',
                        'visible' => false,
                    ],
<?php
                                ++$count;
                            }
                            else if (++$count < 6) {
?>
                    [
                        'attribute' => '<?=$column->name?>',
                        'format'=>'<?=$format?>',
                        'visible' => true,
                    ],
<?php
                            } else {
?>
                    [
                        'attribute' => '<?=$column->name?>',
                        'format'=>'<?=$format?>',
                        'visible' => false,
                    ],
<?php
                            }
                        }
                    }
                    ?>
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
        <?php endif; ?>
<?= $generator->enablePjax ? "<?php Pjax::end(); ?>\n" : "\r" ?>
    </div>
</div>
