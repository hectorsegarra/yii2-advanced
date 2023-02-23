<?php

namespace modules\search;

use Yii;
use yii\i18n\PhpMessageSource;
use yii\web\GroupUrlRule;

/**
 * Class Bootstrap
 * @package modules\search
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
        $i18n->translations['modules/search/*'] = [
            'class' => PhpMessageSource::class,
            'basePath' => '@modules/search/messages',
            'fileMap' => [
                'modules/search/module' => 'module.php'
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
