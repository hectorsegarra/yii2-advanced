<?php

/** @var $exception object */
/** @var $name string */

use yii\helpers\Html;

$this->title = $name;
$homeUrl = is_string(Yii::$app->homeUrl) ? Yii::$app->homeUrl : '/';
?>

<div class="site-error">
    <div class="error-page">
        <h2 class="headline text-yellow"> <?= nl2br(Html::encode($exception->statusCode)) ?></h2>

        <div class="error-content">
            <h3>
                <i class="fa fa-warning text-yellow"></i> <?= Yii::t('app', 'Oops!') ?>
                <?= nl2br(Html::encode($exception->getMessage())) ?>
            </h3>

            <p>
                <?= Yii::t('app', 'Meanwhile, you may {:Link} or try using the search form.', [
                    ':Link' => Html::a(Yii::t('app', 'return to dashboard'), $homeUrl)
                ]) ?>
            </p>

        </div>
    </div>

</div>
