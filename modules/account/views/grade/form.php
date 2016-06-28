<?php
/**
 * @Final File
 */
use yii\bootstrap\ActiveForm;
use app\components\Formmod;
use yii\helpers\Url;

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
echo $form->field($model, 'term')->dropDownList($model->getTerm(), ['prompt' => 'Select']);
echo $form->field($model, 'grade')->input('text');
Formmod::formEnd();

ActiveForm::end();
Formmod::end();