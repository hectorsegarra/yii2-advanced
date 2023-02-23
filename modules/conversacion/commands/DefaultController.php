<?php

namespace modules\conversacion\commands;

use yii\console\Controller;

/**
 * Class DefaultController
 * @package modules\conversacion\commands
 */
class DefaultController extends Controller
{
    /**
     * Console default actions
     * @inheritdoc
     */
    public function actionIndex()
    {
        echo 'php yii conversacion/default' . PHP_EOL;
    }
}
