<?php

namespace modules\conversacion\traits;

use Yii;
use modules\conversacion\Conversacion;

/**
 * Trait ConversacionTrait
 *
 * @property-read Conversacion $module
 * @package modules\conversacion\traits
 */
trait ConversacionTrait
{
    /**
     * @return null|\yii\base\Module
     */
    public function getModule()
    {
        return Yii::$app->getModule('conversacion');
    }
}
