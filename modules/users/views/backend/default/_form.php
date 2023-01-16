<?php
use common\models\PrincipalObjetivo;
use common\models\NivelSmartworkout;
?>

<div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title texto-azul">Datos básicos</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($profile, 'first_name')->textInput([
                        'maxlength' => true,
                        'placeholder' => true,
                    ]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($profile, 'last_name')->textInput([
                        'maxlength' => true,
                        'placeholder' => true,
                    ]) ?>
                </div>
            </div>
        </div>    
    </div>