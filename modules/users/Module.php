<?php

namespace modules\users;

use Yii;
use yii\console\Application as ConsoleApplication;

/**
 * Class Module
 * @package modules\users
 */
class Module extends \yii\base\Module
{
     /**
     * Tiempo en segundos en que se pueden eliminar usuarios con estado "Pendiente"
     * Principalmente para tareas de Cron.
     * ```
     * php yii users/cron/remove-overdue
     * ```
     * @var int
     */
    public $emailConfirmTokenExpire = 259200; // 3 days

    /**
     * @var int
     */
    public static $passwordResetTokenExpire = 3600;

    /**
     * @var string
     */
    public $controllerNamespace = 'modules\users\controllers\frontend';

    /**
     * @var bool Si el módulo se utiliza para el panel de administración.

     */
    public $isBackend;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Está aquí para cambiar entre frontend y backend
        if ($this->isBackend === true) {
            $this->controllerNamespace = 'modules\users\controllers\backend';
            $this->setViewPath('@modules/users/views/backend');
        } else {
            $this->setViewPath('@modules/users/views/frontend');
        }
        if (Yii::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'modules\users\commands';
        }
    }

    /**
     * @param string $category
     * @param string $message
     * @param array $params
     * @param null|string $language
     * @return string
     */
    public static function translate($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/users/' . $category, $message, $params, $language);
    }
}
