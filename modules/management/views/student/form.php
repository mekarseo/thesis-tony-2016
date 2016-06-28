<?php 
/**
 * @Final File
 */
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;
use app\components\Formmod;
use yii\helpers\ArrayHelper;

if ($error) :
echo '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> ' . $error . ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
endif;

echo '<div class="panel panel-info"><div class="panel-heading"><h3 class="panel-title"><i class="fa fa-user-secret"></i> ' . Yii::t('app', 'Personal Info') . '</h3></div><div class="panel-body">';
echo DetailView::widget([
    'model' => $model->getPersonal(),
    'attributes' => [
    	'std_id',
        [
            'attribute' => 'personal_id',
            'value' => substr($model->personal->personal_id, 0, 1) . '-' . substr($model->personal->personal_id, 1, 4) . '-' . substr($model->personal->personal_id, 5, 5) . '-' . substr($model->personal->personal_id, 10, 2) . '-' . substr($model->personal->personal_id, 12, 1),
        ],
        'first_name',
        'last_name',
        'birth_date:date',
        [
        	'attribute' => 'mobile',
        	'value' => substr($model->personal->mobile, 0, 2) . '-' . substr($model->personal->mobile, 2, 4) . '-' . substr($model->personal->mobile, 6, 4),
        ],
        'email:email',
        'father',
        [
        	'attribute' => 'father_mobile',
        	'value' => substr($model->personal->father_mobile, 0, 2) . '-' . substr($model->personal->father_mobile, 2, 4) . '-' . substr($model->personal->father_mobile, 6, 4),
        ],
        'mother',
        [
        	'attribute' => 'mother_mobile',
        	'value' => substr($model->personal->mother_mobile, 0, 2) . '-' . substr($model->personal->mother_mobile, 2, 4) . '-' . substr($model->personal->mother_mobile, 6, 4),
        ],
        'parent',
        [
        	'attribute' => 'parent_mobile',
        	'value' => substr($model->personal->parent_mobile, 0, 2) . '-' . substr($model->personal->parent_mobile, 2, 4) . '-' . substr($model->personal->parent_mobile, 6, 4),
        ],
        'parent_address',
        'term',
    ],
]);
echo '</div></div>';

echo '<div class="panel panel-info"><div class="panel-heading"><h3 class="panel-title"><i class="fa fa-university"></i> ' . Yii::t('app', 'Education Info') . '</h3></div><div class="panel-body">';
echo DetailView::widget([
    'model' => $model->getEducation(),
    'attributes' => [
        'old_school',
        'school_provice',
        'branch',
        'graduate',
        'gpa_graduation',
        [
            'attribute' => 'level',
            'value'     => $model->education->levelName->name,
        ],
        'faculty',
        'major',
    ],
]);
echo '</div></div>';

echo '<div class="panel panel-info"><div class="panel-heading"><h3 class="panel-title"><i class="fa fa-star"></i> ' . Yii::t('app', 'Talent Info') . '</h3></div><div class="panel-body">';
echo DetailView::widget([
    'model' => $model->getTalent(),
    'attributes' => [
        'talent_type',               
        'talent_sub',
        [
            'attribute' => 'talent_sub1',
            'value'     => ArrayHelper::getValue(json_decode($model->talent->talent_detail, true), 'sub1'),
        ],
        [
            'attribute' => 'talent_sub2',
            'value'     => ArrayHelper::getValue(json_decode($model->talent->talent_detail, true), 'sub2'),
        ],
        [
            'attribute' => 'talent_honor',
            'value'     => ArrayHelper::getValue(json_decode($model->talent->talent_detail, true), 'honor'),
        ],
    ],
]);
echo '</div></div>';

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
echo $form->field($model, 'std_id')->input('text', [
    'onkeyup' => "$(function () {
    $(\"input[name*='username']\").val($(\"input[name*='std_id']\").val());
    $(\"input[name*='password']\").val('p'+$(\"input[name*='std_id']\").val());
});"]);
echo $form->field($model, 'firstname')->input('text', ['readonly'=>'readonly']);
echo $form->field($model, 'lastname')->input('text', ['readonly'=>'readonly']);
echo $form->field($model, 'email')->input('email', ['readonly'=>'readonly']);
echo $form->field($model, 'username')->input('text', ['readonly'=>'readonly']);
echo $form->field($model, 'password')->input('text', ['readonly'=>'readonly']);
Formmod::formEnd();

ActiveForm::end();
Formmod::end();