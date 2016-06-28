<?php
/**
 * @Final File
 */
use yii\bootstrap\ActiveForm;
use app\components\Formmod;

if ($success) {
echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' . $success . ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
}

if ($error) {
echo '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> ' . $error . ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
}

Formmod::begin(['t_title' => $t_title, 'l_cancel' => $l_cancel]);

$form = ActiveForm::begin([
    'id'      => 'form-data',
    'layout'  => 'horizontal',
    'options' => [
        'enctype' => 'multipart/form-data'
    ],
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label'   => 'col-sm-2',
            'wrapper' => 'col-sm-10',
        ],
    ],
]);

Formmod::formBegin();
echo $form->field($model, 'imageFile')->fileInput();
Formmod::formEnd();

ActiveForm::end();
Formmod::end();