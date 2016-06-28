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
        'options'       => ['class' => 'grid-view panel-crud'],
        'pager'         => ['options' => ['class' => 'pagination pagination-sm']],
        'columns'       => [
            ['class' => 'yii\grid\SerialColumn'],
            
            'section',
            'operator',
            'talent',
            'status',
            [
                'attribute' => 'activity',
                'value'     => function ($model) {
                    return nl2br($model->activity);
                },
            ],
            'create_at:datetime',

            [
                'class'     => 'yii\grid\ActionColumn',
                'template'  => '{view}',
            ],
        ],
    ]);
Pjax::end();