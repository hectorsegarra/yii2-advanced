<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use modules\users\models\LoginForm;
use modules\users\Module;

/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model LoginForm
 */

$this->title = Module::translate('module', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>

<p class="login-box-msg"><?= Module::translate('module', 'Login to the site to start the session') ?></p>

<?php $form = ActiveForm::begin([
    'id' => 'login-form'
]); ?>



<div class="form-group has-feedback">
    <?= $form->field($model, 'email')->textInput([
        'class' => 'form-control',
        'placeholder' => Module::translate('module', 'Email'),
        ['autofocus' => true],
    ])->label(false) ?>
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
</div>
<div class="form-group has-feedback">
    <?= $form->field($model, 'password')->passwordInput([
        'class' => 'form-control',
        'placeholder' => Module::translate('module', 'Password')
    ])->label(false) ?>
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="checkbox icheck">
            <?= $form->field($model, 'rememberMe')->checkbox(['class' => 'iCheck']) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <?= Html::submitButton(Module::translate('module', 'Sign In'), [
            'class' => 'btn btn-primary btn-block btn-flat',
            'name' => 'login-button'
        ]) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
