<?php
/**
 * @Final File
 */
use yii\bootstrap\ActiveForm;
use app\components\Formmod;

if ($success) :
echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' . $success . ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
endif;

if ($error) :
echo '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> ' . $error . ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
endif;

Formmod::begin(['t_title' => $t_title, 'l_cancel' => $l_cancel]);

$form = ActiveForm::begin([
    'layout' => 'horizontal',
    'id' => 'form-data',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-3',
            'wrapper' => 'col-sm-9',
        ],
    ],
]);

echo $form->errorSummary($model);

Formmod::formBegin();
echo $form->field($model, 'email')->input('email');
echo $form->field($model, 'new_password1')->input('password');
echo $form->field($model, 'new_password2')->input('password');
echo "<hr />";
echo $form->field($model, 'old_password')->input('password');
Formmod::formEnd();

ActiveForm::end();
Formmod::end();