<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

 
/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm; 
//use kartik\datecontrol\DateControl; //Descomentar esto si hay fechas 

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">
    <?= "<?php " ?>$form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="row">
                <?php
                    $i = 0;
                    $vez=1;
                    foreach ($generator->getColumnNames() as $attribute) {
                        if (in_array($attribute, $safeAttributes)) {
                            if($vez==1){
                                $vez=2;
                                echo "<div class='col-lg-6'>
                    <?=" . $generator->generateActiveField($attribute) . "?>
                </div>\n";
                            }else{
                                echo "                <div class='col-lg-6'>
                    <?=" . $generator->generateActiveField($attribute) . "?>
                </div>\n";
                            }
                            if (++$i % 2 == 0) {
                                echo "            </div>\n            <div class='row'>\n";
                            }
                        }
                    }
                    if ($i % 2 != 0) {
                        echo "                <div class='col-lg-6'>
                        </div>\n";
                    }
                ?>
            </div>
        </div>
        <div class="box-footer">
            <div class="form-group">
                <?= "<?= " ?>Html::submitButton('<span class="fas fa-save"></span> '.<?= $generator->generateString('Save') ?>, ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?= "<?php " ?>ActiveForm::end(); ?>
</div>
