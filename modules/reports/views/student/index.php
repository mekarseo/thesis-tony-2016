<?php
/**
 * @Final File
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;

$form = ActiveForm::begin([
    'method' => 'get',
    'layout' => 'horizontal',
    'id' => 'form-data',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-5',
            'wrapper' => 'col-sm-3',
        ],
    ],
]);

echo $form->field($model, 'term')->dropDownList($model->getTerms(), [
        'onchange' => "submit()",
    ]);

ActiveForm::end();

Pjax::begin();
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout'       => $dataLayout,
    'filterModel'  => $model,
    'options'      => ['class' => 'grid-view panel-crud'],
    'pager'        => ['options' => ['class' => 'pagination pagination-sm']],
    'columns'      => [
        [
            'class' => 'yii\grid\SerialColumn',
        ],
        
        'std_id',
        'first_name',
        'last_name',
        'faculty',
        'major',
        'level',

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
        ],
    ],
]);

Modal::begin([
    'id' => 'modal-search',
    'header' => '<h4>' . Yii::t('app', 'Search Panel') . '</h4>',
]);

$form = ActiveForm::begin([
    'method' => 'get',
    'id' => 'form-search',
]);
echo $form->field($model, 'talent')->dropDownList($model->getTalentSubs(),['prompt' => Yii::t('app', 'Select')]);
echo $form->field($model, 'mobile')->input('text');
echo $form->field($model, 'email')->input('text');

echo '<div class="form-group">';
echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']).' ';
echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']);
echo '</div>';

ActiveForm::end();

Modal::end();

Pjax::end();