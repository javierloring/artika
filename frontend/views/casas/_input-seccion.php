<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$accion = Yii::$app->controller->action->id;
$esMod = $accion === 'modificar-seccion';

$urlCrearSeccion = Url::to(['casas/crear-seccion-ajax']);
$urlModificarSeccionAjax = Url::to(['casas/modificar-seccion-ajax']);
$urlSecciones = Url::to(['casas/crear-seccion']);

$js = <<<EOL
var max = $('#secciones-nombre').attr('maxlength');
$('#secciones-nombre').after($('<span id="quedan" class="label"></span>'));
mostrarNumero();
$('#secciones-nombre').on('input', function() {
    mostrarNumero();
});

function mostrarNumero() {
    var lon = max - $('#secciones-nombre').val().length;
    var numero = $('#quedan');
    numero.text(lon);
    if (lon > 16) {
        numero.removeClass('label-success');
        numero.addClass('label-danger');
    } else if (lon <=5) {
        numero.removeClass('label-success');
        numero.addClass('label-warning');
    } else {
        numero.removeClass('label-danger');
        numero.removeClass('label-warning');
        numero.addClass('label-success');
    }
}

function volverCrearSeccion() {
    $.ajax({
        url: '$urlSecciones',
        type: 'POST',
        data: {},
        success: function (data) {
            $('#casa-usuario').html(data);
        }
    });
}

$('#cancelar-button').on('click', function () {
    volverCrearSeccion();
});
EOL;

if ($esMod) {
    $js .= <<<EOL
    $('#seccion-form').on('beforeSubmit', function () {
        $.ajax({
            url: '$urlModificarSeccionAjax' + '?id=$model->id',
            type: 'POST',
            data: {
                'Secciones[nombre]': $('#seccion-form').yiiActiveForm('find', 'secciones-nombre').value
            },
            success: function (data) {
                $('#menu-casa-usuario').html(data);
                volverCrearSeccion();
            }
        });
        return false;
    });
EOL;
} else {
    $js .= <<<EOL
    $('#seccion-form').on('beforeSubmit', function () {
        $.ajax({
            url: '$urlCrearSeccion',
            type: 'POST',
            data: {
                'Secciones[nombre]': $('#seccion-form').yiiActiveForm('find', 'secciones-nombre').value
            },
            success: function (data) {
                $('#menu-casa-usuario').html(data);
            }
        });
        return false;
    });
EOL;
}
$this->registerJs($js);

?>
<div class="row">
    <div class="col-md-3 text-center">
        <img src="/imagenes/seccion.png" alt="">
    </div>
    <div class="col-md-9">
        <?php if ($esMod): ?>
        <h4><span class="label label-info">
            Sección: <?= Html::encode($model->nombre) ?>
        </span></h4>
        <?php endif ?>
        <?php $form = ActiveForm::begin([
            'id' => 'seccion-form',
        ]);
        ?>
        <?= $form->field($model, 'nombre', [
            'enableAjaxValidation' => true,
            'validateOnChange' => false,
            'validateOnBlur' => false,
        ])
        ->textInput([
            'maxlength' => 20,
            'style'=>'width: 35%; display: inline; margin-right: 10px;',
        ])
        ->label('Nombre de la sección', [
            'style' => 'display: block',
            ]) ?>
        <div class="form-group">
            <?= Html::submitButton($esMod ? 'Modificar' : 'Añadir', [
                'class' => 'btn btn-success',
                'id' => 'guardar-button'
                ]) ?>
            <?php if ($esMod): ?>
                <?= Html::button('Cancelar', [
                    'class' => 'btn btn-danger',
                    'id' => 'cancelar-button',
                ]) ?>
            <?php endif ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>