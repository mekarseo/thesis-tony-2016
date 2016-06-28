<?php
/**
 * @Final File
 */
use yii\grid\GridView;
use yii\widgets\Pjax;

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
            'attribute' => 'serial',
        ],
        [   
            'attribute' => 'create_time',
            'filter'    => false,
            'format'    => 'datetime',
        ],
        [   
            'attribute' => 'expire_time',
            'filter'    => false, 
            'format'    => 'datetime',
        ],
        [   
            'attribute' => 'status',
            'filter'    => [
                            'activate' => Yii::t('app', 'Activate'),
                            'available' => Yii::t('app', 'Available')
            ],
            'value'     => function ($model) {
                return Yii::t('app', $model->status);
            },
        ],
    ],
]);
Pjax::end();