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
	'options' => [
		'enctype' => 'multipart/form-data'
	],
    'layout' => 'horizontal',
    'id' => 'form-data',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-4',
            'wrapper' => 'col-sm-8',
        ],
    ],
]);

Formmod::formBegin();
echo $form->field($model, 'term', ['inputOptions' => ['placeholder' => Yii::t('app', 'Example: '.date('Y/1'))]])->input('text');
echo $form->field($model, 'uploadFile')->fileInput();
Formmod::formEnd();

ActiveForm::end();
Formmod::end();

if ($duplicate) {
echo "<pre>";
    $number = 1;
    foreach($duplicate as $id => $text) {
        $pid = substr($id, 0, 1) . '-' . substr($id, 1, 4) . '-' . substr($id, 5, 5) . '-' . substr($id, 10, 2) . '-' . substr($id, 12, 1);
        echo '<font color="red">'.$number . '. ' . Yii::t('app', 'Pesonal ID: ') . $pid . ' has '. $text .'</font><br />';
        $number++;
    }
echo "</pre>";
}