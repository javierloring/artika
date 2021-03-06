<?php

use yii\web\View;
use yii\web\JsExpression;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use common\helpers\UtilHelper;

use kartik\select2\Select2;
use yii2mod\markdown\MarkdownEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Mensajes */
/* @var $form yii\widgets\ActiveForm */
$url = Url::to(['usuarios/lista-usuarios']);
$directo = $model->destinatario_id !== null;
$js = <<<JS
    var formatUsuario = function (usuario) {
    if (usuario.loading) {
        return usuario.text;
    }
    var markup =
        '<div class="row">' +
            '<div class="col-sm-12">' +
            '<img src="' + usuario.url + '" class="img-sm img-circle"  />' +
        '<b style="margin-left:5px">' + usuario.text + '</b>' +
            '</div>' +
        '</div>';
    return markup;
    };
JS;
$this->registerJs($js, View::POS_HEAD);

?>

<div class="mensajes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if ($directo): ?>
        <?php $data = [$model->destinatario_id => $model->destinatario->nombre] ?>
        <?= $form->field($model, 'destinatario_id', [
            'inputTemplate' => UtilHelper::inputGlyphicon('user'),
            ])->widget(Select2::classname(), [
            'data' => $data,
            'pluginOptions' => [
                'allowClear' => false,
                'disabled' => true,
            ],
        ]); ?>
        <?= $form->field($model, 'destinatario_id')->hiddenInput() ?>
    <?php else: ?>
        <?= $form->field($model, 'destinatario_id', [
            'inputTemplate' => UtilHelper::inputGlyphicon('user'),
            ])->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Buscar un usuario ...', 'multiple' => true],
            'maintainOrder' => true,
            'showToggleAll' => false,
            'pluginOptions' => [
                'language' => 'es',
                'allowClear' => false,
                'minimumInputLength' => 2,
                'ajax' => [
                    'url' => $url,
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('formatUsuario'),
                'templateSelection' => new JsExpression('function (usuario) { return usuario.text; }'),
            ],
        ]); ?>
    <?php endif ?>


    <?= $form->field($model, 'asunto', [
        'inputTemplate' => UtilHelper::inputGlyphicon('tag'),
        ])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contenido')->widget(MarkdownEditor::class, [
        'editorOptions' => [
            'showIcons' => ["code", "table"],
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Enviar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
