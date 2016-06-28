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
echo $form->field($model, 'firstname')->input('text');
echo $form->field($model, 'lastname')->input('text');
echo $form->field($model, 'position')->input('text');
echo $form->field($model, 'talent_type')->dropDownList($model->getTalentType(), [
        'prompt' => Yii::t('app', 'Select'),
        'onchange' => "$.ajax({
            dataType: 'html',
            method: 'POST',
            url: '" . Url::to(['/management/teacher/ajax']) . "',
            data: { type: this.value }
        }).done(function(data) {
            $(\"select[name*='talent_sub']\").html(data);
        });",
    ]);
echo $form->field($model, 'talent_sub')->dropDownList(Yii::$app->request->get('id') ? $model->getTalentSub($model->talent_type) : [], ['prompt' => Yii::t('app', 'Select')]);
echo $form->field($model, 'email')->input('email');
echo $form->field($model, 'username')->input('text');
echo $form->field($model, 'password')->input('text');
Formmod::formEnd();

ActiveForm::end();
Formmod::end();