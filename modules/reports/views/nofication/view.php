<?php
/**
 * @Final File
 */
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use app\components\Formmod;

if ($success) {
echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' . $success . '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
}

if ($error) :
echo '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> ' . $error . ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
endif;

echo GridView::widget([
    'dataProvider'  => $dataProvider,
    'layout'        => $dataLayout,
    'options'       => ['class' => 'grid-view panel-crud'],
    'pager'         => ['options' => ['class' => 'pagination pagination-sm']],
    'columns'       => [
        ['class' => 'yii\grid\SerialColumn'],
            
        [
            'attribute' => 'approve_id',
            'value'     => function ($model) {
                return $model->operator;
            },
        ],
        [
            'attribute' => 'status',
            'value'     => function ($model) {
                return Yii::t('app', $model->status);
            },
        ],
        [
            'attribute' => 'comment',
            'value'     => function ($model) {
                return nl2br($model->comment);
            },
        ],
        'process_at:datetime',
    ],
]);

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
echo $form->field($model, 'status')->dropDownList($model->getStatus(), ['prompt' => Yii::t('app', 'Select')]);
echo $form->field($model, 'comment', ['inputOptions' => ['placeholder' => Yii::t('app', 'Other Comment')]])->textarea();
Formmod::formEnd();

ActiveForm::end();
Formmod::end();