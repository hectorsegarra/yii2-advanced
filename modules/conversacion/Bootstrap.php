<?php

namespace modules\conversacion;

use Yii;
use yii\i18n\PhpMessageSource;
use yii\web\GroupUrlRule;

/**
 * Class Bootstrap
 * @package modules\conversacion
 */
class Bootstrap
{
    /**
     * Bootstrap constructor.
     */
    public function __construct()
    {
        $this->registerTranslations();
        $urlManager = Yii::$app->urlManager;
        $urlManager->addRules($this->getRules());
    }

    /**
     * Register Translations
     */
    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['modules/conversacion/*'] = [
            'class' => PhpMessageSource::class,
            'basePath' => '@modules/conversacion/messages',
            'fileMap' => [
                'modules/conversacion/module' => 'module.php'
            ]
        ];
    }

    /**
     * @return GroupUrlRule[]
     */
    public function getRules()
    {
        return [
            new GroupUrlRule([
                'prefix' => 'conversacion',
                'rules' => [
                    '' => 'default/index',
                    '<id:\d+>/<_a:[\w\-]+>' => 'default/<_a>',
                    '<_a:[\w\-]+>' => 'default/<_a>'
                ]
            ])
        ];
    }
}
