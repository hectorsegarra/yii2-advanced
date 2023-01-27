<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */

use yii\helpers\Html;

$script = "
$('#generator-indexwidgettype').on('change', function(){
    if($(this).val() === 'list') {
        $('#pageSizeCheckbox').hide();
    } else {
        $('#pageSizeCheckbox').show();
    }
});
";
$this->registerJs($script);
?>

<strong>Model</strong> Aqui puedes escribir el nombre del modelo, al presionar el boton "escribir" se rellenarán todos los campos<strong>:</strong>
<div class="row">
    <div class="col-sm-9 d-inline-block">
        <input type="text" class="form-control" id="model" name="model">
    </div>
    <div class="col-sm-3 d-inline-block">
        <button class="btn btn-primary form-control" onclick="escribir();">Escribir</button>
    </div>
</div>
    

<hr>

<?php
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'searchModelClass');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'viewPath');
echo $form->field($generator, 'baseControllerClass');
echo $form->field($generator, 'indexWidgetType')->dropDownList([
    'grid' => 'GridView',
    'list' => 'ListView',
]);
echo Html::beginTag('div', ['id' => 'pageSizeCheckbox']);
echo $form->field($generator, 'enablePageSize')->checkbox();
echo Html::endTag('div');
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'enablePjax')->checkbox();
echo $form->field($generator, 'messageCategory');
?>

<script>

    function escribir(){
        $("#generator-modelclass").val("common\\models\\" + $("#model").val());
        $("#generator-searchmodelclass").val("common\\models\\search\\" + $("#model").val() + "Search");
        $("#generator-controllerclass").val("backend\\controllers\\" + $("#model").val() + "Controller");
        
        var model= $("#model").val();
        
        var modelo_minusculas = model.charAt(0);

        for(i=1; i<model.length; i++){
            if(model.charAt(i) === model.charAt(i).toUpperCase()){
                modelo_minusculas = modelo_minusculas + "-" + model.charAt(i)
            }else{
                modelo_minusculas = modelo_minusculas + model.charAt(i);
            }
        }
        modelo_minusculas = modelo_minusculas.toLowerCase();

        $("#generator-viewpath").val("@app/views/" + modelo_minusculas);
       
    }

</script>