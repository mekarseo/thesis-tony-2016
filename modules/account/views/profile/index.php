<?php 
/**
 * @Final File
 */
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;
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
echo $form->field($model->personal, 'std_id')->input('text', ['readonly' => 'readonly']);
echo $form->field($model->personal, 'personal_id')->input('text', ['readonly' => 'readonly']);
echo $form->field($model->personal, 'first_name')->input('text', ['readonly' => 'readonly']);
echo $form->field($model->personal, 'last_name')->input('text', ['readonly' => 'readonly']);
echo $form->field($model->personal, 'birth_date')->input('text', ['readonly' => 'readonly']);
echo $form->field($model, 'mobile')->input('text');
echo $form->field($model, 'address', ['inputOptions' => ['placeholder' => Yii::t('app', 'Present Address')]])->textarea();
echo $form->field($model->personal, 'father')->input('text', ['readonly' => 'readonly']);
echo $form->field($model->personal, 'father_mobile')->input('text', ['readonly' => 'readonly']);
echo $form->field($model->personal, 'mother')->input('text', ['readonly' => 'readonly']);
echo $form->field($model->personal, 'mother_mobile')->input('text', ['readonly' => 'readonly']);
echo $form->field($model->personal, 'parent')->input('text', ['readonly' => 'readonly']);
echo $form->field($model->personal, 'parent_mobile')->input('text', ['readonly' => 'readonly']);
echo $form->field($model->personal, 'parent_address')->input('text', ['readonly' => 'readonly']);
echo $form->field($model->personal, 'term')->input('text', ['readonly' => 'readonly']);
echo "<hr />";
echo $form->field($model, 'password', ['inputOptions' => ['placeholder' => Yii::t('app', 'Current Password')]])->input('password');
Formmod::formEnd();

ActiveForm::end();
Formmod::end();