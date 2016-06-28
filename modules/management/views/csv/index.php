<?php
/**
 * @Final File
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

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
        
        [
            'headerOptions' => ['style' => 'width:10%'],
            'attribute' => 'term',
            'filter'    => $model->getTerms(),
        ],
        [
            'attribute' => 'personal_id',
            'value' => function ($model) {
                $pid = substr($model->personal_id, 0, 1) . '-' . substr($model->personal_id, 1, 4) . '-' . substr($model->personal_id, 5, 5) . '-' . substr($model->personal_id, 10, 2) . '-' . substr($model->personal_id, 12, 1);
                return $pid;
            },
        ],
        'first_name',
        'last_name',
        'faculty',
        'major',
        'level',

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}',
            'buttons' => [
                'update' => function ($url, $model, $key) {
                    return Html::a('<i class="fa fa-plus-square"></i>', ['/management/student/create', 'id' => $model->id], ['title' => Yii::t('app', 'create')]);
                },
            ]
        ],
    ],
]);
Pjax::end();