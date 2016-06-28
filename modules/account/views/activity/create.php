<?php 
/**
 * @Final File
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use app\components\Formmod;

if ($error) :
echo '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> ' . $error . ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
endif;

Formmod::begin(['t_title' => $t_title, 'l_cancel' => $l_cancel]);

$form = ActiveForm::begin([
    'id'     => 'form-data',
    'layout' => 'horizontal',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label'     => 'col-sm-3',
            'wrapper'   => 'col-sm-9',
        ],
    ],
]);

echo $form->errorSummary($model);

Formmod::formBegin();
echo $form->field($model, 'type')->dropDownList($model->getTalentType(), [
        'prompt'    => Yii::t('app', 'Select'),
        'onchange'  => "$.ajax({
            dataType: 'html',
            method: 'POST',
            url: '" . Url::to(['/account/activity/ajax']) . "',
            data: { type: this.value }
        }).done(function(data) {
            $(\"select[name*='talent']\").html(data);
        });",
    ]);
echo $form->field($model, 'talent')->dropDownList(Yii::$app->request->get('id') ? $model->getTalentSub($model->type) : [], ['prompt' => Yii::t('app', 'Select')]);
echo $form->field($model, 'activity')->textarea();
echo $form->field($model, 'comment', ['inputOptions' => ['placeholder' => Yii::t('app', 'Other Comment')]])->textarea();
echo $form->field($model, 'section', ['inputOptions' => ['placeholder' => Yii::t('app', 'Example: ' . date('1/Y'))]])->input('text');
Formmod::formEnd();

ActiveForm::end();
Formmod::end();