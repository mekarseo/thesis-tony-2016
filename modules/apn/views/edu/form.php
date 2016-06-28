<?php 
/**
 * @Final File
 */
use yii\bootstrap\ActiveForm;
use app\components\Formmod;
use yii\helpers\Url;

if ($error) {
echo '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> ' . $error . ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
}

Formmod::begin(['t_title' => $t_title, 'l_cancel' => $l_cancel]);

$form = ActiveForm::begin([
    'layout' => 'horizontal',
    'id' => 'form-data',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-10',
        ],
    ],
]);

echo $form->errorSummary($model);

Formmod::formBegin();
echo $form->field($model, 'type')->input('text', ['readonly' => 'readonly']);

if (Yii::$app->request->get('code') == 'Faculty') {
	echo $form->field($model, 'campus')->dropDownList($model->campusList(), ['prompt'=> Yii::t('app', 'Select')]);
}

if (Yii::$app->request->get('code') == 'Division') {
	echo $form->field($model, 'campus')->dropDownList($model->campusList(), [
        'prompt' => Yii::t('app', 'Select'),
        'onchange' => "$.ajax({
            dataType: 'html',
            method: 'POST',
            url: '" . Url::to(['/apn/edu/ajax', 'code' => Yii::$app->request->get('code')]) . "',
            data: { type: 'faculty', parent: this.value }
        }).done(function(data) {
            $(\"select[name*='faculty']\").html(data);
        });",
    ]);
	echo $form->field($model, 'faculty')->dropDownList(Yii::$app->request->get('id') ? $model->facultyList($model->campus) : [], ['prompt'=> Yii::t('app', 'Select')]);
}

if (Yii::$app->request->get('code') == 'Major') {
	echo $form->field($model, 'campus')->dropDownList($model->campusList(), [
        'prompt' => Yii::t('app', 'Select'),
        'onchange' => "$.ajax({
            dataType: 'html',
            method: 'POST',
            url: '" . Url::to(['/apn/edu/ajax', 'code' => Yii::$app->request->get('code')]) . "',
            data: { type: 'faculty', parent: this.value }
        }).done(function(data) {
            $(\"select[name*='faculty']\").html(data);
        });",
    ]);
	echo $form->field($model, 'faculty')->dropDownList(Yii::$app->request->get('id') ? $model->facultyList($model->campus) : [], [
        'prompt' => Yii::t('app', 'Select'),
        'onchange' => "$.ajax({
            dataType: 'html',
            method: 'POST',
            url: '" . Url::to(['/apn/edu/ajax', 'code' => Yii::$app->request->get('code')]) . "',
            data: { type: 'division', parent: this.value }
        }).done(function(data) {
            $(\"select[name*='division']\").html(data);
        });",
    ]);
	echo $form->field($model, 'division')->dropDownList(Yii::$app->request->get('id') ? $model->divisionList($model->faculty) : [], ['prompt'=> Yii::t('app', 'Select')]);
    echo $form->field($model, 'level')->checkboxList($model->levelList(), ['class' => 'checkbox-inline']);
}

echo $form->field($model, 'name')->input('text');

Formmod::formEnd();

ActiveForm::end();
Formmod::end();