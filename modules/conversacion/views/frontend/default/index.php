<?php

use yii\helpers\Html;
use modules\conversacion\Conversacion;

$this->title = Conversacion::translate('module', 'Conversacion');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversacion-frontend-default-index">
    <h1><?= Html::decode($this->title) ?></h1>

    <p>
        This is the module conversacion frontend page.
        You may modify the following file to customize its content:
    </p>

    <code><?= __FILE__ ?></code>
</div>
