<?php
/**
 * @Final File
 */
use yii\grid\GridView;

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
        //'process_at:datetime',
    ],
]);