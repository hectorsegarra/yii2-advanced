<?php

namespace modules\users\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use modules\users\models\UploadForm;
use modules\users\Module;



/**
 * Class AvatarFormWidget
 * @package modules\users\widgets
 */
class UploadAvatarForm extends Widget
{
    /**
     * On/Off widget
     * @var bool
     */
    public $status = true;
    /**
     * @var UploadForm
     */
    public $model;

    public $user_id;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->model = $this->model ?: new UploadForm();
    }

    /**
     * @return string|void
     */
    public function run()
    {
        if ($this->status === true) {
            $this->renderForm();
        }
    }

    /**
     * Render upload form
     */
    public function renderForm()
    {
       
        if(Yii::$app->controller->id=='profile'){
            $accionDestino='profile';
        }else{
            $accionDestino='user';
        }

        $form = ActiveForm::begin([
            'action' => Url::to(['/'.$accionDestino.'/upload-image','user_id'=>$this->user_id]),
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]);
        echo $form->field($this->model, 'imageFile')->fileInput();

        echo Html::submitButton('<span class="fa fa-upload"></span> ' . Module::translate('module', 'Submit'), [
            'class' => 'btn btn-primary',
            'name' => 'submit-button',
        ]);
        ActiveForm::end();
    }
}
