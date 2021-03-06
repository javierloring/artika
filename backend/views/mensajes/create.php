<?php

/* @var $this yii\web\View */
/* @var $model common\models\Mensajes */

$this->title = 'Crear Mensajes';
$this->params['breadcrumbs'][] = ['label' => 'Mensajes', 'url' => ['recibidos']];
$this->params['breadcrumbs'][] = $this->title;
$js = <<<JS
    $('#menu-principal-user').children('li.mensajes-dropdown').addClass('active');
JS;

$this->registerJs($js);
?>
<?= $this->render('_menu') ?>
<div class="mensajes-create">
    <div class="panel panel-default borde-redondo">
        <div class="panel-body panel-body-gris borde-redondo">
            <div class="text-center">
                <h3 class="post"><span class="label label-success">Nuevo Mensaje</span></h3>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
