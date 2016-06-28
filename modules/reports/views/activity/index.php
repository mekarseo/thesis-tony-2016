<?php
/**
 * @Final File
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

Pjax::begin();
echo GridView::widget([
    'dataProvider'  => $dataProvider,
    'layout'        => $dataLayout,
    'filterModel'   => $model,
    'options'       => ['class' => 'grid-view panel-crud'],
    'pager'         => ['options' => ['class' => 'pagination pagination-sm']],
    'columns'       => [
        ['class' => 'yii\grid\SerialColumn'],
            
        'section',
        'operator',
        'talent',
        'activity',
        'status',
        //'create_at:datetime',

        [
            'class'     => 'yii\grid\ActionColumn',
            'template'  => '{view}',
        ],
    ],
]);
Pjax::end();