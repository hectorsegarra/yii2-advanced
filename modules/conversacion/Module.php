<?php
namespace modules\conversacion;

use Yii;
use yii\console\Application as ConsoleApplication;

/**
 * Class Module 
 * @package modules\conversacion
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'modules\conversacion\controllers\backend';

    /**
     * @var bool If the module is used for the admin panel.
     */
    public $isBackend;
 
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->isBackend === true) {
            $this->controllerNamespace = 'modules\conversacion\controllers\backend';
            $this->setViewPath('@modules/conversacion/views/backend');
        } else {
            $this->setViewPath('@modules/conversacion/views/frontend');
        }
        if (Yii::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'modules\conversacion\commands';
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
        return Yii::t('modules/conversacion/' . $category, $message, $params, $language);
    }
}
